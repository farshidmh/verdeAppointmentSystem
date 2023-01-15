<?php

namespace App\Services\Interfaces;

interface CustomerServiceInterface
{
    public function createCustomer($name, $surname, $email, $phone, $address);

    public function getCustomers();

    public function getCustomerAppointmentsByEmail($email);
}