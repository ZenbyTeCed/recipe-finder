<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class MealLogController extends Controller
{
    public function __construct(protected Database $database) {}

    public function index()
    {
        $uid = session('firebase_uid');
        $today = now()->toDateString();

        $logs = $this->database
            ->getReference('meal_logs/' . $uid . '/' . $today)
            ->getValue();

        $meals = [];
        $totals = ['calories' => 0, 'protein' => 0, 'carbs' => 0, 'fat' => 0];

        if ($logs) {
            foreach ($logs as $key => $meal) {
                $meal['key'] = $key;
                $meals[] = $meal;
                $totals['calories'] += $meal['calories'] ?? 0;
                $totals['protein']  += $meal['protein']  ?? 0;
                $totals['carbs']    += $meal['carbs']    ?? 0;
                $totals['fat']      += $meal['fat']      ?? 0;
            }
        }

        return view('pages.meal-log', compact('meals', 'totals', 'today'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'serving'  => 'required|string',
            'calories' => 'required|numeric',
            'protein'  => 'required|numeric',
            'carbs'    => 'required|numeric',
            'fat'      => 'required|numeric',
        ]);

        $uid   = session('firebase_uid');
        $today = now()->toDateString();

        try {
            $this->database
                ->getReference('meal_logs/' . $uid . '/' . $today)
                ->push([
                    'name'      => $request->name,
                    'serving'   => $request->serving,
                    'calories'  => $request->calories,
                    'protein'   => $request->protein,
                    'carbs'     => $request->carbs,
                    'fat'       => $request->fat,
                    'logged_at' => now()->toDateTimeString(),
                ]);

            return response()->json(['success' => true, 'message' => 'Meal logged successfully!']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        $uid     = session('firebase_uid');
        $today   = now()->toDateString();
        $mealKey = $request->key;

        $this->database
            ->getReference('meal_logs/' . $uid . '/' . $today . '/' . $mealKey)
            ->remove();

        return redirect('/meal-log')->with('success', 'Meal deleted successfully!');
    }
}