<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Response;
use App\Models\Customer;
use App\Repositories\CustomerRepository;

final class CustomersController
{
    private CustomerRepository $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function get(): Response
    {
        return Response::json($this->repository->findAll());
    }

    public function save(array $data): Response
    {
        $customer = Customer::fromArray($data);

        $created = $this->repository->create($customer);

        return Response::json($created->toArray(), 201);
    }

    public function getById(string $id): Response
    {
        $found = $this->repository->findById($id);

        if ($found === false || $found === null) {
            throw new NotFoundException();
        }

        return Response::json($found);
    }

    public function update(string $id, array $data): Response
    {
        $customer = Customer::fromArrayToUpdate($data)->setId($id);
        $updated = $this->repository->update($customer);

        if ($updated === null) {
            throw new NotFoundException();
        }

        return Response::json($updated);
    }

    public function delete(string $id): Response
    {
        $deleted = $this->repository->delete($id);

        if (!$deleted) {
            throw new NotFoundException();
        }

        return Response::noContent();
    }
}