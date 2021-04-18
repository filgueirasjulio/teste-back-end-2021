<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testInputsEmptysLogin()
    {
        $this->postJson('api/v1/auth/login')
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    'email' => [__('validation.required', ['attribute' => 'email'])],
                    'password' => [__('validation.required', ['attribute' => 'password'])]
                ]
            ]);
    }

    public function testSuccessfulLogin()
    {
        $user = User::factory()->create([
            'email' => 'teste@mail.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('api/v1/auth/login', [
            'email' => 'teste@mail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                ],
                "access_token",
                "token_type",
            ]);

        $this->assertAuthenticatedAs($user);
    }

    public function testEmaildInvalidFormat()
    {
        User::factory()->create([
            'email' => 'teste@mail.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('api/v1/auth/login', [
            'email' => 'teste.mail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    'email' => [__('validation.email', ['attribute' => 'email'])]
                ]
            ]);
    }

    public function testEmailOrPasswordIncorrect()
    {
        User::factory()->create([
            'email' => 'teste@mail.com',
            'password' => bcrypt('password'),
        ]);


        $response = $this->postJson('api/v1/auth/login', [
            'email' => 'teste2@mail.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                "error" => "LoginInvalidException",
                "message" => "Email and password don't match"
            ]);
    }
}
