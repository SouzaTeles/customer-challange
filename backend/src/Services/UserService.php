<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use InvalidArgumentException;

class UserService
{
    private const int COOKIE_EXPIRATION_OFFSET = 3600;

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data): User
    {
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            throw new InvalidArgumentException('Nome, e-mail e senha são obrigatórios');
        }

        if ($this->userRepository->findByEmail($data['email'])) {
            throw new InvalidArgumentException('E-mail já cadastrado');
        }

        $data['password'] = password_hash($data['password'], PASSWORD_ARGON2ID);

        $user = User::fromArray($data);
        return $this->userRepository->create($user);
    }

    public function authenticate(string $email, string $password): ?User
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !$user->verifyPassword($password)) {
            return null;
        }

        $this->storeSessionData($user);
        return $user;
    }

    private function storeSessionData(User $user): void
    {
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_name'] = $user->getName();
        $_SESSION['user_email'] = $user->getEmail();
        $_SESSION['user_role'] = $user->getRole();
    }

    public function logout(): void
    {
        $this->clearSessionData();
        $this->invalidateSessionCookie();
        $this->destroySessionServer();
    }

    private function clearSessionData(): void
    {
        $_SESSION = [];
    }

    private function invalidateSessionCookie(): void
    {
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                name: session_name(),
                value: '',
                expires_or_options: time() - self::COOKIE_EXPIRATION_OFFSET,
                path: $params["path"],
                domain: $params["domain"],
                secure: $params["secure"],
                httponly: $params["httponly"]
            );
        }
    }

    private function destroySessionServer(): void
    {
        session_destroy();
    }
}
