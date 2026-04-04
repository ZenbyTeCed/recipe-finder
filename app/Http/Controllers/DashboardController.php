<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class DashboardController extends Controller
{
    public function __construct(protected Database $database) {}

    public function index()
    {
        $uid = session('firebase_uid');

        $goals = $this->database
            ->getReference('users/' . $uid . '/goals')
            ->getValue();

        $goals = $goals ?? [
            'calories' => 2000,
            'protein'  => 150,
            'carbs'    => 200,
            'fat'      => 65,
        ];

        // Update session with latest goals
        session(['goals' => $goals]);

        return view('pages.dashboard', compact('goals'));
    }
}