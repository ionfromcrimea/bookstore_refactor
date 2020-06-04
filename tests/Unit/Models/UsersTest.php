<?php

namespace Tests\Unit\Models;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @watch
     */
    public function a_users_ID_is_a_UUID_instead_of_an_integer()
    {
        $user = factory(User::class)->create();
        $this->assertFalse(is_integer($user->id));
        $this->assertEquals(36, strlen($user->id));
    }

    /**
     * @test
     * @watch
     */
    public function it_has_a_role_of_user_by_default()
    {
        $user = factory(User::class)->create();
        $this->assertEquals('user', $user->role);
    }

    /**
     * @test
     * @watch
     */
    public function it_returns_a_user_as_a_resource_object()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->getJson("/api/v1/users/{$user->id}", [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => $user->id,
                    "type" => "users",
                    "attributes" => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'created_at' => $user->created_at->toJSON(),
                        'updated_at' => $user->updated_at->toJSON(),
                    ]
                ]
            ]);
    }

    /**
     * @test
     * @watch
     */
    public function it_returns_all_users_as_a_collection_of_resource_objects()
    {
        $users = factory(User::class, 3)->create();
//        $users = $users->sortBy(function ($item, $key) {
//            return $item->id;
//        })->values();
        Passport::actingAs($users->first());
        $this->getJson("/api/v1/users", [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "id" => $users[0]->id,
                        "type" => "users",
                        "attributes" => [
                            'name' => $users[0]->name,
                            'email' => $users[0]->email,
                            'role' => 'user',
                            'created_at' => $users[0]->created_at->toJSON(),
                            'updated_at' => $users[0]->updated_at->toJSON(),
                        ]
                    ],
                    [
                        "id" => $users[1]->id,
                        "type" => "users",
                        "attributes" => [
                            'name' => $users[1]->name,
                            'email' => $users[1]->email,
                            'role' => 'user',
                            'created_at' => $users[1]->created_at->toJSON(),
                            'updated_at' => $users[1]->updated_at->toJSON(),
                        ]
                    ],
                    [
                        "id" => $users[2]->id,
                        "type" => "users",
                        "attributes" => [
                            'name' => $users[2]->name,
                            'email' => $users[2]->email,
                            'role' => 'user',
                            'created_at' => $users[2]->created_at->toJSON(),
                            'updated_at' => $users[2]->updated_at->toJSON(),
                        ]
                    ],
                ]
            ]);
    }

    /**
     * @test
     * @watch
     */
    public function it_can_create_an_user_from_a_resource_object()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $response = $this->postJson('/api/v1/users', [
            'data' => [
                'type' => 'users',
                'attributes' => [
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'password' => 'secret',
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    "type" => "users",
                    "attributes" => [
                        'name' => 'John Doe',
                        'email' => 'john@example.com',
                        'role' => 'user',
                        'created_at' => now()->setMilliseconds(0)->toJSON(),
                        'updated_at' => now()->setMilliseconds(0)->toJSON(),
                    ]
                ]
            ]);
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'user',
        ]);
        $this->assertTrue(Hash::check('secret', User::whereName('John Doe')->first()->password));
    }
}
