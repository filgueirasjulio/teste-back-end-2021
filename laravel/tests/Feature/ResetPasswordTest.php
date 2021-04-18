<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function testTokenInvalid()
    {
        $this->postJson('api/v1/auth/register', [
            'name' => 'Júlio',
            'email' => 'teste@mail.com',
            'password' => 'Password1',
        ]);

        $response = $this->postJson('api/v1/auth/reset-password', [
            'email' => 'teste@mail.com',
            'password' => 'Password123',
            'token' => 'tokeninválido'
        ]);
            
        $response->assertStatus(401)
                ->assertJson([
                    "error" => "ResetPasswordTokenInvalidException",
                    "message" => "Reset password token not valid.",
                ]);
    }

    public function testResetPasswordSuccessful()
    {
        $this->postJson('api/v1/auth/register', [
            'name' => 'Júlio',
            'email' => 'teste@mail.com',
            'password' => 'Password1',
        ]);

        $this->postJson('api/v1/auth/forgot-password', [
            'email' => 'teste@mail.com'
        ]);

        $passReset = PasswordReset::get()->first();    
    
        $response = $this->postJson('api/v1/auth/reset-password', [
            'email' => 'teste@mail.com',
            'password' => 'Password123',
            'token' => $passReset->token,
        ]);

        $response->assertStatus(200);
    }

    public function testLoginAfterResetPasswordSuccessful()
    {
        $user = User::factory()->create([
            'email' => 'teste@mail.com',
            'password' => bcrypt('password'),
        ]);

        $this->postJson('api/v1/auth/forgot-password', [
            'email' => 'teste@mail.com'
        ]);

        $passReset = PasswordReset::get()->first();    
    
        $this->postJson('api/v1/auth/reset-password', [
            'email' => 'teste@mail.com',
            'password' => 'Password123',
            'token' => $passReset->token,
        ]);

        $response = $this->postJson('api/v1/auth/login', [
            'email' => 'teste@mail.com',
            'password' => 'Password123',
        ]);

        $response->assertJsonPath('status', 200)
        ->assertJsonPath('success', true);

        $this->assertAuthenticatedAs($user);
    }
}