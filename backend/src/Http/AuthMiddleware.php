<?php

declare(strict_types=1);

namespace App\Http;

use App\Exceptions\UnauthorizedException;

class AuthMiddleware
{
    public static function requireLogin(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            throw new UnauthorizedException('Você precisa estar logado para acessar este recurso.');
        }
    }
}
