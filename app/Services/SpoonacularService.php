<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SpoonacularService
{
    protected string $baseUrl = 'https://api.spoonacular.com';
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.spoonacular.key');
    }

    // Search recipe by name to get time + nutrition
    public function searchByName(string $name): ?array
    {
        $response = Http::get("{$this->baseUrl}/recipes/complexSearch", [
            'apiKey'         => $this->apiKey,
            'query'          => $name,
            'number'         => 1,
            'addRecipeNutrition' => true,
            'addRecipeInformation' => true,
        ]);

        return $response->json()['results'][0] ?? null;
    }

    // Extract only what we need
    public function extractInfo(?array $data): array
    {
        if (!$data) {
            return [
                'cook_time' => null,
                'calories'  => null,
                'protein'   => null,
                'carbs'     => null,
                'fat'       => null,
            ];
        }

        $nutrients = collect($data['nutrition']['nutrients'] ?? []);

        return [
            'cook_time' => $data['readyInMinutes'] ?? null,
            'calories'  => (int) ($nutrients->firstWhere('name', 'Calories')['amount'] ?? 0),
            'protein'   => (int) ($nutrients->firstWhere('name', 'Protein')['amount'] ?? 0),
            'carbs'     => (int) ($nutrients->firstWhere('name', 'Carbohydrates')['amount'] ?? 0),
            'fat'       => (int) ($nutrients->firstWhere('name', 'Fat')['amount'] ?? 0),
        ];
    }

    public function searchByCuisine(string $cuisine, int $number = 20): array
    {
        $response = Http::get("{$this->baseUrl}/recipes/complexSearch", [
            'apiKey'               => $this->apiKey,
            'cuisine'              => $cuisine,
            'number'               => $number,
            'addRecipeInformation' => true,
        ]);

        return collect($response->json()['results'] ?? [])->map(function ($recipe) {
            return [
                'idMeal'       => 'spoon_' . $recipe['id'],
                'strMeal'      => $recipe['title'],
                'strMealThumb' => $recipe['image'] ?? 'https://placehold.co/400x250',
                'strCategory'  => $recipe['dishTypes'][0] ?? null,
                'strArea'      => 'Filipino',
            ];
        })->all();
    }

    public function getById(string $id): ?array
    {
        $response = Http::get("{$this->baseUrl}/recipes/{$id}/information", [
            'apiKey'             => $this->apiKey,
            'includeNutrition'   => true,
        ]);

        $recipe = $response->json();

        return [
            'strMeal'         => $recipe['title'] ?? null,
            'strMealThumb'    => $recipe['image'] ?? null,
            'strArea'         => 'Filipino',
            'strCategory'     => $recipe['dishTypes'][0] ?? null,
            'strInstructions' => strip_tags($recipe['summary'] ?? ''),
            'readyInMinutes'  => $recipe['readyInMinutes'] ?? null,
            'nutrition'       => $recipe['nutrition'] ?? null,
            'ingredients'     => collect($recipe['extendedIngredients'] ?? [])
                ->map(fn($i) => $i['original'])
                ->all(),
        ];
    }
}