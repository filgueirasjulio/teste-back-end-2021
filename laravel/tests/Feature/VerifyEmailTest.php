<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VerifyEmailTest extends TestCase
{
    use RefreshDatabase;

    public function testInvalidConfirmationToken()
    {
        $this->postJson('api/v1/auth/register', [
            'name' => 'Júlio',
            'email' => 'teste@mail.com',
            'password' => 'Password1',
        ]);

        $response = $this->postJson('api/v1/auth/verify-email', [
            'token' => 'tokeninválido'
        ]);
       
        $response->assertStatus(400)
            ->assertJson([
                "error" => "VerifyEmailTokenInvalidException",
                "message" => "Token not valid.",
            ]);
    }

    public function testVerifyEmailSucessful()
    {
        $this->postJson('api/v1/auth/register', [
            'name' => 'Júlio',
            'email' => 'teste@mail.com',
            'password' => 'Password1',
        ]);

        $user = User::get()->first();

        $response = $this->postJson('api/v1/auth/verify-email', [
            'token' => $user->confirmation_token
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure([
            "data" => [
                'id',
                'name',
                'email',
                'created_at',
            ]
        ]);
    }
}