<?php

namespace App\Repositories;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Agent;
use App\Repositories\Interfaces\AppointmentRepositoryInterface;
use Exception;

class AppointmentRepository implements AppointmentRepositoryInterface
{

    public function checkAppointmentAgentConflictCount($agentId, $date_begin, $date_end): int
    {
        $existing = Appointment::where(
            function ($query) use ($date_begin, $date_end) {
                $query->whereBetween('datetime_begin', [$date_begin, $date_end])
                    ->orWhereBetween('datetime_end', [$date_begin, $date_end])
                    ->orWhere(function ($query) use ($date_begin, $date_end) {
                        $query->where('datetime_begin', '<=', $date_begin)
                            ->where('datetime_end', '>=', $date_end);
                    });
            }
        )->where('agent_id', $agentId)
            ->count();

        return $existing;
    }

    public function checkAppointmentCustomerConflictCount($customerId, $date_begin, $date_end): int
    {
        $existing = Appointment::where(
            function ($query) use ($date_begin, $date_end) {
                $query->whereBetween('datetime_begin', [$date_begin, $date_end])
                    ->orWhereBetween('datetime_end', [$date_begin, $date_end])
                    ->orWhere(function ($query) use ($date_begin, $date_end) {
                        $query->where('datetime_begin', '<=', $date_begin)
                            ->where('datetime_end', '>=', $date_end);
                    });
            }
        )->where('customer_id', $customerId)
            ->count();

        return $existing;
    }

    /**
     * @throws Exception
     */
    public function deleteAppointment($id)
    {
        $apt = Appointment::find($id);
        if ($apt) {
            $apt->delete();
        } else {
            throw new Exception('Appointment not found');
        }
    }

    public function getAppointment($id)
    {
        // TODO: Implement getAppointment() method.
    }

    public function getAppointments()
    {
        // TODO: Implement getAppointments() method.
    }

    public function createAppointment(Customer $customer, Agent $agent, $address, $date_begin, $date_end)
    {
        $agent->appointments()->create([
            'customer_id' => $customer->id,
            'address' => $address,
            'datetime_begin' => $date_begin,
            'datetime_end' => $date_end,
            'distance' => 0
        ]);
    }

    public function updateAppointment($id, Customer $customer, Agent $agentId, $address, $date, $time, $status)
    {
        // TODO: Implement updateAppointment() method.
    }
}