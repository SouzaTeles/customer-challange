<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Response;
use App\Services\UserService;
use InvalidArgumentException;

class AuthController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(array $data): Response
    {
        try {
            $created = $this->userService->register($data);
            return Response::created($created->toArray());
        } catch (InvalidArgumentException $e) {
            return Response::badRequest($e->getMessage());
        }
    }

    public function login(array $data): Response
    {
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            return Response::badRequest('E-mail e senha são obrigatórios');
        }

        $user = $this->userService->authenticate($email, $password);

        if (!$user) {
            return Response::json(['error' => 'Credenciais inválidas'], 401);
        }

        return Response::json([
            'message' => 'Login realizado com sucesso',
            'user' => $user->toArray()
        ]);
    }

    public function logout(): Response
    {
        $this->userService->logout();
        return Response::json(['message' => 'Logout realizado com sucesso']);
    }
}
