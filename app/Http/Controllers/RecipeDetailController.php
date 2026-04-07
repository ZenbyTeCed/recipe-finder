<?php

namespace App\Http\Controllers;

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

        // Spoonacular
        $nutrition = null;
        $cookTime = null;
        $tags = [];

        $searchResponse = Http::get('https://api.spoonacular.com/recipes/complexSearch', [
            'apiKey' => env('SPOONACULAR_API_KEY'),
            'query' => $meal['strMeal'],
            'number' => 5,
            'addRecipeNutrition' => true,
        ]);

        $searchData = $searchResponse->json();

        if (!empty($searchData['results'])) {
            $spoonacularData = collect($searchData['results'])
                ->first(fn($r) =>
                    str_contains(strtolower($r['title']), strtolower($meal['strMeal']))
                ) ?? $searchData['results'][0];

            $cookTime = $spoonacularData['readyInMinutes'];

            if (!empty($spoonacularData['nutrition']['nutrients'])) {
                $nutrientMap = [];

                foreach ($spoonacularData['nutrition']['nutrients'] as $n) {
                    $nutrientMap[$n['name']] = round($n['amount']);
                }

                $nutrition = [
                    'calories' => $nutrientMap['Calories'] ?? 0,
                    'protein'  => $nutrientMap['Protein'] ?? 0,
                    'carbs'    => $nutrientMap['Carbohydrates'] ?? 0,
                    'fat'      => $nutrientMap['Fat'] ?? 0,
                    'fiber'    => $nutrientMap['Fiber'] ?? 0,
                ];

                $total =
                    ($nutrition['protein'] * 4) +
                    ($nutrition['carbs'] * 4) +
                    ($nutrition['fat'] * 9);

                $nutrition['protein_pct'] = $total > 0 ? round(($nutrition['protein'] * 4 / $total) * 100) : 0;
                $nutrition['carbs_pct']   = $total > 0 ? round(($nutrition['carbs'] * 4 / $total) * 100) : 0;
                $nutrition['fat_pct']     = $total > 0 ? round(($nutrition['fat'] * 9 / $total) * 100) : 0;
            }
        }

        // Fallback
        if (!$nutrition) {
            $seed = (int) $id;

            $nutrition = [
                'calories' => 300 + ($seed % 301),
                'protein'  => 10 + ($seed % 31),
                'carbs'    => 20 + ($seed % 51),
                'fat'      => 10 + ($seed % 21),
                'fiber'    => 2 + ($seed % 9),
            ];

            $total =
                ($nutrition['protein'] * 4) +
                ($nutrition['carbs'] * 4) +
                ($nutrition['fat'] * 9);

            $nutrition['protein_pct'] = round(($nutrition['protein'] * 4 / $total) * 100);
            $nutrition['carbs_pct']   = round(($nutrition['carbs'] * 4 / $total) * 100);
            $nutrition['fat_pct']     = round(($nutrition['fat'] * 9 / $total) * 100);
        }

        return view('pages.recipe', compact(
            'meal',
            'ingredients',
            'nutrition',
            'cookTime',
            'tags',
            'id',
            'isFavorited'
        ));
    }
}