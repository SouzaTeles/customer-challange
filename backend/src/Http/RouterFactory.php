<?php

declare(strict_types=1);

namespace App\Http;

use App\Database\Database;
use App\Controllers\CustomersController;
use App\Controllers\AuthController;
use App\Routes\CustomersRoutes;
use App\Routes\AuthRoutes;
use App\Repositories\AddressRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\UserRepository;
use App\Exceptions\NotFoundException;
use App\Routes\RoutesInterface;
use App\Services\CustomerService;
use App\Services\UserService;

class RouterFactory
{

    public static function create(string $route): RoutesInterface
    {
        if ($route === 'customers') {
            return self::createCustomersRoutesInstance();
        }

        if ($route === 'auth') {
            return self::createAuthRoutesInstance();
        }

        throw new NotFoundException();
    }

    private static function createAuthRoutesInstance(): AuthRoutes
    {
        $database = new Database();
        $repository = new UserRepository($database);
        $service = new UserService($repository);
        $controller = new AuthController($service);
        return new AuthRoutes($controller);
    }

    private static function createCustomersRoutesInstance(): CustomersRoutes
    {
        $database = new Database();
        $addressRepository = new AddressRepository($database);
        $customerRepository = new CustomerRepository($database);
        $service = new CustomerService($customerRepository, $addressRepository);
        $controller = new CustomersController($service);
        return new CustomersRoutes($controller);
    }
}
