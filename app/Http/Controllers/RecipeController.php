<?php

namespace App\Http\Controllers;

use App\Services\MealDbService;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    protected MealDbService $mealDb;

    public function __construct(MealDbService $mealDb)
    {
        $this->mealDb = $mealDb;
        
    }

    public function index(Request $request)
    {
        $query    = $request->input('query');
        $category = $request->input('category');
        $area     = $request->input('area');

        $meals = [];

        $validCategories = [
            'Breakfast', 'Seafood', 'Dessert', 'Vegetarian',
            'Chicken', 'Beef', 'Pasta', 'Lamb', 'Miscellaneous',
        ];

        // Dynamically fetched from TheMealDB — always up to date
        $validAreas = $this->mealDb->getAllAreas();

        if ($query) {
            // Search by name first
            $byName = $this->mealDb->search($query);

            // Also search by category and area if query matches
            $byCategory = $this->mealDb->filterByCategory($query);
            $byArea     = $this->mealDb->filterByArea($query);

            // Merge all results and remove duplicates by meal ID
            $meals = collect($byName)
                ->merge($byCategory)
                ->merge($byArea)
                ->unique('idMeal')
                ->values()
                ->all();

        } elseif ($category && $area) {
            $byCategory = $this->mealDb->filterByCategory($category);
            $meals = collect($byCategory)->filter(function ($meal) use ($area) {
                $detail = $this->mealDb->getById($meal['idMeal']);
                return isset($detail['strArea']) &&
                    strtolower($detail['strArea']) === strtolower($area);
            })->values()->all();

        } elseif ($category) {
            $meals = $this->mealDb->filterByCategory($category);

        } elseif ($area) {
            $meals = $this->mealDb->filterByArea($area);

        } else {
            $famousSearches = ['pasta', 'sushi', 'burger', 'curry', 'steak', 'pizza', 'soup', 'tacos', 'chicken', 'beef', 'salad', 'rice'];
            shuffle($famousSearches);

            $meals = [];

            foreach ($famousSearches as $term) {
                $results = $this->mealDb->search($term);
                $meals   = collect($meals)->merge($results)->unique('idMeal')->values()->all();

                if (count($meals) >= 12) break;
            }
        }

        $recipes = collect($meals)->map(function ($meal) {
            return $this->mealDb->normalizeCard($meal);
        })->values()->all();

        return view('pages.home', [
            'recipes'         => $recipes,
            'query'           => $query,
            'category'        => $category,
            'area'            => $area,
            'totalCount'      => count($recipes),
            'validCategories' => $validCategories,
            'validAreas'      => $validAreas,
        ]);
    }
}