<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class LoginController extends Controller
{

    /**
     * Faz o login do usuário e gera um token de acesso se as credenciais estiverem corretas.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        // Obtém as credenciais do formulário
        $credentials = $request->only('email', 'password');

        // Tenta autenticar o usuário com as credenciais fornecidas
        if (Auth::attempt($credentials)) {
            // Obtém o usuário autenticado
            $user = Auth::user();

            // Revoque tokens antigos do usuário, se houver
            $user->tokens->each(function ($token, $key) {
                $token->delete();
            });

            // Cria um novo token de acesso
            $token = $user->createToken('PassportToken')->accessToken;

            // Retorna uma resposta JSON com os detalhes do usuário e o token de acesso
            return response()->json([
                'user' => $user,
                'access_token' => $token,
            ]);
        }

        // Retorna uma resposta de erro se as credenciais estiverem incorretas
        return response()->json(['error' => 'Credenciais inválidas'], 401);
    }

    /**
     * Revoga o token de acesso atual do usuário, efetuando o logout.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Revoga o token de acesso atual
        $request->user()->token()->revoke();

        // Retorna uma resposta JSON informando que o logout foi bem-sucedido
        return response()->json(['message' => 'Logout efetuado com sucesso']);
    }

    /**
     * Verifica se o token de acesso é válido.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkToken(Request $request)
    {
        // Verifica se o token de acesso é válido
        if (Auth::guard('api')->check()) {
            // Retorna uma resposta JSON indicando que o token é válido
            return response()->json(['message' => 'Token válido']);
        }

        // Retorna uma resposta de erro se o token não for válido
        return response()->json(['error' => 'Token inválido'], 401);
    }
}
