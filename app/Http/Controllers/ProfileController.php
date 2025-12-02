<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Models\Movie;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        // Augšupielādēt failu
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('profiles', 'public'); 
            $request->user()->image = $path;
            $data['image'] = $path;
        }


        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.show')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function show(User $user) {

        if($user->id == auth()->user()->id) {
            $movies = auth()->user()->wantToWatch;
            $user = \Auth::user();
            $reviews = Review::where('user_id', auth()->user()->id)->get();
            $review_count = Review::where('user_id', $user->id)->count();
            $average_review = round(Review::where('user_id', $user->id)->avg('rating'), 2) ?? 0;

            return view('dashboard', compact('reviews', 'user', 'movies', 'average_review'));
        } else {
            $movies = $user->movies;
            $reviews = $user->reviews;
            $review_count = Review::where('user_id', $user->id)->count();
            $average_review = round(Review::where('user_id', $user->id)->avg('rating'), 2) ?? 0;
            
            return view('profile.show', compact('user', 'movies', 'reviews', 'review_count', 'average_review'));
        }
    }
}
