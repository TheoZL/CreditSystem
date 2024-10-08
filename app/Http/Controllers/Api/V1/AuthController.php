<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Method to Login the user
     *
     * @param AuthRequest $request
     * @return Response
     */
    public function login(AuthRequest $request):Response
    {
        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            $cookie = cookie('cookie_token', $token, 60 * 24);
            return response(["token"=>$token], Response::HTTP_OK)->withoutCookie($cookie);
        } else {
            return response(["message"=> __('auth.failed')  ],Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Logout the user
     *
     * @return Response
     */
    public function logout(Request $request):Response
    {
        $cookie = Cookie::forget('cookie_token');
        $request->user()->currentAccessToken()->delete();
        return response(["message"=>"Cierre de sesión OK"], Response::HTTP_OK)->withCookie($cookie);
    }
}
