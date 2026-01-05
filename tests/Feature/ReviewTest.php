<?php

use App\Models\Comment;
use App\Models\Movie;
use App\Models\Review;
use App\Models\User;

function createMovieForReviewTests(): Movie
{
    return Movie::create([
        'tmdb_id' => 1001,
        'name' => 'Example Movie',
        'year' => 2024,
        'description' => 'Example description.',
        'language' => 'en',
    ]);
}

function createReviewForReviewTests(User $user, Movie $movie): Review
{
    return Review::create([
        'user_id' => $user->id,
        'movie_id' => $movie->id,
        'title' => 'Solid watch',
        'rating' => 4,
        'description' => 'Great performances and pacing.',
        'spoilers' => false,
    ]);
}


// T-29
test('reviews index displays reviews', function () {
    $user = User::factory()->create();
    $movie = createMovieForReviewTests();
    $review = createReviewForReviewTests($user, $movie);

    $response = $this->get('/reviews');

    $response
        ->assertOk()
        ->assertViewHas('reviews', function ($reviews) use ($review) {
            return $reviews->contains('id', $review->id);
        });
});

// T-26
test('review show loads comments', function () {
    $user = User::factory()->create();
    $movie = createMovieForReviewTests();
    $review = createReviewForReviewTests($user, $movie);
    $comment = Comment::create([
        'user_id' => $user->id,
        'review_id' => $review->id,
        'description' => 'Helpful perspective.',
    ]);

    $response = $this->get("/reviews/{$review->id}");

    $response
        ->assertOk()
        ->assertViewHas('review', function ($loadedReview) use ($comment, $review) {
            return $loadedReview->id === $review->id
                && $loadedReview->comments->count() === 1
                && $loadedReview->comments->first()->id === $comment->id;
        });
});

// T-27
test('guests are redirected when liking a review', function () {
    $user = User::factory()->create();
    $movie = createMovieForReviewTests();
    $review = createReviewForReviewTests($user, $movie);

    $response = $this->post(route('reviews.like', $review));

    $response->assertRedirect(route('login', absolute: false));
});


// T-28
test('users can like and unlike reviews', function () {
    $author = User::factory()->create();
    $liker = User::factory()->create();
    $movie = createMovieForReviewTests();
    $review = createReviewForReviewTests($author, $movie);

    $response = $this
        ->actingAs($liker)
        ->from('/reviews')
        ->post(route('reviews.like', $review));

    $response->assertRedirect('/reviews');
    $this->assertTrue($review->likedBy()->where('user_id', $liker->id)->exists());

    $response = $this
        ->actingAs($liker)
        ->from('/reviews')
        ->post(route('reviews.like', $review));

    $response->assertRedirect('/reviews');
    $this->assertFalse($review->likedBy()->where('user_id', $liker->id)->exists());
});

// T-20
test('review title cannot be empty', function () {
    $user = User::factory()->create();
    $movie = createMovieForReviewTests();

    $response = $this
        ->actingAs($user)
        ->from('/previous')
        ->post('/reviews', [
            'movie_id' => $movie->id,
            'title' => '',
            'rating' => 5,
            'comment' => 'Great performances and pacing.',
            'spoilers' => '0',
        ]);

    $response
        ->assertSessionHasErrors(['title'])
        ->assertRedirect('/previous');
    $this->assertDatabaseCount('reviews', 0);
});

// T-22
test('review rating cannot be empty', function () {
    $user = User::factory()->create();
    $movie = createMovieForReviewTests();

    $response = $this
        ->actingAs($user)
        ->from('/previous')
        ->post('/reviews', [
            'movie_id' => $movie->id,
            'title' => 'Good watch',
            'rating' => '',
            'comment' => 'Great performances and pacing.',
            'spoilers' => '0',
        ]);

    $response
        ->assertSessionHasErrors(['rating'])
        ->assertRedirect('/previous');
    $this->assertDatabaseCount('reviews', 0);
});

// T-20
test('review text cannot be empty', function () {
    $user = User::factory()->create();
    $movie = createMovieForReviewTests();

    $response = $this
        ->actingAs($user)
        ->from('/previous')
        ->post('/reviews', [
            'movie_id' => $movie->id,
            'title' => 'Good watch',
            'rating' => 4,
            'comment' => '',
            'spoilers' => '0',
        ]);

    $response
        ->assertSessionHasErrors(['comment'])
        ->assertRedirect('/previous');
    $this->assertDatabaseCount('reviews', 0);
});

// T-21
test('review text cannot be more than 1000 symbols', function () {
    $user = User::factory()->create();
    $movie = createMovieForReviewTests();

    $response = $this
        ->actingAs($user)
        ->from('/previous')
        ->post('/reviews', [
            'movie_id' => $movie->id,
            'title' => 'Name',
            'rating' => 4,
            'comment' => str_repeat('a', 1001),
            'spoilers' => '0',
        ]);

    $response
        ->assertSessionHasErrors(['comment'])
        ->assertRedirect('/previous');
    $this->assertDatabaseCount('reviews', 0);
});

test('review title cannot be more than 30 symbols', function () {
    $user = User::factory()->create();
    $movie = createMovieForReviewTests();

    $response = $this
        ->actingAs($user)
        ->from('/previous')
        ->post('/reviews', [
            'movie_id' => $movie->id,
            'title' => str_repeat('a', 31),
            'rating' => 4,
            'comment' => 'Lorem ipsum',
            'spoilers' => '0',
        ]);

    $response
        ->assertSessionHasErrors(['title'])
        ->assertRedirect('/previous');
    $this->assertDatabaseCount('reviews', 0);
});

// T-23
test('users can create a review without spoilers', function () {
    $user = User::factory()->create();
    $movie = createMovieForReviewTests();

    $response = $this
        ->actingAs($user)
        ->from('/previous')
        ->post('/reviews', [
            'movie_id' => $movie->id,
            'title' => 'My favorite movie',
            'rating' => 5,
            'comment' => 'Love this movie',
            'spoilers' => '0',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success', 'Review successfully posted.')
        ->assertRedirect('/previous');

    $this->assertDatabaseHas('reviews', [
        'user_id' => $user->id,
        'movie_id' => $movie->id,
        'title' => 'My favorite movie',
        'rating' => 5,
        'description' => 'Love this movie',
        'spoilers' => 0,
    ]);
});

// T-24
test('users can create a review marked as spoilers', function () {
    $user = User::factory()->create();
    $movie = createMovieForReviewTests();

    $response = $this
        ->actingAs($user)
        ->from('/previous')
        ->post('/reviews', [
            'movie_id' => $movie->id,
            'title' => 'My favorite movie',
            'rating' => 5,
            'comment' => 'Love this movie',
            'spoilers' => '1',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success', 'Review successfully posted.')
        ->assertRedirect('/previous');

    $this->assertDatabaseHas('reviews', [
        'user_id' => $user->id,
        'movie_id' => $movie->id,
        'title' => 'My favorite movie',
        'rating' => 5,
        'description' => 'Love this movie',
        'spoilers' => 1,
    ]);
});

test('users can delete their review', function () {
    $user = User::factory()->create();
    $movie = createMovieForReviewTests();
    $review = createReviewForReviewTests($user, $movie);

    $response = $this
        ->actingAs($user)
        ->from('/previous')
        ->delete("/reviews/{$review->id}");

    $response
        ->assertSessionHas('status', 'Review successfully deleted.')
        ->assertRedirect('/previous');

    $this->assertDatabaseMissing('reviews', [
        'id' => $review->id,
    ]);
});
