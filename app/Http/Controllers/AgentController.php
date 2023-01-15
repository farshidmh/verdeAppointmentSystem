<?php

namespace App\Http\Controllers;

use App\Services\AgentService;
use App\Services\Interfaces\AgentServiceInterface;
use Illuminate\Http\Request;

class AgentController extends Controller
{

    private AgentService $agentService;

    public function __construct(AgentServiceInterface $agentService)
    {
        $this->agentService = $agentService;
    }


    public function login(Request $request)
    {
        try {
            $loginAttempt = $this->agentService->login($request->email, $request->password);
            return $this->sendResponse($loginAttempt, 'User logged in successfully.');
        } catch (\Exception $e) {
            return $this->sendError(json_decode($e->getMessage()), 'login_error', $e->getCode());
        }
    }

    public function register(Request $request)
    {
        try {
            $registerInfo = $this->agentService->register($request->name, $request->email, $request->password);
            return $this->sendResponse($registerInfo, 'Agent registered successfully.');
        } catch (\Exception $e) {
            return $this->sendError(json_decode($e->getMessage()), 'register_error', $e->getCode());
        }
    }

    public function logout()
    {
        $this->agentService->logout();
        return $this->sendResponse(null, 'User logged out successfully.');
    }

    public function getAgent()
    {
        return $this->sendResponse($this->agentService->getAgent(), 'User retrieved successfully.');
    }

    public function refresh()
    {
        return $this->sendResponse($this->agentService->refreshToken(), 'Token refreshed successfully.');
    }

}