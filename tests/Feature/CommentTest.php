<?php

use App\Models\Comment;
use App\Models\Movie;
use App\Models\Review;
use App\Models\User;

function createMovieForCommentTests(): Movie
{
    return Movie::create([
        'tmdb_id' => 2001,
        'name' => 'Comment Movie',
        'year' => 2024,
        'description' => 'Example description.',
        'language' => 'en',
    ]);
}

function createReviewForCommentTests(User $user, Movie $movie): Review
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

// T-30
test('comment text is required', function () {
    $user = User::factory()->create();
    $movie = createMovieForCommentTests();
    $review = createReviewForCommentTests($user, $movie);

    $response = $this
        ->actingAs($user)
        ->from('/previous')
        ->post('/comments', [
            'review_id' => $review->id,
            'comment' => '',
        ]);

    $response
        ->assertSessionHasErrors(['comment'])
        ->assertRedirect('/previous');
    $this->assertDatabaseCount('comments', 0);
});

// T-31
test('comment text cannot exceed 300 symbols', function () {
    $user = User::factory()->create();
    $movie = createMovieForCommentTests();
    $review = createReviewForCommentTests($user, $movie);

    $response = $this
        ->actingAs($user)
        ->from('/previous')
        ->post('/comments', [
            'review_id' => $review->id,
            'comment' => str_repeat('a', 301),
        ]);

    $response
        ->assertSessionHasErrors(['comment'])
        ->assertRedirect('/previous');
    $this->assertDatabaseCount('comments', 0);
});

// T-32
test('users can create a comment', function () {
    $user = User::factory()->create();
    $movie = createMovieForCommentTests();
    $review = createReviewForCommentTests($user, $movie);

    $response = $this
        ->actingAs($user)
        ->from('/previous')
        ->post('/comments', [
            'review_id' => $review->id,
            'comment' => 'I disagree, best Nolan movie always will be the dark knight',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/previous');

    $this->assertDatabaseHas('comments', [
        'user_id' => $user->id,
        'review_id' => $review->id,
        'description' => 'I disagree, best Nolan movie always will be the dark knight',
    ]);
});

// T-33
test('users can delete their comment', function () {
    $user = User::factory()->create();
    $movie = createMovieForCommentTests();
    $review = createReviewForCommentTests($user, $movie);
    $comment = Comment::create([
        'user_id' => $user->id,
        'review_id' => $review->id,
        'description' => 'Short comment.',
    ]);

    $response = $this
        ->actingAs($user)
        ->from('/previous')
        ->delete("/comments/{$comment->id}");

    $response->assertRedirect('/previous');
    $this->assertDatabaseMissing('comments', [
        'id' => $comment->id,
    ]);
});
