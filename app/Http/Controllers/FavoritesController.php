<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class FavoritesController extends Controller
{
    public function __construct(protected Database $database) {}

    public function index()
    {
        $uid = session('firebase_uid');

        $data = $this->database
            ->getReference('favorites/' . $uid)
            ->getValue();

        $favorites = [];
        if ($data) {
            foreach ($data as $key => $recipe) {
                $recipe['recipe_id'] = $key;
                $favorites[] = $recipe;
            }
        }

        $count = count($favorites);

        return view('pages.favorites', compact('favorites', 'count'));
    }

    public function store(Request $request)
    {
        $uid      = session('firebase_uid');
        $recipeId = $request->recipe_id;

        try {
            $this->database
                ->getReference('favorites/' . $uid . '/' . $recipeId)
                ->set([
                    'name'     => $request->name,
                    'cuisine'  => $request->cuisine,
                    'category' => $request->category,
                    'time'     => $request->time,
                    'calories' => $request->calories,
                    'protein'  => $request->protein,
                    'carbs'    => $request->carbs,
                    'fat'      => $request->fat,
                    'image'    => $request->image,
                ]);

            return response()->json(['success' => true, 'message' => 'Recipe added to favorites!']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        $uid      = session('firebase_uid');
        $recipeId = $request->recipe_id;

        $this->database
            ->getReference('favorites/' . $uid . '/' . $recipeId)
            ->remove();

        return redirect('/favorites')->with('success', 'Recipe removed from favorites!');
    }
    
}