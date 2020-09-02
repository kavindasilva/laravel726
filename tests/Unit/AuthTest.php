<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
// use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    protected $token = null;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Test if user can login trough internal api.
     *
     * @return void
     */
    public function testLogin()
    {
        $payload = [
            "email" => "k@admin.com",
            "password" => "k"
        ];
        $response = $this->postJson('api/login', $payload);
        // var_dump($response);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in'])
            ->assertJsonPath('token_type', 'bearer');

        $this->token = $response["access_token"];

        // $this->assertAuthenticated('api');
    }

}
