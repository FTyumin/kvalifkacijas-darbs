<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

// T-11
test('users can view their own profile via profile show route', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('profile.show', $user, absolute: false));

    $response->assertRedirect('/dashboard');
});

// T-12
test('users can view other user profiles', function () {
    $viewer = User::factory()->create();
    $otherUser = User::factory()->create();

    $response = $this
        ->actingAs($viewer)
        ->get(route('profile.show', $otherUser, absolute: false));

    $response
        ->assertOk()
        ->assertViewHas('user', fn ($user) => $user->id === $otherUser->id);
});

// T-13
test('users can edit their profile with valid data', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
    ]);

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->patch('/profile', [
            'name' => 'KeanuReaves',
            'email' => 'abc@gmail.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertSessionHas('status', 'profile-updated')
        ->assertRedirect(route('profile.show', $user, absolute: false));

    $user->refresh();
    $this->assertSame('KeanuReaves', $user->name);
    $this->assertSame('abc@gmail.com', $user->email);
});

// T-14
test('users can not edit profile with empty name', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
    ]);

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->patch('/profile', [
            'name' => '',
            'email' => 'abc@gmail.com',
        ]);

    $response
        ->assertSessionHasErrors(['name'])
        ->assertRedirect('/profile');

    $user->refresh();
    $this->assertSame('Old Name', $user->name);
    $this->assertSame('old@example.com', $user->email);
});


// test('email verification status is unchanged when the email address is unchanged', function () {

// T-15
test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

// test('correct password must be provided to delete account', function () {
//     $user = User::factory()->create();

//     $response = $this
//         ->actingAs($user)
//         ->from('/profile')
//         ->delete('/profile', [
//             'password' => 'wrong-password',
//         ]);

//     $response
//         ->assertSessionHasErrorsIn('userDeletion', 'password')
//         ->assertRedirect('/profile');

//     $this->assertNotNull($user->fresh());
// });
