<?php

namespace App\Services\Interfaces;

use App\Models\Agent;

interface AppointmentServiceInterface
{
    public function createOrUpdateAppointment(Agent $agent, $customerEmail, $address, $date, $time, $id = null);

    public function deleteAppointment($id);

    public function getAppointmentByDate($beginDate,$endDate);

    public function getAppointmentsByAgentID(Agent $agent);

}