<?php

// namespace Tests\Unit;
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\user;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

class gqlitemTest extends TestCase
{
    use MakesGraphQLRequests;
    
    protected $token = null;
    protected $email = "k@admin.com";
    protected $password = "k";

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExistingUserTest()
    {
        $response = $this->graphQL(/** @lang GraphQL */ '
            {
                user(id:8) {
                    id
                    name
                    email
                }
            }
        ');
        $response->assertStatus(200)
            // ->assertJsonPath('data', 'invalid_credentials')
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        'id',
                        'email',
                        'name',
                    ]
                ]
            ])
        ;
        // $response
        // var_dump($response);
    }

    public function testNonExistingUserTest()
    {
        $response = $this->graphQL(/** @lang GraphQL */ '
            {
                user(id:857) {
                    id
                    name
                    email
                }
            }
        ');
        $response->assertStatus(200)
            // ->assertJsonPath('data', 'invalid_credentials')
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        
                    ]
                ]
            ])
        ;
        // $response
        // var_dump($response);
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
