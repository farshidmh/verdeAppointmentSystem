<?php

namespace App\Services;

use App\Models\Customer;
use App\Repositories\CustomerRepository;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Services\Interfaces\CustomerServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;

class CustomerService implements CustomerServiceInterface
{

    private CustomerRepository $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Create a new customer
     * @param $name
     * @param $surname
     * @param $email
     * @param $phone
     * @param $address
     * @return \App\Models\Customer
     * @throws Exception
     */
    public function createCustomer($name, $surname, $email, $phone, $address): Customer
    {
        $validator = Validator::make(
            ['name' => $name, 'surname' => $surname, 'email' => $email, 'phone' => $phone, 'address' => $address],
            ['name' => 'required|string', 'surname' => 'required|string', 'email' => 'required|string|email|unique:customers', 'phone' => 'required|string', 'address' => 'required|string']
        );

        if ($validator->fails()) {
            throw new Exception($validator->errors(), 400);
        }

        return $this->customerRepository->createCustomer($name, $surname, $email, $phone, $address);
    }

    /**
     * Get all customers
     * @return Collection
     */
    public function getCustomers(): Collection
    {
        return $this->customerRepository->getCustomers();
    }

    /**
     * Get Customer by Eamil
     * @throws Exception
     */
    public function getCustomerByEmail($email): Customer
    {
        $validator = Validator::make(
            ['email' => $email],
            ['email' => 'required|string|email|exists:customers'],
            ['exists' => 'Customer with this email does not exist']
        );

        if ($validator->fails()) {
            throw new Exception($validator->errors(), 400);
        }
        return $this->customerRepository->getCustomerByEmail($email);
    }

    /**
     * Get all customers appointments
     * @param $id
     * @return void
     */
    public function getCustomerAppointmentsByEmail($email)
    {
        return $this->customerRepository->getCustomerAppointmentsByEmail($email);
    }

}