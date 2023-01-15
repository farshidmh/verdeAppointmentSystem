<?php

namespace App\Repositories\Interfaces;

interface CustomerRepositoryInterface
{
    public function createCustomer($name, $surname, $email, $phone, $address);

    public function getCustomers();

    public function getCustomerAppointmentsByEmail($email);
}