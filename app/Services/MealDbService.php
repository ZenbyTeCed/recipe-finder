<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MealDbService
{
    protected string $baseUrl = 'https://www.themealdb.com/api/json/v1/1';

    // Search by name
    public function search(string $query): array
    {
        $response = Http::get("{$this->baseUrl}/search.php", ['s' => $query]);
        return $response->json()['meals'] ?? [];
    }

    // Filter by category (Breakfast, Seafood, Dessert...)
    public function filterByCategory(string $category): array
    {
        $response = Http::get("{$this->baseUrl}/filter.php", ['c' => $category]);
        return $response->json()['meals'] ?? [];
    }

    // Filter by area/cuisine (Italian, French, Japanese...)
    public function filterByArea(string $area): array
    {
        $response = Http::get("{$this->baseUrl}/filter.php", ['a' => $area]);
        return $response->json()['meals'] ?? [];
    }

    // Get all available areas/cuisines from TheMealDB
    public function getAllAreas(): array
    {
        $response = Http::get("{$this->baseUrl}/list.php", ['a' => 'list']);
        $areas = $response->json()['meals'] ?? [];
        return collect($areas)->pluck('strArea')->sort()->values()->all();
    }

    // Get full recipe detail by ID
    public function getById(string $id): ?array
    {
        $response = Http::get("{$this->baseUrl}/lookup.php", ['i' => $id]);
        return $response->json()['meals'][0] ?? null;
    }

    // Extract ingredients list from meal detail
    public function extractIngredients(array $meal): array
    {
        $ingredients = [];

        for ($i = 1; $i <= 20; $i++) {
            $ingredient = trim($meal["strIngredient{$i}"] ?? '');
            $measure    = trim($meal["strMeasure{$i}"] ?? '');

            if ($ingredient === '') break;

            $ingredients[] = "{$measure} {$ingredient}";
        }

        return $ingredients;
    }

    // Normalize meal data for card display
    public function normalizeCard(array $meal): array
    {

        if (!isset($meal['strCategory']) && !isset($meal['strArea'])) {
            $detail = $this->getById($meal['idMeal']);
            return [
                'id'       => $meal['idMeal'],
                'name'     => $meal['strMeal'],
                'image'    => $meal['strMealThumb'],
                'category' => $detail['strCategory'] ?? null,
                'area'     => $detail['strArea'] ?? null,
            ];
        }

        return [
            'id'       => $meal['idMeal'],
            'name'     => $meal['strMeal'],
            'image'    => $meal['strMealThumb'],
            'category' => $meal['strCategory'] ?? null,
            'area'     => $meal['strArea'] ?? null,
        ];
    }
}