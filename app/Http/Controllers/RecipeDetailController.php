<?php

namespace App\Http\Controllers;

use App\Services\MealDbService;
use App\Services\SpoonacularService;
use Illuminate\Http\Request;

class RecipeDetailController extends Controller
{
    protected MealDbService $mealDb;
    protected SpoonacularService $spoonacular;

    public function __construct(MealDbService $mealDb, SpoonacularService $spoonacular)
    {
        $this->mealDb      = $mealDb;
        $this->spoonacular = $spoonacular;
    }

    public function show(string $id)
    {
        // Check if this recipe came from Spoonacular
        if (str_starts_with($id, 'spoon_')) {
            $spoonId = str_replace('spoon_', '', $id);
            $spoonData = $this->spoonacular->getById($spoonId);
            $nutrition = $this->spoonacular->extractInfo($spoonData);

            return view('pages.recipe', [
                'meal'        => $spoonData,
                'ingredients' => $spoonData['ingredients'] ?? [],
                'nutrition'   => $nutrition,
                'source'      => 'spoonacular',
            ]);
        }

        // Otherwise use TheMealDB as normal
        $meal = $this->mealDb->getById($id);

        if (!$meal) {
            abort(404, 'Recipe not found.');
        }

        $ingredients     = $this->mealDb->extractIngredients($meal);
        $spoonacularData = $this->spoonacular->searchByName($meal['strMeal']);
        $nutrition       = $this->spoonacular->extractInfo($spoonacularData);

        return view('pages.recipe', [
            'meal'        => $meal,
            'ingredients' => $ingredients,
            'nutrition'   => $nutrition,
            'source'      => 'mealdb',
        ]);
    }
}