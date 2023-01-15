<?php

namespace App\Repositories\Interfaces;

interface AgentRepositoryInterface
{
    public function login($email, $password);

    public function register($name, $email, $password);

    public function logout();

    public function getAgent();

    public function refreshToken();

}