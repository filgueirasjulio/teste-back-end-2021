<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testInputsEmailPasswordEmptys()
    {
        $this->json('POST', 'api/v1/auth/login')
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    'email' => ["The email field is required."],
                    'password' => ["The password field is required."],
                ]
            ]);
    }

    public function testSuccessfulLogin()
    {
        $user = User::factory()->create([
            'email' => 'teste@mail.com',
            'password' => bcrypt('password'),
        ]);
        
        $response = $this->post('api/v1/auth/login', [
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
}
