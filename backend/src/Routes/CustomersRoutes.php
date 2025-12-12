<?php

declare(strict_types=1);

namespace App\Routes;

use App\Controllers\CustomersController;
use App\Exceptions\NotAllowedException;
use App\Exceptions\NotFoundException;
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

    public function handle(Request $request): ?Response
    {
        $id = $request->popSegments();

        switch ($request->getMethod()) {
            case HttpMethod::GET:
                if ($id) {
                    return $this->controller->getById($id);
                }
                return $this->controller->get();
            case HttpMethod::POST:
                if ($id) {
                    throw new NotFoundException();
                }
                return $this->controller->save($request->getBodyContent());
            case HttpMethod::PATCH:
                if (!$id) {
                    throw new NotFoundException();
                }
                return $this->controller->update($id, $request->getBodyContent());
            case HttpMethod::DELETE:
                if (!$id) {
                    throw new NotFoundException();
                }
                return $this->controller->delete($id);
            default:
                throw new NotAllowedException();
        }
    }
}