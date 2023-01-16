<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatingNewCustomer()
    {
        $response = $this->post('/api/v1/customer', ['name' => 'John', 'email' => 'John@doe.com', 'surname' => 'Doee', 'phone' => '123456789', 'address' => 'address']);
        $response->assertStatus(200);
    }

}
