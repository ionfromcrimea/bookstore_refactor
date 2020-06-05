<?php

namespace Tests\Feature;

use App\Comment;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UsersRelationshipsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @watch
     */
    public function it_returns_a_relationship_to_comments_adhering_to_json_api_spec()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $comments = factory(Comment::class, 3)->make();
        $user->comments()->saveMany($comments);
        $this->getJson("/api/v1/users/{$user->id}?include=comments", [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => $user->id,
                    "type" => "users",
                    "attributes" => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'created_at' => $user->created_at->toJSON(),
                        'updated_at' => $user->updated_at->toJSON(),
                    ],
                    'relationships' => [
                        'comments' => [
                            'links' => [
                                'self' => route(
                                    'users.relationships.comments',
                                    ['user' => $user->id]
                                ),
                                'related' => route(
                                    'users.comments',
                                    ['user' => $user->id]
                                ),
                            ],
                            'data' => [
                                [
                                    'id' => $comments->get(0)->id,
                                    'type' => 'comments'
                                ],
                                [
                                    'id' => $comments->get(1)->id,
                                    'type' => 'comments'
                                ],
                                [
                                    'id' => $comments->get(2)->id,
                                    'type' => 'comments'
                                ]
                            ]
                        ]
                    ]
                ],
                'included' => [
                    [
                        'id' => '1',
                        'type' => 'comments',
                        'attributes' => [
                            'message' => $comments->get(0)->message,
                            'created_at' => $comments->get(0)->created_at->toJson(),
                            'updated_at' => $comments->get(0)->updated_at->toJson(),
                        ]
                    ],
                    [
                        'id' => '2',
                        'type' => 'comments',
                        'attributes' => [
                            'message' => $comments->get(1)->message,
                            'created_at' => $comments->get(1)->created_at->toJson(),
                            'updated_at' => $comments->get(1)->updated_at->toJson(),
                        ]
                    ],
                    [
                        'id' => '3',
                        'type' => 'comments',
                        'attributes' => [
                            'message' => $comments->get(2)->message,
                            'created_at' => $comments->get(2)->created_at->toJson(),
                            'updated_at' => $comments->get(2)->updated_at->toJson(),
                        ]
                    ],
                ]
            ]);
    }

}
