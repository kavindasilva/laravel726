<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
// use Tests\TestCase;
use App\user;
use PHPUnit\Framework\TestCase;

class BaseUnitTest extends TestCase
{
    protected $token = null;
    protected $email = "k@admin.com";
    protected $password = "k";

    /**
     * A basic test example.
     *
     * @return void
     */
    protected function testBasicTest()
    {
        $this->assertTrue(true);
    }

    /**
     * Refresh token.
     *
     * @return void
     */
    public function testRefreshToken()
    {
        $this->old_token = $this->getTokenForUser($this->adminUser());
        $response = $this->get('api/refresh', [
            "Authorization" => "Bearer ".($this->old_token),
            "Accept" => "application/json"
        ]);

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in'])
            ->assertJsonPath('token_type', 'bearer')
            ->assertDontSeeText($this->old_token);
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
