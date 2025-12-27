<?php

use App\Models\Comment;
use App\Models\Movie;
use App\Models\Review;
use App\Models\User;
use App\Livewire\CreateReview;
use Livewire\Livewire;

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

test('guests are redirected when liking a review', function () {
    $user = User::factory()->create();
    $movie = createMovieForReviewTests();
    $review = createReviewForReviewTests($user, $movie);

    $response = $this->post(route('reviews.like', $review));

    $response->assertRedirect(route('login', absolute: false));
});

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

test('review title cannot be empty', function () {
    $user = User::factory()->create();
    $movie = createMovieForReviewTests();

    Livewire::actingAs($user)
        ->test(CreateReview::class, ['movie' => $movie])
        ->set('rating', 4)
        ->set('comment', 'Great performances and pacing.')
        ->set('title', '')
        ->call('save')
        ->assertHasErrors(['title' => 'required']);

    expect(Review::count())->toBe(0);
});

test('review rating cannot be empty', function () {
    $user = User::factory()->create();
    $movie = createMovieForReviewTests();

    Livewire::actingAs($user)
        ->test(CreateReview::class, ['movie' => $movie])
        ->set('comment', 'Great performances and pacing.')
        ->set('title', 'Good watch')
        ->call('save')
        ->assertHasErrors(['rating' => 'required']);

    expect(Review::count())->toBe(0);
});

test('review comment cannot be empty', function () {
    $user = User::factory()->create();
    $movie = createMovieForReviewTests();

    Livewire::actingAs($user)
        ->test(CreateReview::class, ['movie' => $movie])
        ->set('rating', 4)
        ->set('title', 'Good watch')
        ->call('save')
        ->assertHasErrors(['comment' => 'required']);

    expect(Review::count())->toBe(0);
});

test('review text cannot be more than 300 symbols', function () {
    $user = User::factory()->create();
    $movie = createMovieForReviewTests();

    Livewire::actingAs($user)
        ->test(CreateReview::class, ['movie' => $movie])
        ->set('rating', 4)
        ->set('comment', str_repeat('a', 1001))
        ->set('title', 'Name')
        ->call('save')
        ->assertHasErrors(['comment' => 'max']);

    expect(Review::count())->toBe(0);
});

test('review title cannot be more than 30 symbols', function () {
    $user = User::factory()->create();
    $movie = createMovieForReviewTests();

    Livewire::actingAs($user)
        ->test(CreateReview::class, ['movie' => $movie])
        ->set('rating', 4)
        ->set('comment', 'Lorem ipsum')
        ->set('title', str_repeat('a', 31))
        ->call('save')
        ->assertHasErrors(['title' => 'max']);

    expect(Review::count())->toBe(0);
});


