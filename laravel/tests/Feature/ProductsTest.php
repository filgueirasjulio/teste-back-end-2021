<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexSuccessful()
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

    public function testCreateSuccessful()
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

        $response = $this->postJson('api/v1/products/create', [
            'name' => 'televisão',
            'weight' => 10,
            'price' => 200
        ]);

        $response->assertJsonPath('status', 200)
        ->assertJsonPath('success', true);
    }

    public function testUpdateSuccessful()
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

        $this->postJson('api/v1/products/create', [
            'name' => 'televisao',
            'weight' => 10,
            'price' => 200
        ]);

        $this->putJson('api/v1/products/edit/product/1', [
            'name' => 'televisao HD',
            'weight' => 10,
            'price' => 200
        ]);

        $response = $this->json('get', 'api/v1/products/show/product/1');

        $response->assertJsonPath('status', 200)
        ->assertJsonPath('success', true)
        ->assertJsonFragment(['name' => 'televisao HD']);
       
    }

    public function testShowSuccessful()
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

        $this->postJson('api/v1/products/create', [
            'name' => 'notebook',
            'weight' => 10,
            'price' => 200
        ]);

        $response = $this->json('get', 'api/v1/products/show/product/1');

        $response->assertJsonPath('status', 200)
        ->assertJsonPath('success', true)
        ->assertJsonFragment(['name' => 'notebook']); 
    }

    public function testDeleteSuccessful()
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

        $this->postJson('api/v1/products/create', [
            'name' => 'notebook',
            'weight' => 10,
            'price' => 200
        ]);

        $this->deleteJson('api/v1/products/delete/product/1');

        $response = $this->json('get', 'api/v1/products/show/product/1');

        $response->assertJsonPath('status', 500)
        ->assertJsonPath('success', false)
        ->assertJsonFragment(['message' => 'O produto não foi encontrado!']); 
    }
}