<?php

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
    protected $existing_user_id = 8;
    public $created_item_id = null;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExistingUserTest()
    {
        $response = $this->graphQL(/** @lang GraphQL */ '
            {
                user(id:'.$this->existing_user_id.') {
                    id
                    name
                    email
                }
            }
        ');
        var_dump($response->content());
        $response->assertStatus(200)
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
            ->assertJsonFragment(['id' => "$this->existing_user_id"]);
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
        var_dump($response->content());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user'
                ]
            ])
            ->assertJsonFragment(['user' => null])
        ;
    }

}
