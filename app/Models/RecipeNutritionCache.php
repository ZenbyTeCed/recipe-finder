<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeNutritionCache extends Model
{
    protected $table = 'recipe_nutrition_cache';
    protected $fillable = [
        'recipe_id',
        'recipe_name',
        'cook_time',
        'calories',
        'protein',
        'carbs',
        'fat',
        'fiber',
    ];

    public static function findByRecipeId($recipeId)
    {
        return self::where('recipe_id', $recipeId)->first();
    }

    public static function upsert($recipeId, $data)
    {
        return self::updateOrCreate(
            ['recipe_id' => $recipeId],
            array_merge(['recipe_name' => $data['recipe_name'] ?? ''], [
                'cook_time' => $data['cook_time'] ?? null,
                'calories'  => $data['calories'] ?? 0,
                'protein'   => $data['protein'] ?? 0,
                'carbs'     => $data['carbs'] ?? 0,
                'fat'       => $data['fat'] ?? 0,
                'fiber'     => $data['fiber'] ?? 0,
            ])
        );
    }
}
