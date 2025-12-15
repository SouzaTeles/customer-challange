<?php

declare(strict_types=1);

require __DIR__ . '/src/bootstrap.php';

use App\Database\Database;
use App\Repositories\UserRepository;
use App\Services\UserService;

// Uso: php create_user.php <nome> <email> <senha>

$name = $argv[1];
$email = $argv[2];
$password = $argv[3];

try {
    $db = new Database();
    $repo = new UserRepository($db);
    $service = new UserService($repo);

    $service->register([
        'name' => $name,
        'email' => $email,
        'password' => $password
    ]);

    echo "Usuário criado com sucesso!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit(1);
}
