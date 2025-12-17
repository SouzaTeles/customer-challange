<?php

declare(strict_types=1);

namespace App\Http;

use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;
use DomainException;
use Exception;

final class Router
{
    public function handle(Request $request): void
    {
        try {
            $route = RouterFactory::create($request->popSegments());
            $response = $route->handle($request);
            $response->send();
        } catch (ValidationException $e) {
            Response::badRequest(
                $e->getMessage(),
                $e->getErrors()
            )->send();
        } catch (NotFoundException $e) {
            Response::notFound()->send();
        } catch (UnauthorizedException $e) {
            Response::unauthorized($e->getMessage())->send();
        } catch (DomainException $e) {
            Response::json(
                ['error' => $e->getMessage()],
                $e->getCode()
            )->send();
        } catch (Exception $e) {
            Response::serverError()->send();
        }
    }
}
