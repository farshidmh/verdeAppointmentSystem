<?php

namespace App\Repositories\Interfaces;

use App\Models\Customer;
use App\Models\Agent;

interface AppointmentRepositoryInterface
{
    public function createOrUpdateAppointment(Customer $customer, Agent $agent, $address, $date, $time,$id);

    public function deleteAppointment($id);

    public function getAppointment($id);

    public function getAppointments();
}