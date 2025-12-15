<?php

declare(strict_types=1);

namespace App\Routes;

use App\Controllers\CustomersController;
use App\Exceptions\NotAllowedException;
use App\Exceptions\NotFoundException;
use App\Http\AuthMiddleware;
use App\Http\HttpMethod;
use App\Http\Request;
use App\Http\Response;

class CustomersRoutes implements RoutesInterface
{
    private CustomersController $controller;

    public function __construct(CustomersController $controller)
    {
        $this->controller = $controller;
    }

    private function getNumericId(?string $segment): int
    {
        if (ctype_digit($segment)) {
            return (int) $segment;
        }

        throw new NotFoundException();
    }

    public function handle(Request $request): ?Response
    {
        AuthMiddleware::requireLogin();

        $segment = $request->popSegments();

        switch ($request->getMethod()) {
            case HttpMethod::GET:
                if ($segment) {
                    $id = $this->getNumericId($segment);
                    return $this->controller->getById($id);
                }
                return $this->controller->get();
            case HttpMethod::POST:
                if ($segment) {
                    throw new NotFoundException();
                }
                return $this->controller->save($request->getBodyContent());
            case HttpMethod::PUT:
                $id = $this->getNumericId($segment);
                return $this->controller->update($id, $request->getBodyContent());
            case HttpMethod::DELETE:
                $id = $this->getNumericId($segment);
                return $this->controller->delete($id);
            default:
                throw new NotAllowedException();
        }
    }
}