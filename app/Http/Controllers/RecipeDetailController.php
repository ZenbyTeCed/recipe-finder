<?php

namespace App\Http\Controllers;

use App\Models\RecipeNutritionCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

        // Check if already favorited in Firebase
        $favoriteData = $this->database
            ->getReference('favorites/' . $uid . '/' . $id)
            ->getValue();

        $isFavorited = !empty($favoriteData);

        // Nutrition handling
        $nutrition = null;
        $cookTime = null;
        $tags = [];
        $nutritionMessage = null;

        // Step 1: Check cache first
        $cachedNutrition = RecipeNutritionCache::findByRecipeId($id);
        if ($cachedNutrition) {
            $nutrition = $this->buildNutritionArray($cachedNutrition);
            $cookTime = $cachedNutrition->cook_time;
        } else {
            // Step 2: Fetch from Spoonacular API
            try {
                $searchResponse = Http::timeout(10)->get('https://api.spoonacular.com/recipes/complexSearch', [
                    'apiKey' => env('SPOONACULAR_API_KEY'),
                    'query' => $meal['strMeal'],
                    'number' => 5,
                ]);

                // Check if API limit is reached
                if (in_array($searchResponse->status(), [402, 429])) {
                    $nutritionMessage = 'Spoonacular API limit reached. Please try again later.';
                } elseif ($searchResponse->successful()) {
                    $searchData = $searchResponse->json();

                    if (!empty($searchData['results'])) {
                        foreach ($searchData['results'] as $result) {
                            $recipeId = $result['id'];

                            $nutritionResponse = Http::timeout(10)->get("https://api.spoonacular.com/recipes/{$recipeId}/nutritionWidget.json", [
                                'apiKey' => env('SPOONACULAR_API_KEY'),
                            ]);

                            // Check if API limit is reached while fetching nutrition
                            if (in_array($nutritionResponse->status(), [402, 429])) {
                                $nutritionMessage = 'Spoonacular API limit reached. Please try again later.';
                                break;
                            }

                            if ($nutritionResponse->successful()) {
                                $nutritionData = $nutritionResponse->json();

                                if (!empty($nutritionData['calories'])) {
                                    $cookTime = $result['readyInMinutes'] ?? null;

                                    $nutrition = [
                                        'calories' => (int) ($nutritionData['calories'] ?? 0),
                                        'protein'  => (int) preg_replace('/[^0-9]/', '', $nutritionData['protein'] ?? '0'),
                                        'carbs'    => (int) preg_replace('/[^0-9]/', '', $nutritionData['carbs'] ?? '0'),
                                        'fat'      => (int) preg_replace('/[^0-9]/', '', $nutritionData['fat'] ?? '0'),
                                        'fiber'    => 0,
                                    ];

                                    if (!empty($nutritionData['nutrients'])) {
                                        $fiberNutrient = collect($nutritionData['nutrients'])->firstWhere('name', 'Fiber');
                                        if ($fiberNutrient) {
                                            $servings = $nutritionData['servings'] ?? 1;
                                            $nutrition['fiber'] = (int) round(($fiberNutrient['amount'] ?? 0) / max($servings, 1));
                                        }
                                    }

                                    // Cache the real data
                                    RecipeNutritionCache::upsert($id, array_merge($nutrition, [
                                        'recipe_name' => $meal['strMeal'],
                                        'cook_time' => $cookTime,
                                    ]));

                                    break;
                                }
                            }
                        }

                        if (!$nutrition && !$nutritionMessage) {
                            $nutritionMessage = 'Spoonacular could not find nutrition data for this recipe.';
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

        // Step 3: Fallback values if no real data
        if (!$nutrition) {
            $nutrition = [
                'calories' => 0,
                'protein'  => 0,
                'carbs'    => 0,
                'fat'      => 0,
                'fiber'    => 0,
            ];
        }

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
            'nutritionMessage'
        ));
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