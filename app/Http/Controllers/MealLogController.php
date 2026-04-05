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
                    'meal_type' => $request->meal_type ?? 'Lunch',
                    'calories'  => (float) $request->calories,
                    'protein'   => (float) $request->protein,
                    'carbs'     => (float) $request->carbs,
                    'fat'       => (float) $request->fat,
                    'logged_at' => now()->toDateTimeString(),
                ]);

            // Recalculate totals
            $logs = $this->database
                ->getReference('meal_logs/' . $uid . '/' . $today)
                ->getValue();

            $consumed = ['calories' => 0, 'protein' => 0, 'carbs' => 0, 'fat' => 0];
            if ($logs) {
                foreach ($logs as $meal) {
                    $consumed['calories'] += $meal['calories'] ?? 0;
                    $consumed['protein']  += $meal['protein']  ?? 0;
                    $consumed['carbs']    += $meal['carbs']    ?? 0;
                    $consumed['fat']      += $meal['fat']      ?? 0;
                }
            }

            // Check goals
            $goals = session('goals', [
                'calories' => 2000,
                'protein'  => 150,
                'carbs'    => 200,
                'fat'      => 65,
            ]);

            $flameSvg   = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4"/></svg>';
            $drumSvg    = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.4 15.63a7.875 6 135 1 1 6.23-6.23 4.5 3.43 135 0 0-6.23 6.23"/><path d="m8.29 12.71-2.6 2.6a2.5 2.5 0 1 0-1.65 4.65A2.5 2.5 0 1 0 8.7 18.3l2.59-2.59"/></svg>';
            $wheatSvg   = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22 16 8"/><path d="M3.47 12.53 5 11l1.53 1.53a3.5 3.5 0 0 1 0 4.94L5 19l-1.53-1.53a3.5 3.5 0 0 1 0-4.94Z"/></svg>';
            $dropletSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"/></svg>';

            $notifications = [];
            if ($consumed['calories'] >= $goals['calories']) $notifications[] = $flameSvg   . ' Calorie goal reached!';
            if ($consumed['protein']  >= $goals['protein'])  $notifications[] = $drumSvg    . ' Protein goal reached!';
            if ($consumed['carbs']    >= $goals['carbs'])    $notifications[] = $wheatSvg   . ' Carbs goal reached!';
            if ($consumed['fat']      >= $goals['fat'])      $notifications[] = $dropletSvg . ' Fat goal reached!';

            $allGoalsHit = count($notifications) === 4;

            return response()->json([
                'success'       => true,
                'message'       => 'Meal logged successfully!',
                'notifications' => $notifications,
                'allGoalsHit'   => $allGoalsHit,
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

            // TEMPORARY DEBUG
    $uid = session('firebase_uid');
    
    try {
        $this->database
            ->getReference('debug_test/' . $uid)
            ->set(['test' => 'working', 'time' => now()->toDateTimeString()]);

        return response()->json([
            'success' => true,
            'message' => 'Firebase write test — UID: ' . $uid,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Firebase error: ' . $e->getMessage(),
        ]);
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