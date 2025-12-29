<?php

use App\Models\User;
use App\Models\UserRelationship;

// T-16
test('users can follow other users', function () {
    $follower = User::factory()->create();
    $followee = User::factory()->create();

    $response = $this
        ->actingAs($follower)
        ->post("/api/users/{$followee->id}/follow");

    $response
        ->assertOk()
        ->assertJson(['following' => true]);

    $this->assertDatabaseHas('user_relationships', [
        'follower_id' => $follower->id,
        'followee_id' => $followee->id,
    ]);
});

// T-17
test('users can unfollow other users', function () {
    $follower = User::factory()->create();
    $followee = User::factory()->create();

    UserRelationship::create([
        'follower_id' => $follower->id,
        'followee_id' => $followee->id,
    ]);

    $response = $this
        ->actingAs($follower)
        ->delete("/api/users/{$followee->id}/unfollow");

    $response
        ->assertOk()
        ->assertJson(['following' => false]);

    $this->assertDatabaseMissing('user_relationships', [
        'follower_id' => $follower->id,
        'followee_id' => $followee->id,
    ]);
});

// T-18
test('users can view their followers list', function () {
    $user = User::factory()->create();
    $follower = User::factory()->create();

    UserRelationship::create([
        'follower_id' => $follower->id,
        'followee_id' => $user->id,
    ]);

    $response = $this->get("/users/{$user->id}/followers");

    $response
        ->assertOk()
        ->assertJsonCount(1)
        ->assertJsonFragment([
            'follower_id' => $follower->id,
            'followee_id' => $user->id,
        ]);
});

// T-19
test('users can view their followees list', function () {
    $user = User::factory()->create();
    $followee = User::factory()->create();

    UserRelationship::create([
        'follower_id' => $user->id,
        'followee_id' => $followee->id,
    ]);

    $response = $this->get("/users/{$user->id}/followees");

    $response
        ->assertOk()
        ->assertJsonCount(1)
        ->assertJsonFragment([
            'follower_id' => $user->id,
            'followee_id' => $followee->id,
        ]);
});
