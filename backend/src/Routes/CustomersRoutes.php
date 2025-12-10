<?php

declare(strict_types=1);

namespace App\Routes;

use App\Controllers\CustomersController;
use App\Exceptions\NotFoundException;
use App\Http\HttpMethod;

class CustomersRoutes
{
    public function handleCustomersRoute(array $request): void
    {
        $controller = new CustomersController();

        $id = $segments[1] ?? null;

        switch ($request['method']) {
            case HttpMethod::GET:
                if ($id) {
                    return $controller->getById($id);
                }
                return $controller->get();
            case HttpMethod::POST:
                if ($id) {
                    throw new NotFoundException();
                }
                return $controller->save();
            case HttpMethod::PATCH:
                if (!$id) {
                    throw new NotFoundException();
                }
                return $controller->update($id);
            case HttpMethod::DELETE:
                if (!$id) {
                    throw new NotFoundException();
                }
                return $controller->delete($id);
            default:
                http_response_code(405);
                header('Allow: GET, POST, PATCH, DELETE');
        }
    }
}