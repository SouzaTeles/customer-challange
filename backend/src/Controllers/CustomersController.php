<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Response;
use App\Services\CustomerService;

final class CustomersController
{
    private CustomerService $service;

    public function __construct(CustomerService $service)
    {
        $this->service = $service;
    }

    public function get(): Response
    {
        $customers = $this->service->getAll();
        return Response::json($customers);
    }

    public function save(array $data): Response
    {
        $created = $this->service->create($data);
        return Response::created($created->toArray());
    }

    public function getById(int $id): Response
    {
        $found = $this->service->findById($id);

        if ($found === null) {
            throw new NotFoundException();
        }

        return Response::json($found->toArray());
    }

    public function update(int $id, array $data): Response
    {
        $updated = $this->service->update($id, $data);

        if ($updated === null) {
            throw new NotFoundException();
        }

        return Response::json($updated->toArray());
    }

    public function delete(int $id): Response
    {
        $deleted = $this->service->delete($id);

        if (!$deleted) {
            throw new NotFoundException();
        }

        return Response::noContent();
    }
}