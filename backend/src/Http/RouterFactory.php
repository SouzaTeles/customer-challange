<?php

declare(strict_types=1);

namespace App\Http;

use App\Database\Database;
use App\Controllers\CustomersController;
use App\Routes\CustomersRoutes;
use App\Repositories\CustomerRepository;
use App\Exceptions\NotFoundException;
use App\Routes\RoutesInterface;

class RouterFactory
{

    public static function create(string $route): RoutesInterface
    {
        if ($route === 'customers') {
            $database = new Database();
            $repository = new CustomerRepository($database);
            $controller = new CustomersController($repository);

            return new CustomersRoutes($controller);
        }

        throw new NotFoundException();
    }
}
