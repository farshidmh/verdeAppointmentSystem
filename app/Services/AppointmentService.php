<?php

namespace App\Services;

use App\Exceptions\AgentBusyException;
use App\Exceptions\CustomerBusyException;
use App\Models\Agent;
use App\Models\Appointment;
use App\Repositories\AppointmentRepository;
use App\Repositories\Interfaces\AppointmentRepositoryInterface;
use App\Services\Interfaces\AppointmentServiceInterface;
use App\Services\Interfaces\CustomerServiceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator;

class AppointmentService implements AppointmentServiceInterface
{
    private AppointmentRepository $appointmentRepository;
    private CustomerService $customerService;

    public function __construct(AppointmentRepositoryInterface $appointmentRepository, CustomerServiceInterface $customerService)
    {
        $this->appointmentRepository = $appointmentRepository;
        $this->customerService = $customerService;
    }

    /**
     * create appointment
     * @throws AgentBusyException
     * @throws CustomerBusyException
     * @throws Exception
     */
    public function createOrUpdateAppointment(Agent $agent, $customerEmail, $address, $date, $time, $id = null): Appointment
    {
        $validate = Validator::make(
            [
                'customer_email' => $customerEmail,
                'address' => $address,
                'date' => $date,
                'time' => $time,
                'appointment_id' => $id
            ],
            [
                'customer_email' => 'required|string|email',
                'address' => 'required|string|postal_code:GB',
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
                'appointment_id' => 'nullable|integer|exists:appointments,id'
            ],
            ['postal_code' => 'The :attribute must be a valid UK postal code.']
        );

        if ($validate->fails()) {
            throw new Exception($validate->errors());
        }

        $customer = $this->customerService->getCustomerByEmail($customerEmail);

        $date_begin = Carbon::parse($date . ' ' . $time);
        $date_end = Carbon::parse($date . ' ' . $time)->addHour();

        $distance = null;
        $timeToLeave = null;

        try {
            $response = json_decode(\GoogleMaps::load('distancematrix')
                ->setParam(['origins' => config('app.default_agent_origin')])
                ->setParam(['destinations' => $address])
                ->get(), true);
            if ($response['status']) {
                $distance = $response['rows'][0]['elements'][0]['distance']['value'];
                $timeToLeave = $response['rows'][0]['elements'][0]['duration']['value'];
                $timeToLeave = $date_begin->subSeconds($timeToLeave);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        $existing = $this->appointmentRepository->checkAppointmentAgentConflictCount($agent->id, $date_begin, $date_end, $id);
        if ($existing) {
            throw new AgentBusyException('Agent is busy at this time', 400);
        }

        $existing = $this->appointmentRepository->checkAppointmentCustomerConflictCount($customer->id, $date_begin, $date_end, $id);
        if ($existing) {
            throw new CustomerBusyException('Customer is busy at this time', 400);
        }

        return $this->appointmentRepository->createOrUpdateAppointment($customer, $agent, $address, $date_begin, $date_end, $id, $distance, $timeToLeave);
    }

    /**
     * @throws Exception
     */
    public function deleteAppointment($id)
    {
        $validate = Validator::make(
            ['appointment_id' => $id],
            ['appointment_id' => 'required|integer|exists:appointments,id'],
            ['exists' => 'Appointment not found']
        );

        if ($validate->fails()) {
            throw new Exception($validate->errors());
        }

        $this->appointmentRepository->deleteAppointment($id);
    }


    /**
     * @throws Exception
     */
    public function getAppointmentByDate($beginDate, $endDate)
    {
        $validate = Validator::make(
            ['begin_date' => $beginDate, 'end_date' => $endDate],
            ['begin_date' => 'required|date', 'end_date' => 'nullable|date']
        );

        if ($validate->fails()) {
            throw new Exception($validate->errors());
        }

        if (is_null($endDate)) {
            $endDate = $beginDate;
        }
        $beginDate = Carbon::parse($beginDate)->setHour(0)->setMinute(0)->setSecond(0);
        $endDate = Carbon::parse($endDate)->setHour(23)->setMinute(59)->setSecond(59);
        return $this->appointmentRepository->getAppointmentByDate($beginDate, $endDate);
    }

    public function getAppointmentsByAgentID(Agent $agent)
    {
        return $this->appointmentRepository->getAppointmentsByAgentID($agent->id);
    }
}