<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;

class ProfileController extends Controller
{
    public function __construct(protected Auth $auth) {}

    public function update(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
        ]);

        try {
            $uid = session('firebase_uid');

            // Update display name in Firebase Auth
            $this->auth->updateUser($uid, [
                'displayName' => $request->fullname,
            ]);

            // Update session
            session(['user_fullname' => $request->fullname]);

            return back()->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}