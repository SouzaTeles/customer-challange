<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Customer;
use App\Repositories\AddressRepository;
use App\Repositories\CustomerRepository;

class CustomerService
{
    private CustomerRepository $customerRepository;
    private AddressRepository $addressRepository;

    public function __construct(
        CustomerRepository $customerRepository,
        AddressRepository $addressRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->addressRepository = $addressRepository;
    }

    public function getAll(): array
    {
        return $this->customerRepository->findAll();
    }

    public function create(array $data): Customer
    {
        $customer = Customer::fromArray($data);
        $createdCustomer = $this->customerRepository->create($customer);
        
        $customerId = (int)$createdCustomer->getId();

        foreach ($customer->getAddresses() as $address) {
            $address->setCustomerId($customerId);
            $this->addressRepository->create($address);
        }
        
        $createdCustomer->setAddresses($customer->getAddresses());

        return $createdCustomer;
    }

    public function findById(int $id): ?Customer
    {
        $customer = $this->customerRepository->findById($id);

        if ($customer) {
            $addresses = $this->addressRepository->findByCustomerId((int)$id);
            $customer->setAddresses($addresses);
        }

        return $customer;
    }

    public function update(int $id, array $data): ?Customer
    {
        $existing = $this->customerRepository->findById($id);
        if (!$existing) {
            return null;
        }
        
        $customer = Customer::fromArray($data)->setId($id);
        
        $updatedCustomer = $this->customerRepository->update($customer);

        $customerId = (int) $id;
        $this->addressRepository->deleteByCustomerId($customerId);

        $newAddresses = [];
        foreach ($customer->getAddresses() as $address) {
            $address->setCustomerId($customerId);
            $newAddresses[] = $this->addressRepository->create($address);
        }

        $updatedCustomer->setAddresses($newAddresses);

        return $updatedCustomer;
    }

    public function delete(int $id): bool
    {
        return $this->customerRepository->delete($id);
    }
}
