<?php

namespace App\Services;

use App\Exceptions\AgentBusyException;
use App\Exceptions\CustomerBusyException;
use App\Models\Agent;
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
    public function createAppointment(Agent $agent, $customerEmail, $address, $date, $time)
    {
        $validate = Validator::make(
            [
                'customer_email' => $customerEmail,
                'address' => $address,
                'date' => $date,
                'time' => $time
            ],
            [
                'customer_email' => 'required|string|email',
                'address' => 'required|string|postal_code:GB',
                'date' => 'required|date',
                'time' => 'required|date_format:H:i'
            ],
            ['postal_code' => 'The :attribute must be a valid UK postal code.']
        );

        if ($validate->fails()) {
            throw new Exception($validate->errors());
        }

        $customer = $this->customerService->getCustomerByEmail($customerEmail);

        $date_begin = Carbon::parse($date . ' ' . $time);
        $date_end = Carbon::parse($date . ' ' . $time)->addHour();

        $existing = $this->appointmentRepository->checkAppointmentAgentConflictCount($agent->id, $date_begin, $date_end);
        if ($existing) {
            throw new AgentBusyException('Agent is busy at this time', 400);
        }

        $existing = $this->appointmentRepository->checkAppointmentCustomerConflictCount($customer->id, $date_begin, $date_end);
        if ($existing) {
            throw new CustomerBusyException('Customer is busy at this time', 400);
        }

        $this->appointmentRepository->createAppointment($customer, $agent, $address, $date_begin, $date_end);
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


}