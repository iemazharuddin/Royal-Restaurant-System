<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Throwable;

class FirebaseAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'id_token' => ['required', 'string'],
        ]);

        try {
            $verifiedToken = Firebase::auth()->verifyIdToken(
                $request->input('id_token')
            );

            $claims = $verifiedToken->claims();

            $email = strtolower((string) $claims->get('email'));
            $emailVerified = (bool) $claims->get('email_verified');
            $name = (string) ($claims->get('name') ?? 'Google User');

            if (!$email || !$emailVerified) {
                return response()->json([
                    'message' => 'Google email is not verified.',
                ], 401);
            }

            $user = User::where('email', $email)->first();

            if (!$user) {
                $user = new User();

                $user->forceFill([
                    'name' => $name,
                    'email' => $email,
                    'password' => Str::random(40),
                    'email_verified_at' => now(),
                ]);

                $user->save();
            }

            // Update verification for existing Google users
            if (!$user->email_verified_at) {
                $user->forceFill([
                    'email_verified_at' => now(),
                ])->save();
            }

            // Assign Customer only when no role exists
            if (!$user->hasAnyRole([
                'Super Admin',
                'Admin',
                'Manager',
                'Cashier',
                'Staff',
                'Customer',
            ])) {
                $user->assignRole('Customer');
            }

            Auth::login($user);

            $request->session()->regenerate();

            // Admin roles go to dashboard
            if ($user->hasAnyRole([
                'Super Admin',
                'Admin',
                'Manager',
                'Cashier',
                'Staff',
            ])) {
                $redirect = route('dashboard');
            } else {
                $redirect = url('/');
            }

            return response()->json([
                'success' => true,
                'message' => 'Google login successful!',
                'redirect' => $redirect,
            ]);

        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Google login failed. Please try again.',
            ], 401);
        }
    }
}
