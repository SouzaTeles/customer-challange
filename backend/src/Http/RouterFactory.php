<?php

declare(strict_types=1);

namespace App\Http;

use App\Routes\CustomersRoutes;
use App\Exceptions\NotFoundException;
use App\Routes\RoutesInterface;

class RouterFactory
{

    public static function create(string $route): RoutesInterface

    {
        $routes = [
            'customers' => CustomersRoutes::class,
        ];

        if (!isset($routes[$route])) {
            throw new NotFoundException();
        }

        return new $routes[$route]();
    }
}
