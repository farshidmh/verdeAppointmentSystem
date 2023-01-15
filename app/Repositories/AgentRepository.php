<?php

namespace App\Repositories;

use App\Models\Agent;
use App\Repositories\Interfaces\AgentRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AgentRepository implements AgentRepositoryInterface
{

    /**
     * Login Agent and return token if successful login attempt is made
     * @param $email
     * @param $password
     * @return bool
     * @throws Exception
     */
    public function login($email, $password): string
    {
        return Auth::attempt(['email' => $email, 'password' => $password]);
    }

    /**
     * Register Agent and return Agent object if successful registration attempt is made
     * @param $name
     * @param $email
     * @param $password
     * @return array
     * @throws Exception
     */
    public function register($name, $email, $password): Agent
    {
        return Agent::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password)]);
    }

    /**
     * Logout Agent
     */
    public function logout(): bool
    {
        Auth::logout();
        return true;
    }

    /**
     * Get Agent model for the authenticated Agent
     * @return Agent
     */
    public function getAgent(): Agent
    {
        return Auth::user();
    }

    /**
     * Refresh token for the authenticated Agent
     * @return array
     */
    public function refreshToken(): array
    {
        return ['user' => Auth::user(), 'token' => Auth::refresh(), 'token_type' => 'bearer', 'expires_in' => Auth::factory()->getTTL() * 60];
    }
}

{

}