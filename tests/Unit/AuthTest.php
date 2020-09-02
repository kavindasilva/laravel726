<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
// use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    protected $token = null;
    protected $email = "k@admin.com";
    protected $password = "k";

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
            "email" => $this->email,
            "password" => $this->password
        ];
        $response = $this->postJson('api/login', $payload);
        // var_dump($response);

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in'])
            ->assertJsonPath('token_type', 'bearer');

        $this->token = $response["access_token"];
        var_dump($this->token);
        // $this->assertAuthenticated('api');
    }


    /**
     * Test current logged user data.
     *
     * @return void
     */
    public function testLoggedUser()
    {
        $payload = [
            "email" => $this->email,
            "password" => $this->password
        ];
        $response = $this->get('api/me', [
            // $this->getAuthHeader()
            "Authorization" => "Bearer ".($this->token),
            "Accept" => "application/json"
        ]);
        var_dump($response);

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json');
            // ->assertJsonStructure(['access_token', 'token_type', 'expires_in'])
            // ->assertJsonPath('token_type', 'bearer');

        // $this->token = $response["access_token"];
        // $this->assertAuthenticated('api');
    }


    protected function getAuthHeader(){
        return (object)[
            "Authorization" => "Bearer ".'$this->token'
        ];
    }

}
