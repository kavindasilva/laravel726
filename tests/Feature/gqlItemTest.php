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

    protected $create_item_name = "gql-create-unit-test";
    protected $create_item_batch = "unit-c-gql";
    protected $create_item_price = 20.2;
    protected $edit_item_name = "gql-edit-unit-test";
    protected $edit_item_batch = "unit-e-gql";
    protected $edit_item_price = 200;

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
        $response->assertStatus(200)
            // ->assertJsonPath('data', 'invalid_credentials')
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        
                    ]
                ]
            ])
        ;
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
                    name: "'.$this->create_item_name.'",
                    batch: "'.$this->create_item_batch.'",
                    price: '.$this->create_item_price.'
                ){
                    id
                    name
                    batch
                    price
                }
            }
        ');
        var_dump($response->content());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'createItem' => [
                        'id',
                        'name',
                        'batch',
                        'price',
                    ]
                ]
            ])
            ->assertJsonFragment(['name' => "$this->create_item_name"])
            ->assertJsonFragment(['batch' => "$this->create_item_batch"])
            ->assertJsonFragment(['price' => "$this->create_item_price"])
        ;
        return $this->jsonStringToArr($response->content())["data"]["createItem"]["id"];
    }

    /**
    {
        "data": {
            "item": {
                "id": "31",
                "name": "gql-unit-test",
                "batch": "unit-gql",
                "price": "122.5"
            }
        }
    }
     */
    protected function testReadExistingItemTest($id)
    {
        $response = $this->graphQL(/** @lang GraphQL */ '
            {
                item(id: '.$id.'){
                    id
                    name
                    batch
                    price
                }
            }
        ');
        var_dump($response->content());
        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonStructure([
                'data' => [
                    'item' => [
                        'id',
                        'name',
                        'batch',
                        'price',
                    ]
                ]
            ])
            ->assertJsonFragment(['id' => "$id"]);
    }

    /**
    {
        "data": {
            "item": null
        }
    }
     */
    protected function testReadNonExistingItemTest()
    {
        $response = $this->graphQL(/** @lang GraphQL */ '
            {
                item(id: 33451){
                    id
                    name
                    batch
                    price
                }
            }
        ');
        var_dump($response->content());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'item'
                ]
            ])
            ->assertJsonFragment(['item' => null])
        ;
    }

    /**
    {
        "data": {
            "updateItem": {
                "id": "36",
                "name": "33",
                "batch": "d",
                "price": "33"
            }
        }
    }
     */
    protected function testEditItemTest($id)
    {
        $response = $this->graphQL(/** @lang GraphQL */ '
            mutation{
                updateItem(
                    id:'.$id.'
                    data:{
                        name: "'.$this->edit_item_name.'",
                        price: '.$this->edit_item_price.'
                        batch: "'.$this->edit_item_batch.'"
                    }
                ){
                    id
                    name
                    batch
                    price
                }
            }
        ');
        var_dump($response->content());
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'updateItem' => [
                        'id',
                        'name',
                        'batch',
                        'price',
                    ]
                ]
            ])
            ->assertJsonFragment(['id' => "$id"])
            ->assertJsonFragment(['name' => "$this->edit_item_name"])
            ->assertJsonFragment(['batch' => "$this->edit_item_batch"])
            ->assertJsonFragment(['price' => "$this->edit_item_price"])
        ;
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
    protected function testDeleteItemTest($id)
    {
        $response = $this->graphQL(/** @lang GraphQL */ '
            mutation{
                deleteItem(
                    id: '.$id.'
                ){
                    id
                    name
                    batch
                    price
                }
            }
        ');
        var_dump($response->content());
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
            ->assertJsonFragment(['id' => "$id"])
            ->assertJsonFragment(['name' => "$this->edit_item_name"])
            ->assertJsonFragment(['batch' => "$this->edit_item_batch"])
            ->assertJsonFragment(['price' => "$this->edit_item_price"])
        ;
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
