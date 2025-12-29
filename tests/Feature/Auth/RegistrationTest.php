<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});


// T-1
test('registration requires a name', function () {
    $response = $this->post('/register', [
        'name' => '',
        'email' => 'test@example.com',
        'password' => 'Test123!',
        'password_confirmation' => 'Test123!',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors(['name']);
});

// T-2
test('registration requires matching passwords', function () {
    $response = $this->post('/register', [
        'name' => 'MovieLover',
        'email' => 'test@example.com',
        'password' => 'testPassw0d',
        'password_confirmation' => 'testPasssw04d',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors(['password']);
});

// T-3
test('registration requires a valid email', function () {
    $response = $this->post('/register', [
        'name' => 'John',
        'email' => 'johngmail.c',
        'password' => 'Test123!',
        'password_confirmation' => 'Test123!',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors(['email']);
});

// T-4
test('new users can register', function () {
    Storage::fake('public');

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'image' => UploadedFile::fake()->image('avatar.jpg'),
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('quiz.show', absolute: false));

    $user = User::where('email', 'test@example.com')->firstOrFail();
    Storage::disk('public')->assertExists($user->image);
});

// T-5
test('registration requires a unique email', function () {
    User::factory()->create([
        'email' => 'abc@gmail.com',
    ]);

    $response = $this->post('/register', [
        'name' => 'MovieLover',
        'email' => 'abc@gmail.com',
        'password' => 'pass$(0k1@',
        'password_confirmation' => 'pass$(0k1@',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors(['email']);
});
