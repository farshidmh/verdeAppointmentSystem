<?php

namespace App\Services;

use App\Models\Agent;
use App\Repositories\AgentRepository;
use App\Repositories\Interfaces\AgentRepositoryInterface;
use App\Services\Interfaces\AgentServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AgentService implements AgentServiceInterface
{
    private AgentRepository $agentRepository;

    public function __construct(AgentRepositoryInterface $agentRepository)
    {
        $this->agentRepository = $agentRepository;
    }

    /**
     * Login Agent and return token and agent  if successful login attempt is made
     * @throws Exception
     */
    public function login($email, $password): array
    {
        $validator = Validator::make(
            ['email' => $email, 'password' => $password],
            ['email' => 'required|string|email', 'password' => 'required|string|min:6']
        );

        if ($validator->fails()) {
            throw new Exception($validator->errors(), 400);
        }

        $token = $this->agentRepository->login($email, $password);
        if (!$token) {
            throw new Exception('User Not Found', 404);
        }

        $user = Auth::user();
        return ['user' => $user, 'token' => $token, 'token_type' => 'bearer', 'expires_in' => Auth::factory()->getTTL() * 60];

    }

    /**
     * Register a new Agent and return Agent object and login token if successful registration attempt is made
     * @throws Exception
     */
    public function register($name, $email, $password): array
    {
        $validator = Validator::make(
            ['name' => $name, 'email' => $email, 'password' => $password],
            ['name' => 'required|string', 'email' => 'required|string|email|unique:agents|min:6', 'password' => 'required|string|min:6']
        );

        if ($validator->fails()) {
            throw new Exception($validator->errors(), 401);
        }

        $this->agentRepository->register($name, $email, $password);

        return $this->login($email, $password);
    }

    public function logout(): bool
    {
        return $this->agentRepository->logout();
    }

    public function getAgent(): Agent
    {
        return $this->agentRepository->getAgent();
    }

    public function refreshToken(): array
    {
        return $this->agentRepository->refreshToken();
    }


}
