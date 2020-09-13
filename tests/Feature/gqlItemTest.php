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
        // var_dump($response->content());
        // var_dump( $this->jsonStringToArr($response->content()) );
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
            ->assertJsonFragment(['id' => "$this->existing_user_id"], $this->existing_user_id);
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


    public function testItemCrudTest()
    {
        $new_id = $this->testCreateItemTest();
        $this->testReadExistingItemTest($new_id );
        $this->testEditItemTest($new_id );
        $this->testReadExistingItemTest($new_id );
        $this->testDeleteItemTest($new_id);
        $this->testReadNonExistingItemTest($new_id );
    }

    protected function testReadExistingItemTest()
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
        // var_dump($response->content());
        // var_dump( $this->jsonStringToArr($response->content()) );
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
            ->assertJsonFragment(['id' => "$this->existing_user_id"], $this->existing_user_id);
    }

    protected function testReadNonExistingItemTest()
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
     {
        "data": {
            "createItem": {
                "id": "21",
                "name": "1",
                "batch": "1",
                "price": "11"
            }
        }
    }
     */
    protected function testCreateItemTest()
    {
        $response = $this->graphQL(/** @lang GraphQL */ '
            mutation{
                createItem(
                    name: "gql-unit-test",
                    batch: "unit-gql",
                    price: 122.5
                ){
                    id
                    name
                    batch
                    price
                }
            }
        ');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'deleteItem' => [
                        'id',
                        'name',
                        'batch',
                        'price',
                    ]
                ]
            ])
        ;
        // $response
        // var_dump($response);
        // $this->created_item_id = $this->jsonStringToArr($response->content())["data"]["createItem"]["id"];
        return $this->jsonStringToArr($response->content())["data"]["createItem"]["id"];
        // var_dump($this->created_item_id);
    }

    /**
    {
        "data": {
            "deleteItem": {
                "id": "21",
                "name": "1",
                "batch": "1",
                "price": "11"
            }
        }
    }
     */
    protected function testDeleteItemTest($ii)
    {
        var_dump("xz");
        var_dump($this->created_item_id);
        $response = $this->graphQL(/** @lang GraphQL */ '
            mutation{
                deleteItem(
                    id: '.$ii.'
                ){
                    id
                    name
                    batch
                    price
                }
            }
        ');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'deleteItem' => [
                        'id',
                        'name',
                        'batch',
                        'price',
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

    protected function jsonStringToArr($string){
        return json_decode($string, true);
    }

}
