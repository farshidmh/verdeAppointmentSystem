<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgentTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatingNewUser()
    {
        $response = $this->post('/api/v1/register', ['name' => 'John Doe', 'email' => 'John@doe.com', 'password' => 'password']);
        $response->assertStatus(200);
    }

}