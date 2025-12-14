<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Database\Database;
use App\Repositories\UserRepository;
use App\Services\UserService;

if (php_sapi_name() !== 'cli') {
    die("Execução permitida apenas em CLI." . PHP_EOL);
}

// CONFIGURAÇÃO DO NOVO USUÁRIO
// Altere os dados abaixo conforme necessário
$userData = [
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => 'secret123',
    'role' => 'admin'
];

echo "=== Criação de Usuário (Modo Script) ===" . PHP_EOL;

try {
    $database = new Database();
    $userRepository = new UserRepository($database);
    $userService = new UserService($userRepository);

    echo "Criando usuário: {$userData['name']} ({$userData['email']})..." . PHP_EOL;

    $createdUser = $userService->register($userData);

    echo "[SUCESSO] Usuário criado com sucesso." . PHP_EOL;
    echo "ID: " . $createdUser->getId() . PHP_EOL;

} catch (InvalidArgumentException $e) {
    echo "[ERRO] Dados inválidos: " . $e->getMessage() . PHP_EOL;
} catch (Exception $e) {
    echo "[ERRO] Exceção: " . $e->getMessage() . PHP_EOL;
}
