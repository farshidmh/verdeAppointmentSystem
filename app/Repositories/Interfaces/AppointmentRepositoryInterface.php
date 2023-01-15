<?php

namespace App\Repositories\Interfaces;

use App\Models\Customer;
use App\Models\Agent;

interface AppointmentRepositoryInterface
{
    public function createAppointment(Customer $customer, Agent $agent, $address, $date, $time);

    public function updateAppointment($id, Customer $customer, Agent $agentId, $address, $date, $time, $status);

    public function deleteAppointment($id);

    public function getAppointment($id);

    public function getAppointments();
}