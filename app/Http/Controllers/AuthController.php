<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ], [
                'name.required' => 'Name is required.',
                'name.string' => 'Name must be a string.',
                'name.max' => 'Name should not exceed 255 characters.',
                'email.required' => 'Email is required.',
                'email.email' => 'Enter a valid email address.',
                'email.unique' => 'This email is already registered.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 6 characters.',
                'password.confirmed' => 'Password confirmation does not match.',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = auth()->login($user);

            return response()->json([
                'message' => 'User registered successfully!',
                'token' => $token,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong during registration.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string|min:6',
            ], [
                'email.required' => 'Email is required.',
                'email.email' => 'Enter a valid email address.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 6 characters.',
            ]);

            $credentials = $request->only(['email', 'password']);

            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Invalid email or password'], 401);
            }

            return response()->json([
                'message' => 'Login successful!',
                'token' => $token,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong during login.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
