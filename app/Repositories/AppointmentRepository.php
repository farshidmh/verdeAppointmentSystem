<?php

namespace App\Repositories;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Agent;
use App\Repositories\Interfaces\AppointmentRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Builder;

class AppointmentRepository implements AppointmentRepositoryInterface
{

    private function checkAppointmentConflictCount($date_begin, $date_end): Builder
    {
        return Appointment::where(
            function ($query) use ($date_begin, $date_end) {
                $query->whereBetween('datetime_begin', [$date_begin, $date_end])
                    ->orWhereBetween('datetime_end', [$date_begin, $date_end])
                    ->orWhere(function ($query) use ($date_begin, $date_end) {
                        $query->where('datetime_begin', '<=', $date_begin)
                            ->where('datetime_end', '>=', $date_end);
                    });
            }
        );
    }

    public function checkAppointmentAgentConflictCount($agentId, $date_begin, $date_end, $appointmentId = null): int
    {
        $build = $this->checkAppointmentConflictCount($date_begin, $date_end)
            ->where('agent_id', $agentId);

        if ($appointmentId) {
            $build->where('id', '!=', $appointmentId);
        }

        return $build->count();
    }

    public function checkAppointmentCustomerConflictCount($customerId, $date_begin, $date_end, $appointmentId = null): int
    {
        $build = $this
            ->checkAppointmentConflictCount($date_begin, $date_end)
            ->where('customer_id', $customerId);

        if ($appointmentId) {
            $build->where('id', '!=', $appointmentId);
        }

        return $build->count();
    }


    /**
     * @throws Exception
     */
    public function deleteAppointment($id)
    {
        Appointment::find($id)->delete();
    }


    public function getAppointmentsByAgentID($agentId)
    {
        return Appointment::where('agent_id',$agentId)->get()->sortBy('datetime_begin');
    }

    public function createOrUpdateAppointment(Customer $customer, Agent $agent, $address, $date_begin, $date_end, $id, $distance, $timeToLeave): Appointment
    {
        return $agent->appointments()->updateOrCreate(
            ['id' => $id],
            [
                'customer_id' => $customer->id,
                'address' => $address,
                'datetime_begin' => $date_begin,
                'datetime_end' => $date_end,
                'distance' => $distance,
                'datetime_to_leave' => $timeToLeave,
            ]);
    }

    public function getAppointmentByDate($beginDate, $endDate)
    {
        return Appointment::whereBetween('datetime_begin', [$beginDate, $endDate])
            ->orWhereBetween('datetime_end', [$beginDate, $endDate])
            ->orWhere(function ($query) use ($beginDate, $endDate) {
                $query->where('datetime_begin', '<=', $beginDate)
                    ->where('datetime_end', '>=', $endDate);
            })
            ->get()->sortBy('datetime_begin');
    }


}