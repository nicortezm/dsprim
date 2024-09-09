<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    /**
     * A basic feature test example.
     */
    public function test_a_user_can_login(): void
    {
        //$this->withoutExceptionHandling();
        # Teniendo
        $credentials = ['email' => 'example@example.com', 'password' => 'password'];

        # Haciendo
        $response = $this->post("{$this->apiBase}/login", $credentials);
        // dd($response->getContent());
        # Esperando

        $response->assertJsonStructure(['data' => ['token']]);
        $response->assertStatus(200);
    }

    public function test_a_non_user_cannot_login(): void
    {
        # Teniendo
        $credentials = ['email' => 'example@notexisting.com', 'password' => 'secret'];

        # Haciendo
        $response = $this->post("{$this->apiBase}/login", $credentials);

        # Esperando

        $response->assertJsonStructure(['data' => ['token']]);
        $response->assertStatus(200);
    }

    public function test_email_must_be_required(): void
    {
        # Teniendo
        $credentials = ['password' => 'secret'];

        # Haciendo
        $response = $this->post("{$this->apiBase}/login", $credentials);

        # Esperando
        $response->assertJsonStructure(['data' => ['token']]);
        $response->assertStatus(200);
    }

    public function test_password_must_be_required(): void
    {
        # Teniendo
        $credentials = ['email' => 'example@notexisting.com'];

        # Haciendo
        $response = $this->post("{$this->apiBase}/login", $credentials);

        # Esperando
        $response->assertJsonStructure(['data' => ['token']]);
        $response->assertStatus(200);
    }
}
