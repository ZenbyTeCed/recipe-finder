<?php

namespace App\Http\Controllers;

use App\Models\RecipeNutritionCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Kreait\Firebase\Contract\Database;

class RecipeDetailController extends Controller
{
    public function __construct(protected Database $database) {}

    public function show($id)
    {
        $uid = session('firebase_uid');

        // Get meal from TheMealDB
        $mealResponse = Http::get("https://www.themealdb.com/api/json/v1/1/lookup.php?i={$id}");
        $meal = $mealResponse->json()['meals'][0] ?? null;

        if (!$meal) {
            return redirect('/home')->with('error', 'Recipe not found.');
        }

        // Ingredients
        $ingredients = [];
        for ($i = 1; $i <= 20; $i++) {
            $ingredient = $meal["strIngredient{$i}"] ?? '';
            $measure    = $meal["strMeasure{$i}"] ?? '';

            if (!empty(trim($ingredient))) {
                $ingredients[] = trim($measure) . ' ' . trim($ingredient);
            }
        }

        $isFavorited = false;

        if ($uid) {
            // Check if already favorited in Firebase
            $favoriteData = $this->database
                ->getReference('favorites/' . $uid . '/' . $id)
                ->getValue();

            $isFavorited = !empty($favoriteData);
        }

        // Nutrition handling
        $nutrition = null;
        $cookTime = null;
        $tags = [];
        $nutritionMessage = null;

        // Step 1: Check cache first
        $cachedNutrition = RecipeNutritionCache::findByRecipeId($id);
        $cacheIsFresh = $cachedNutrition &&
            $cachedNutrition->updated_at &&
            $cachedNutrition->updated_at->gt(now()->subDays(30));

        $cacheHasUsableData = $cachedNutrition &&
            (
                (int) $cachedNutrition->calories > 0 ||
                (int) $cachedNutrition->protein > 0 ||
                (int) $cachedNutrition->carbs > 0 ||
                (int) $cachedNutrition->fat > 0
            );

        if ($cacheIsFresh && $cacheHasUsableData) {
            $nutrition = $this->buildNutritionArray($cachedNutrition);
            $cookTime = $cachedNutrition->cook_time;
        } else {

            // Step 2: Fetch from Spoonacular API
            try {
                $rateKey = 'spoonacular:' . ($uid ?: request()->ip());

                // Build a short ingredient list from TheMealDB
                $ingredientNames = [];
                for ($i = 1; $i <= 20; $i++) {
                    $ingredient = trim($meal["strIngredient{$i}"] ?? '');
                    if (!empty($ingredient)) {
                        $ingredientNames[] = strtolower($ingredient);
                    }
                }

                $ingredientNames = array_slice($ingredientNames, 0, 5);

                if (RateLimiter::tooManyAttempts($rateKey, 15)) {
                    $searchResponse = null;
                } else {
                    RateLimiter::hit($rateKey, 60);

                    if (count($ingredientNames) > 0) {
                        $searchResponse = Http::timeout(10)->get('https://api.spoonacular.com/recipes/findByIngredients', [
                            'apiKey' => env('SPOONACULAR_API_KEY'),
                            'ingredients' => implode(',', $ingredientNames),
                            'number' => 3,
                            'ranking' => 2,
                            'ignorePantry' => true,
                        ]);
                    } else {
                        $searchResponse = Http::timeout(10)->get('https://api.spoonacular.com/recipes/complexSearch', [
                            'apiKey' => env('SPOONACULAR_API_KEY'),
                            'query' => $meal['strMeal'],
                            'number' => 3,
                        ]);
                    }
                }

                if (!$searchResponse) {
                    $nutritionMessage = 'Spoonacular API limit reached. Please try again later.';
                } elseif (in_array($searchResponse->status(), [402, 429])) {
                    $nutritionMessage = 'Spoonacular API limit reached. Please try again later.';
                } elseif ($searchResponse->successful()) {
                    $searchData = $searchResponse->json();
                    $results = isset($searchData['results']) ? $searchData['results'] : $searchData;

                    if (!empty($results)) {
                        $mealName = strtolower($meal['strMeal']);

                        foreach ($results as $candidate) {
                            $recipeId = $candidate['id'];
                            $spoonName = strtolower($candidate['title'] ?? '');
                            $matchedIngredients = $candidate['usedIngredientCount'] ?? null;

                            similar_text($mealName, $spoonName, $percent);

                            $matchTooWeak = $matchedIngredients !== null
                                ? ($matchedIngredients < 1 || $percent < 15)
                                : ($percent < 15);

                            if ($matchTooWeak) {
                                continue;
                            }

                            // info endpoint
                            if (RateLimiter::tooManyAttempts($rateKey, 15)) {
                                $infoResponse = null;
                            } else {
                                RateLimiter::hit($rateKey, 60);
                                $infoResponse = Http::timeout(10)->get("https://api.spoonacular.com/recipes/{$recipeId}/information", [
                                    'apiKey' => env('SPOONACULAR_API_KEY'),
                                    'includeNutrition' => false,
                                ]);
                            }

                            if ($infoResponse && $infoResponse->successful()) {
                                $infoData = $infoResponse->json();
                                $cookTime = $infoData['readyInMinutes'] ?? null;
                            }

                            // nutrition endpoint
                            if (RateLimiter::tooManyAttempts($rateKey, 15)) {
                                $nutritionResponse = null;
                            } else {
                                RateLimiter::hit($rateKey, 60);
                                $nutritionResponse = Http::timeout(10)->get("https://api.spoonacular.com/recipes/{$recipeId}/nutritionWidget.json", [
                                    'apiKey' => env('SPOONACULAR_API_KEY'),
                                ]);
                            }

                            if (!$nutritionResponse || in_array($nutritionResponse->status(), [402, 429])) {
                                $nutritionMessage = 'Spoonacular API limit reached. Please try again later.';
                                break;
                            }

                            if (!$nutritionResponse->successful()) {
                                continue;
                            }

                            $nutritionData = $nutritionResponse->json();

                            if (empty($nutritionData['calories'])) {
                                continue;
                            }

                            $nutritionCandidate = [
                                'calories' => (int) ($nutritionData['calories'] ?? 0),
                                'protein'  => (int) preg_replace('/[^0-9]/', '', $nutritionData['protein'] ?? '0'),
                                'carbs'    => (int) preg_replace('/[^0-9]/', '', $nutritionData['carbs'] ?? '0'),
                                'fat'      => (int) preg_replace('/[^0-9]/', '', $nutritionData['fat'] ?? '0'),
                                'fiber'    => 0,
                            ];

                            // Reject suspicious data
                            if ($this->isNutritionSuspicious($nutritionCandidate)) {
                                continue; // try next candidate
                            }

                            $nutrition = $nutritionCandidate;

                            if (!empty($nutritionData['nutrients'])) {
                                $fiberNutrient = collect($nutritionData['nutrients'])->firstWhere('name', 'Fiber');
                                $nutrition['fiber'] = $fiberNutrient && !empty($fiberNutrient['amount'])
                                    ? (int) round($fiberNutrient['amount'])
                                    : 0;
                            }

                            RecipeNutritionCache::upsert($id, array_merge($nutrition, [
                                'recipe_name' => $meal['strMeal'],
                                'cook_time' => $cookTime,
                            ]));

                            // first valid match wins
                            break;
                        }

                        if (!$nutrition && !$nutritionMessage) {
                            $nutritionMessage = 'Spoonacular could not find reliable nutrition data for this recipe.';
                        }
                    } else {
                        $nutritionMessage = 'Spoonacular could not find a matching recipe.';
                    }
                } else {
                    $nutritionMessage = 'Unable to fetch nutrition data right now.';
                }
            } catch (\Exception $e) {
                \Log::error('Spoonacular API error: ' . $e->getMessage(), [
                    'recipe' => $meal['strMeal'],
                ]);

                $nutritionMessage = 'Error fetching nutrition data. Please try again later.';
            }
        }

        $isEstimatedNutrition = false;

        // Step 3: Fallback values if no real data
        if (!$nutrition) {
            $estimatedNutrition = $this->getIngredientFallbackNutrition($ingredientNames ?? []);

            if ($estimatedNutrition) {
                $nutrition = $estimatedNutrition;
                $nutritionMessage = 'Estimated nutrition based on main ingredients. Spoonacular could not find exact data for this recipe, so values may vary.';
                $isEstimatedNutrition = true;
            } else {
                $nutrition = [
                    'calories' => 0,
                    'protein'  => 0,
                    'carbs'    => 0,
                    'fat'      => 0,
                    'fiber'    => 0,
                ];
            }
        }

        $hasRealNutrition = $nutrition && $nutrition['calories'] > 0;
        $shouldForceManualLog = !$hasRealNutrition;

        // Calculate macro percentages
        $total =
            ($nutrition['protein'] * 4) +
            ($nutrition['carbs'] * 4) +
            ($nutrition['fat'] * 9);

        $nutrition['protein_pct'] = $total > 0 ? round(($nutrition['protein'] * 4 / $total) * 100) : 0;
        $nutrition['carbs_pct']   = $total > 0 ? round(($nutrition['carbs'] * 4 / $total) * 100) : 0;
        $nutrition['fat_pct']     = $total > 0 ? round(($nutrition['fat'] * 9 / $total) * 100) : 0;

        return view('pages.recipe', compact(
            'meal',
            'ingredients',
            'nutrition',
            'cookTime',
            'tags',
            'id',
            'isFavorited',
            'nutritionMessage',
            'hasRealNutrition',
            'isEstimatedNutrition',
            'shouldForceManualLog'
        ));
    }

