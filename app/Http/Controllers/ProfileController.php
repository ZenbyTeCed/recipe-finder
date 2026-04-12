<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;

class ProfileController extends Controller
{
    public function __construct(
        protected Auth $auth,
        protected Database $database
    ) {}

    public function update(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'calorie'  => 'nullable|numeric|min:0',
            'protein'  => 'nullable|numeric|min:0',
            'carbs'    => 'nullable|numeric|min:0',
            'fat'      => 'nullable|numeric|min:0',
        ]);

        try {
            $uid = session('firebase_uid');

            // Update display name in Firebase Auth
            $this->auth->updateUser($uid, [
                'displayName' => $request->fullname,
            ]);

            // Save nutritional goals to Realtime Database
            $this->database
                ->getReference('users/' . $uid . '/goals')
                ->set([
                    'calories' => $request->calorie ?? 2000,
                    'protein'  => $request->protein ?? 150,
                    'carbs'    => $request->carbs ?? 200,
                    'fat'      => $request->fat ?? 65,
                ]);

            // Update session
            session([
                'user_fullname' => $request->fullname,
                'goals' => [
                    'calories' => $request->calorie ?? 2000,
                    'protein'  => $request->protein ?? 150,
                    'carbs'    => $request->carbs ?? 200,
                    'fat'      => $request->fat ?? 65,
                ]
            ]);

            return redirect('/profile')->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $uid = session('firebase_uid');

            if (!$uid) {
                return redirect('/login')->with('error', 'User session not found.');
            }

            // Delete user-related data from Realtime Database
            $this->database->getReference('users/' . $uid)->remove();
            $this->database->getReference('meal_logs/' . $uid)->remove();
            $this->database->getReference('favorites/' . $uid)->remove();

            // Delete user from Firebase Auth
            $this->auth->deleteUser($uid);

            // Clear session
            session()->flush();

            return redirect('/')->with('success', 'Account deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}