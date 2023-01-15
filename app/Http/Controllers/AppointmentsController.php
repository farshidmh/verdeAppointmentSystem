<?php

namespace App\Http\Controllers;

use App\Exceptions\AgentBusyException;
use App\Exceptions\CustomerBusyException;
use App\Services\AppointmentService;
use App\Services\Interfaces\AppointmentServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentsController extends Controller
{
    private AppointmentService $appointmentService;

    public function __construct(AppointmentServiceInterface $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function createOrUpdateAppointment(Request $request)
    {
        try {
            $apt = $this->appointmentService->createOrUpdateAppointment(Auth::user(), $request->customer_email, $request->address, $request->date, $request->time, $request->id);
            if ($request->id) {
                return $this->sendResponse($apt, 'Appointment updated successfully.');
            }
            return $this->sendResponse($apt, 'Appointment created successfully.');

        } catch (AgentBusyException|CustomerBusyException $e) {
            return $this->sendError(null, $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendError(json_decode($e->getMessage()), null, 404);
        }
    }


    public function deleteAppointment(Request $request)
    {
        try {
            $this->appointmentService->deleteAppointment($request->id);
            return $this->sendResponse(NULL, 'Appointment deleted successfully.');
        } catch (\Exception $e) {
            return $this->sendError(null, json_decode($e->getMessage()), 404);
        }
    }

}