<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function testEmailNotFound()
    {
        $this->postJson('api/v1/auth/register', [
            'name' => 'Júlio',
            'email' => 'teste@mail.com',
            'password' => 'Password1',
        ]);

        $response = $this->postJson('api/v1/auth/forgot-password', [
            'email' => 'teste123@mail.com'
        ]);
            
        $response->assertStatus(404);
    }

    public function testForgotPasswordSussceful()
    {
        $this->postJson('api/v1/auth/register', [
            'name' => 'Júlio',
            'email' => 'teste@mail.com',
            'password' => 'Password1',
        ]);

        $response = $this->postJson('api/v1/auth/forgot-password', [
            'email' => 'teste@mail.com'
        ]);
            
        $response->assertStatus(200);
    }
}