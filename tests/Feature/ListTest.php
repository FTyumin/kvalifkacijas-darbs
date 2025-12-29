<?php

use App\Models\Movie;
use App\Models\MovieList;
use App\Models\User;

function createMovieForListTests(): Movie
{
    return Movie::create([
        'tmdb_id' => 3001,
        'name' => 'List Movie',
        'year' => 2024,
        'description' => 'Example description.',
        'language' => 'en',
    ]);
}

function createListForTests(User $user, array $overrides = []): MovieList
{
    return MovieList::create(array_merge([
        'user_id' => $user->id,
        'name' => 'Favorites',
        'description' => 'Favorite comedies',
        'is_private' => false,
    ], $overrides));
}

// T-56
test('list creation requires a name', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/lists/create')
        ->post('/lists', [
            'name' => '',
            'description' => 'Favorite comedies',
        ]);

    $response
        ->assertSessionHasErrors(['name'])
        ->assertRedirect('/lists/create');
    $this->assertDatabaseCount('lists', 0);
});

// T-57
test('list creation requires a description', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/lists/create')
        ->post('/lists', [
            'name' => 'Best dicaprio movies',
            'description' => '',
        ]);

    $response
        ->assertSessionHasErrors(['description'])
        ->assertRedirect('/lists/create');
    $this->assertDatabaseCount('lists', 0);
});

// T-58
test('users can view all public lists', function () {
    $owner = User::factory()->create();
    $publicList = createListForTests($owner, ['is_private' => false]);

    $response = $this->get('/lists');

    $response
        ->assertOk()
        ->assertViewHas('lists', function ($lists) use ($publicList) {
            return $lists->contains('id', $publicList->id);
        });
});

// T-59
test('users can view a list by id', function () {
    $owner = User::factory()->create();
    $list = createListForTests($owner);

    $response = $this->get(route('lists.show', $list, absolute: false));

    $response
        ->assertOk()
        ->assertViewHas('list', fn ($viewList) => $viewList->id === $list->id);
});

// T-60
test('users can add a movie to a list', function () {
    $user = User::factory()->create();
    $list = createListForTests($user);
    $movie = createMovieForListTests();

    $response = $this
        ->actingAs($user)
        ->from('/previous')
        ->post("/lists/{$movie->id}/add", [
            'listId' => $list->id,
        ]);

    $response
        ->assertSessionHas('success', 'Movie added to list')
        ->assertRedirect('/previous');

    $this->assertDatabaseHas('movie_lists', [
        'list_id' => $list->id,
        'movie_id' => $movie->id,
    ]);
});

// T-61
test('users can remove a movie from a list', function () {
    $user = User::factory()->create();
    $list = createListForTests($user);
    $movie = createMovieForListTests();
    $list->addMovie($movie->id);

    $response = $this
        ->actingAs($user)
        ->from('/previous')
        ->delete("/lists/{$list->id}/movies/{$movie->id}");

    $response
        ->assertSessionHas('message', 'Movie removed!')
        ->assertRedirect('/previous');

    $this->assertDatabaseMissing('movie_lists', [
        'list_id' => $list->id,
        'movie_id' => $movie->id,
    ]);
});

// T-62
test('adding a movie already in the list returns a warning', function () {
    $user = User::factory()->create();
    $list = createListForTests($user);
    $movie = createMovieForListTests();
    $list->addMovie($movie->id);

    $response = $this
        ->actingAs($user)
        ->from('/previous')
        ->post("/lists/{$movie->id}/add", [
            'listId' => $list->id,
        ]);

    $response
        ->assertSessionHas('warning', 'Movie is already in list')
        ->assertRedirect('/previous');
});

// T-63
test('users can delete their list', function () {
    $user = User::factory()->create();
    $list = createListForTests($user);

    $response = $this
        ->actingAs($user)
        ->from('/lists')
        ->delete(route('lists.destroy', $list, absolute: false));

    $response
        ->assertSessionHas('success', 'List deleted!')
        ->assertRedirect('/lists');

    $this->assertDatabaseMissing('lists', [
        'id' => $list->id,
    ]);
});
