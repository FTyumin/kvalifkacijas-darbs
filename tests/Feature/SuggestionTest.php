<?php

use App\Models\Suggestion;
use App\Models\User;
use App\Notifications\SuggestionAccepted;
use App\Notifications\SuggestionRejected;
use Illuminate\Support\Facades\Notification;

// T-64
test('suggestion text is required', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/suggestion')
        ->post(route('suggestions.store', absolute: false), [
            'title' => '',
        ]);

    $response
        ->assertSessionHasErrors(['title'])
        ->assertRedirect('/suggestion');
    $this->assertDatabaseCount('suggestions', 0);
});

// T-65
test('suggestion text cannot exceed 30 symbols', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/suggestion')
        ->post(route('suggestions.store', absolute: false), [
            'title' => str_repeat('a', 31),
        ]);

    $response
        ->assertSessionHasErrors(['title'])
        ->assertRedirect('/suggestion');
    $this->assertDatabaseCount('suggestions', 0);
});

// T-66
test('users can submit a suggestion', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/suggestion')
        ->post(route('suggestions.store', absolute: false), [
            'title' => 'Good time',
        ]);

    $response
        ->assertSessionHas('success', 'Your suggestion has been sent!')
        ->assertRedirect('/');

    $this->assertDatabaseHas('suggestions', [
        'user_id' => $user->id,
        'title' => 'Good time',
    ]);
});

// T-67
test('admins can approve suggestions', function () {
    Notification::fake();

    $user = User::factory()->create();
    $admin = User::factory()->create(['is_admin' => true]);
    $suggestion = Suggestion::create([
        'user_id' => $user->id,
        'title' => 'Good time',
    ]);

    $response = $this
        ->actingAs($admin)
        ->post(route('suggestions.approve', $suggestion, absolute: false));

    $response->assertRedirect();
    $this->assertSame(1, $suggestion->refresh()->accepted);
    Notification::assertSentTo($user, SuggestionAccepted::class);
});

// T-68
test('admins can reject suggestions', function () {
    Notification::fake();

    $user = User::factory()->create();
    $admin = User::factory()->create(['is_admin' => true]);
    $suggestion = Suggestion::create([
        'user_id' => $user->id,
        'title' => 'Good time',
    ]);

    $response = $this
        ->actingAs($admin)
        ->post(route('suggestions.reject', $suggestion, absolute: false));

    $response->assertRedirect();
    $this->assertSame(0, $suggestion->refresh()->accepted);
    Notification::assertSentTo($user, SuggestionRejected::class);
});
