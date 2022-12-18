<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $attr = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string|min:6'
        ]);

        $user = User::where('login', '=', $request->input('login'))->first();

        if ($user && $user->status == User::STATUS_ACTIVE && Auth::attempt($attr)) {
            $request->session()->regenerate();

            return response()->json([], 204);
        }

        return response()->json([
            'message' => 'Неверные учетные данные'
        ], 401);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([], 204);
    }
}
