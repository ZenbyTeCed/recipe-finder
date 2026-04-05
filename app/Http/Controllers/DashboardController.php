<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class DashboardController extends Controller
{
    public function __construct(protected Database $database) {}

    public function index()
    {
        $uid   = session('firebase_uid');
        $today = now()->toDateString();

        $goals = session('goals', [
            'calories' => 2000,
            'protein'  => 150,
            'carbs'    => 200,
            'fat'      => 65,
        ]);

        // Fetch today's meal logs
        $logs = $this->database
            ->getReference('meal_logs/' . $uid . '/' . $today)
            ->getValue();

        $consumed = [
            'calories' => 0,
            'protein'  => 0,
            'carbs'    => 0,
            'fat'      => 0,
        ];

        if ($logs) {
            foreach ($logs as $meal) {
                $consumed['calories'] += $meal['calories'] ?? 0;
                $consumed['protein']  += $meal['protein']  ?? 0;
                $consumed['carbs']    += $meal['carbs']    ?? 0;
                $consumed['fat']      += $meal['fat']      ?? 0;
            }
        }

        $goalsHit = [];
        if ($consumed['calories'] >= $goals['calories']) $goalsHit[] = '🔥 Calorie goal reached!';
        if ($consumed['protein']  >= $goals['protein'])  $goalsHit[] = '💪 Protein goal reached!';
        if ($consumed['carbs']    >= $goals['carbs'])    $goalsHit[] = '🌾 Carbs goal reached!';
        if ($consumed['fat']      >= $goals['fat'])      $goalsHit[] = '💧 Fat goal reached!';

        return view('pages.dashboard', compact('goals', 'consumed', 'goalsHit'));
    }
}