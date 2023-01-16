<?php

namespace App\Http\Controllers;

use App\Services\CustomerService;
use App\Services\Interfaces\CustomerServiceInterface;
use Illuminate\Http\Request;


class CustomerController extends Controller
{

    private CustomerService $customerService;

    public function __construct(CustomerServiceInterface $customerService)
    {
        $this->customerService = $customerService;
    }

    public function createCustomer(Request $request)
    {
        try {
            $newCustomer = $this->customerService->createCustomer($request->name, $request->surname, $request->email, $request->phone, $request->address);
            return $this->sendResponse($newCustomer, 'New Customer created',200);
        } catch (\Exception $e) {
            return $this->sendError(null,json_decode($e->getMessage()), $e->getCode());
        }
    }

    public function getAllCustomers()
    {
        try {
            $customers = $this->customerService->getCustomers();
            return $this->sendResponse($customers, 200);
        } catch (\Exception $e) {
            return $this->sendError(json_decode($e->getMessage()), $e->getCode());
        }
    }

    public function getCustomerAppointmentsByEmail(Request $request)
    {
        try {
            $customer = $this->customerService->getCustomerAppointmentsByEmail($request->email);
            return $this->sendResponse($customer, 200);
        } catch (\Exception $e) {
            return $this->sendError(json_decode($e->getMessage()), $e->getCode());
        }
    }




}