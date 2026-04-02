<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Exception\Auth\EmailExists;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\Auth\UserNotFound;

class AuthController extends Controller
{
    public function __construct(
        protected Auth $auth,
        protected Database $database
    ) {}

    public function showLogin()
    {
        return view('pages.login');
    }

    public function showRegister()
    {
        return view('pages.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'fullname'         => 'required|string',
            'email'            => 'required|email',
            'password'         => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        try {
            // Create user in Firebase Auth
            $user = $this->auth->createUserWithEmailAndPassword(
                $request->email,
                $request->password
            );

            // Update display name
            $this->auth->updateUser($user->uid, [
                'displayName' => $request->fullname,
            ]);

            // Save additional data to Realtime Database
            $this->database
                ->getReference('users/' . $user->uid)
                ->set([
                    'fullname'   => $request->fullname,
                    'email'      => $request->email,
                    'created_at' => now()->toDateTimeString(),
                ]);

            return redirect('/login')->with('success', 'Account created! Please login.');

        } catch (EmailExists $e) {
            return back()->withErrors(['email' => 'Email is already in use.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        try {
            $signInResult = $this->auth->signInWithEmailAndPassword(
                $request->email,
                $request->password
            );

            $user = $signInResult->data();

            session([
                'firebase_uid'   => $user['localId'],
                'firebase_token' => $user['idToken'],
                'user_email'     => $user['email'],
            ]);

            return redirect('/dashboard');

        } catch (InvalidPassword | UserNotFound $e) {
            return back()->withErrors(['email' => 'Invalid email or password.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }
}