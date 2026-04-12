<?php

namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Hash;
	use App\Models\User;

	class AuthController extends Controller
	{
		// 🔐 LOGIN
		public function login(Request $request)
		{
			$request->validate([
				'email' => 'required|email',
				'password' => 'required'
			]);

			if (!auth()->attempt($request->only('email', 'password'))) {
				return response()->json([
					'message' => 'Invalid credentials'
				], 401);
			}

			return response()->json(auth()->user());
		}

		// 👤 CURRENT USER
		public function me(Request $request)
		{
			return response()->json($request->user());
		}

		// 🚪 LOGOUT
		public function logout(Request $request)
		{
			auth()->logout();

			return response()->json([
				'message' => 'Logged out'
			]);
		}
	}