    private function isNutritionSuspicious(array $nutrition): bool
    {
        return
            $nutrition['calories'] > 1200 ||   // too high per serving
            $nutrition['calories'] < 50 ||     // too low (probably wrong)
            $nutrition['protein'] > 80 ||
            $nutrition['carbs'] > 150 ||
            $nutrition['fat'] > 70;
    }

    private function getIngredientFallbackNutrition(array $ingredientNames): ?array
    {
        $joined = strtolower(implode(' ', $ingredientNames));

        if (str_contains($joined, 'chicken')) {
            return [
                'calories' => 280,
                'protein'  => 24,
                'carbs'    => 10,
                'fat'      => 15,
                'fiber'    => 1,
            ];
        }

        if (str_contains($joined, 'beef')) {
            return [
                'calories' => 320,
                'protein'  => 23,
                'carbs'    => 9,
                'fat'      => 20,
                'fiber'    => 1,
            ];
        }

        if (str_contains($joined, 'pork')) {
            return [
                'calories' => 340,
                'protein'  => 22,
                'carbs'    => 8,
                'fat'      => 24,
                'fiber'    => 1,
            ];
        }

        if (
            str_contains($joined, 'fish') ||
            str_contains($joined, 'tuna') ||
            str_contains($joined, 'salmon') ||
            str_contains($joined, 'bangus') ||
            str_contains($joined, 'tilapia')
        ) {
            return [
                'calories' => 240,
                'protein'  => 22,
                'carbs'    => 6,
                'fat'      => 12,
                'fiber'    => 1,
            ];
        }

        return null;
    }

    private function buildNutritionArray($cachedData): array
    {
        return [
            'calories' => $cachedData->calories,
            'protein'  => $cachedData->protein,
            'carbs'    => $cachedData->carbs,
            'fat'      => $cachedData->fat,
            'fiber'    => $cachedData->fiber,
        ];
    }
}
