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
            $user = $this->auth->createUserWithEmailAndPassword(
                $request->email,
                $request->password
            );

            $this->auth->updateUser($user->uid, [
                'displayName' => $request->fullname,
            ]);

            // Save user profile to Realtime Database
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
        ], [
            'email.required' => 'Email is required.',
            'email.email'    => 'Invalid email format.',
            'password.required' => 'Password is required.',
        ]);

        try {
            $signInResult = $this->auth->signInWithEmailAndPassword(
                $request->email,
                $request->password
            );

            $user = $signInResult->data();

            // Get goals from Realtime Database
            $goals = $this->database
                ->getReference('users/' . $user['localId'] . '/goals')
                ->getValue();

            session([
                'firebase_uid'   => $user['localId'],
                'firebase_token' => $user['idToken'],
                'user_email'     => $user['email'],
                'user_fullname'  => $user['displayName'] ?? '',
                'goals'          => $goals ?? [
                    'calories' => 2000,
                    'protein'  => 150,
                    'carbs'    => 200,
                    'fat'      => 65,
                ],
            ]);

            return redirect('/home');

        } catch (InvalidPassword | UserNotFound $e) {
            return back()->withErrors(['email' => 'Invalid email or password.']);
        } catch (\Exception $e) {
            return back()->with('error', 'Invalid email or password');
        }
    }

    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }

    public function googleLogin(Request $request)
    {
        try {
            $idToken = $request->input('id_token');

            $verifiedToken = $this->auth->verifyIdToken($idToken);
            $uid = $verifiedToken->claims()->get('sub');

            $user = $this->auth->getUser($uid);

            // Save to Realtime Database if new user
            $existing = $this->database
                ->getReference('users/' . $uid)
                ->getValue();

            if (!$existing) {
                $this->database
                    ->getReference('users/' . $uid)
                    ->set([
                        'fullname'   => $user->displayName ?? '',
                        'email'      => $user->email ?? '',
                        'created_at' => now()->toDateTimeString(),
                    ]);
            }

            session([
                'firebase_uid'   => $uid,
                'firebase_token' => $idToken,
                'user_email'     => $user->email,
                'user_fullname'  => $user->displayName ?? '',
            ]);

            return response()->json(['redirect' => '/home']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
}