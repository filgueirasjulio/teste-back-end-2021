<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $this->postJson('api/v1/auth/register', [
            'name' => 'Júlio',
            'email' => 'teste@mail.com',
            'password' => 'Password1',
        ]);

        $this->postJson('api/v1/auth/login', [
            'email' => 'teste@mail.com',
            'password' => 'Password1',
        ]);

        $response =  $this->json('get', 'api/v1/products/');

        $response->assertJsonPath('status', 200)
        ->assertJsonPath('success', true);

    }
}