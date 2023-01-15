<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class CustomerRepository implements CustomerRepositoryInterface
{

    /**
     * Create a new customer
     * @param $name
     * @param $surname
     * @param $email
     * @param $phone
     * @param $address
     * @return void
     * @throws Exception
     */
    public function createCustomer($name, $surname, $email, $phone, $address): Customer
    {
        return Customer::create(['name' => $name, 'surname' => $surname, 'email' => $email, 'phone' => $phone, 'address' => $address]);
    }

    /**
     * Get list of all customers
     * @return Collection
     */
    public function getCustomers(): Collection
    {
        return Customer::all();
    }

    public function getCustomerAppointmentsByEmail($email)
    {
        //return Customer::where('email', $email)->with('appointments')->first();
        return Customer::where('email', $email)->first();
    }
}