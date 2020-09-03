<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
// use Tests\TestCase;
use App\user;
use PHPUnit\Framework\TestCase;

class AuthTest extends BaseUnitTest
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
        $this->assertTrue(true);
    }


    /**
     * Test "Me", current user details.
     *
     * @return void
     */
    public function testMe()
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
        // var_dump($this->token);
        // $this->assertAuthenticated('api');
        return $this;
    }

}
