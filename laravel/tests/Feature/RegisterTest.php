<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function testInputsEmptysRegister()
    {
        $this->postJson('api/v1/auth/register')
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    'email' => [ __('validation.required', ['attribute' => 'email']) ],
                    'email' => [ __('validation.required', ['attribute' => 'email']) ],
                    'password' => [ __('validation.required', ['attribute' => 'password']) ]
                ]
            ]);
    }

    public function testSuccessfulRegister()
    {

        $response = $this->postJson('api/v1/auth/register', [
            'name' => 'Júlio',
            'email' => 'teste@mail.com',
            'password' => 'Password1',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                "data" => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                ]
            ]);
    }

    public function testUserHasBeenTaken()
    {
        $this->postJson('api/v1/auth/register', [
            'name' => 'Júlio',
            'email' => 'teste@mail.com',
            'password' => 'Password1',
        ]);

        $response = $this->postJson('api/v1/auth/register', [
            'name' => 'João',
            'email' => 'teste@mail.com',
            'password' => 'Password2',
        ]);
    
        $response->assertStatus(422)
                ->assertJson([
                    "message" => "The given data was invalid.",
                    "errors" => [
                        'email' => [ __('validation.unique', ['attribute' => 'email']) ]
                    ]
                ]);
    }

    public function testePasswordInvalidFormat()
    {
        $response = $this->postJson('api/v1/auth/register', [
            'name' => 'Júlio',
            'email' => 'teste@mail.com',
            'password' => 'password1',
        ]);

        $response->assertStatus(422)
                ->assertJson([
                    "message" => "The given data was invalid.",
                    "errors" => [
                        'password' => [ __('validation.regex', ['attribute' => 'password']) ]
                    ]
                ]);
    }
}
