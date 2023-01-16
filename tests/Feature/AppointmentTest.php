<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatingNewAppointmnet()
    {

        $response = $this->post('/api/v1/register', ['name' => 'John Doe', 'email' => 'John@doe.com', 'password' => 'password']);
        $this->post('/api/v1/customer', ['name' => 'John', 'email' => 'John@doe.com', 'surname' => 'Doee', 'phone' => '123456789', 'address' => 'address']);
        $loginToken = (json_decode($response->getContent())->data->token);
        $response = $this->post('/api/v1/appointment', ['customer_email' => 'John@doe.com', 'address' => 'CM11 1LD', 'date' => '2023/1/21', 'time' => '19:00']);

        $response->assertStatus(200);
    }

}
