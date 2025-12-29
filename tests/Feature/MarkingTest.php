<?php

use App\Models\Movie;
use App\Models\Person;
use App\Models\User;

function createMovieForMarkingTests(): Movie
{
    return Movie::create([
        'tmdb_id' => 4001,
        'name' => 'Marking Movie',
        'year' => 2024,
        'description' => 'Example description.',
        'language' => 'en',
    ]);
}

function createPersonForMarkingTests(): Person
{
    return Person::create([
        'tmdb_id' => 5001,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'slug' => 'john-doe',
    ]);
}

// T-69
test('users can favorite and unfavorite a movie', function () {
    $user = User::factory()->create();
    $movie = createMovieForMarkingTests();

    $response = $this
        ->actingAs($user)
        ->from('/previous')
        ->post(route('favorite.toggle', $movie->id, absolute: false));

    $response
        ->assertSessionHas('success', 'Movie added to favorites!')
        ->assertRedirect('/previous');

    $this->assertDatabaseHas('markable_favorites', [
        'user_id' => $user->id,
        'markable_id' => $movie->id,
        'markable_type' => Movie::class,
    ]);

    $response = $this
        ->actingAs($user)
        ->from('/previous')
        ->post(route('favorite.toggle', $movie->id, absolute: false));

    $response->assertRedirect('/previous');

    $this->assertDatabaseMissing('markable_favorites', [
        'user_id' => $user->id,
        'markable_id' => $movie->id,
        'markable_type' => Movie::class,
    ]);
});

// T-70
test('users can view a person page', function () {
    $person = createPersonForMarkingTests();

    $response = $this->get(route('people.show', $person->slug, absolute: false));

    $response
        ->assertOk()
        ->assertViewHas('person', fn ($viewPerson) => $viewPerson->id === $person->id);
});

// T-71
test('users can mark a person as favorite', function () {
    $user = User::factory()->create();
    $person = createPersonForMarkingTests();

    $response = $this
        ->actingAs($user)
        ->from('/previous')
        ->post(route('person.favorite', $person->id, absolute: false));

    $response
        ->assertSessionHas('success', 'Person marked as favorite!')
        ->assertRedirect('/previous');
});

