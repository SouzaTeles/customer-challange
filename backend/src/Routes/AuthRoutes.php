<?php

declare(strict_types=1);

namespace App\Routes;

use App\Controllers\AuthController;
use App\Exceptions\NotAllowedException;
use App\Exceptions\NotFoundException;
use App\Http\HttpMethod;
use App\Http\Request;
use App\Http\Response;

class AuthRoutes implements RoutesInterface
{
    private AuthController $controller;

    public function __construct(AuthController $controller)
    {
        $this->controller = $controller;
    }

    public function handle(Request $request): ?Response
    {
        $action = $request->popSegments();
        $method = $request->getMethod();

        switch ($action) {
            case 'login':
                if ($method !== HttpMethod::POST)  {
                    throw new NotAllowedException();
                }
                return $this->controller->login($request->getBodyContent());
            case 'logout':
                if ($method !== HttpMethod::POST)  {
                    throw new NotAllowedException();
                }
                return $this->controller->logout();
            default:
                throw new NotFoundException();
        }
    }
}
