<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Models\Movie;
use App\Models\Review;
use Maize\Markable\Models\Favorite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        // Uploading file
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('profiles', 'public'); 
            $request->user()->image = $path;
            $data['image'] = $path;
        }


        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.show', $request->user()->id)->with('status', 'profile-updated');
    }


    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function show(User $user) {
        if(!$user) {
            abort(404);
        }

        if($user->id == auth()->user()->id) {
            return redirect('dashboard');
        }
        
        // query user data for profile page
        $movies = $user->movies;
        $reviews = $user->reviews;
        $review_count = Review::where('user_id', $user->id)->count();
        $average_review = round(Review::where('user_id', $user->id)->avg('rating'), 2) ?? 0;
        
        return view('profile.show', compact('user', 'movies', 'reviews', 'review_count', 'average_review'));
    }

    public function dashboard(Request $request) {
        $user = $request->user();
        if (!$user) {
            abort(404);
        }

        $watchList = $user->wantToWatch;
        $seen = $user->seenMovies;
        $favorites = $user->favorites->take(8);

        $reviews = Review::where('user_id', $user->id)->get();
        $review_count = Review::where('user_id', $user->id)->count();
        $average_review = round(Review::where('user_id', $user->id)->avg('rating'), 2) ?? 0;


        $lists = $user->lists()->withCount('movies')->latest()->limit(5)->get();

        return view('dashboard', compact('reviews', 'user', 'watchList', 'average_review', 'seen', 'favorites', 'lists'));
    }
}
