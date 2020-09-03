<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\user;

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

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in'])
            ->assertJsonPath('token_type', 'bearer');

        $this->token = $response["access_token"];
        return $this;
    }


    /**
     * Test current logged user data.
     *
     * @return void
     */
    public function testLoggedUser()
    {
        $this->token = $this->getTokenForUser($this->adminUser());
        $response = $this->get('api/me', [
            "Authorization" => "Bearer ".($this->token),
            "Accept" => "application/json"
        ]);

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure(['id', 'name', 'email', 'created_at', 'created_at'])
            ->assertJsonPath('email', $this->email);
    }


    /**
     * credit for getting user jwt in unit testing: https://github.com/tymondesigns/jwt-auth/issues/1246#issuecomment-633380379
     */
    public function getTokenForUser(User $user) : string
    {
        return \JWTAuth::fromUser($user);
    }

    public function adminUser() : User
    {
        $user = \App\User::query()->firstWhere('email', $this->email);
        if ($user) {
            return $user;
        }
        // $user = \App\User::generate('Test Admin', 'test-admin@example.com', self::AUTH_PASSWORD);
        // $user->assignRole(Role::findByName('admin'));
        // return $user;
    }

}
