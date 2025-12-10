<?php

declare(strict_types=1);

namespace App\Routes;

use App\Controllers\CustomersController;
use App\Exceptions\NotFoundException;
use App\Http\HttpMethod;
use App\Http\Request;

class CustomersRoutes implements RoutesInterface
{
    public function handle(Request $request): ?array
    {
        $controller = new CustomersController();

        $id = $request->popSegments();

        switch ($request->getMethod()) {
            case HttpMethod::GET:
                if ($id) {
                    return $controller->getById($id);
                }
                return $controller->get();
            case HttpMethod::POST:
                if ($id) {
                    throw new NotFoundException();
                }
                return $controller->save($request->getBodyContent());
            case HttpMethod::PATCH:
                if (!$id) {
                    throw new NotFoundException();
                }
                return $controller->update($id, $request->getBodyContent());
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