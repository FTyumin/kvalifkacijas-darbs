<!-- resources/views/movies/recommendations.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Personal Recommendations Section -->
    @auth
    <div class="mb-12">
        <h2 class="text-2xl font-bold mb-6">Recommended For You</h2>
        <div id="personal-recommendations" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>
    @endauth

    <!-- Trending Movies Section -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold mb-6">Trending Now</h2>
        <div id="trending-movies" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>
    <!-- Popular Movies Section -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold mb-6">Popular Movies</h2>
        <div id="popular-movies" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>
</div>

<!-- Movie Card Template -->
<template id="movie-card-template">
    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow cursor-pointer movie-card">
        <img class="movie-poster w-full h-64 object-cover" src="" alt="">
        <div class="p-4">
            <h3 class="movie-title font-semibold text-lg mb-1"></h3>
            <p class="movie-year text-gray-600 text-sm mb-2"></p>
            <div class="movie-genres text-xs text-blue-600 mb-2"></div>
            <div class="flex justify-between items-center">
                <div class="movie-rating flex items-center">
                    <span class="text-yellow-500">★</span>
                    <span class="ml-1 text-sm"></span>
                </div>
                <button class="rate-btn bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600">
                    Rate
                </button>
            </div>
        </div>
    </div>
</template>

<!-- Rating Modal -->
<div id="rating-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-semibold mb-4">Rate Movie</h3>
        <div class="movie-info mb-4">
            <h4 id="modal-movie-title" class="font-medium"></h4>
            <p id="modal-movie-year" class="text-gray-600"></p>
        </div>
        <div class="rating-stars mb-4">
            <span class="text-2xl cursor-pointer hover:text-yellow-500" data-rating="1">★</span>
            <span class="text-2xl cursor-pointer hover:text-yellow-500" data-rating="2">★</span>
            <span class="text-2xl cursor-pointer hover:text-yellow-500" data-rating="3">★</span>
            <span class="text-2xl cursor-pointer hover:text-yellow-500" data-rating="4">★</span>
            <span class="text-2xl cursor-pointer hover:text-yellow-500" data-rating="5">★</span>
        </div>
        <div class="flex justify-end gap-2">
            <button id="cancel-rating" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
            <button id="submit-rating" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Rate</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedRating = 0;
    let currentMovieId = null;

    // Load recommendations on page load
    loadHomepageRecommendations();

    // Rating modal functionality
    const modal = document.getElementById('rating-modal');
    const ratingStars = document.querySelectorAll('.rating-stars span');
    const submitBtn = document.getElementById('submit-rating');
    const cancelBtn = document.getElementById('cancel-rating');

    // Star rating interaction
    ratingStars.forEach(star => {
        star.addEventListener('click', function() {
            selectedRating = parseInt(this.dataset.rating);
            updateStarDisplay();
        });

        star.addEventListener('mouseover', function() {
            const hoverRating = parseInt(this.dataset.rating);
            highlightStars(hoverRating);
        });
    });

    document.querySelector('.rating-stars').addEventListener('mouseleave', function() {
        updateStarDisplay();
    });

    // Submit rating
    submitBtn.addEventListener('click', function() {
        if (selectedRating > 0 && currentMovieId) {
            submitMovieRating(currentMovieId, selectedRating);
        }
    });

    // Cancel rating
    cancelBtn.addEventListener('click', function() {
        closeRatingModal();
    });

    // Close modal on backdrop click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeRatingModal();
        }
    });

    function loadHomepageRecommendations() {
        fetch('/recommendations/homepage')
            .then(response => response.json())
            .then(data => {
                if (data.personal_recommendations) {
                    renderMovies(data.personal_recommendations, 'personal-recommendations');
                }
                if (data.trending) {
                    renderMovies(data.trending, 'trending-movies');
                }
                if (data.popular) {
                    renderMovies(data.popular, 'popular-movies');
                }
            })
            .catch(error => {
                console.error('Error loading recommendations:', error);
            });
    }

    function renderMovies(movies, containerId) {
        const container = document.getElementById(containerId);
        const template = document.getElementById('movie-card-template');
        
        container.innerHTML = '';

        movies.forEach(item => {
            const movie = item.movie || item; // Handle different response structures
            const clone = template.content.cloneNode(true);
            
            const poster = clone.querySelector('.movie-poster');
            poster.src = movie.poster_url || '/images/default-movie-poster.jpg';
            poster.alt = movie.title;
            
            clone.querySelector('.movie-title').textContent = movie.title;
            clone.querySelector('.movie-year').textContent = movie.year;
            
            // Handle genres
            if (movie.genres && movie.genres.length > 0) {
                const genreNames = typeof movie.genres[0] === 'string' 
                    ? movie.genres 
                    : movie.genres.map(g => g.name);
                clone.querySelector('.movie-genres').textContent = genreNames.join(', ');
            }
            
            // Handle rating
            const rating = item.predicted_rating || item.similarity_score || movie.average_rating || movie.imdb_rating || 0;
            clone.querySelector('.movie-rating span:last-child').textContent = rating.toFixed(1);
            
            // Add click handlers
            const movieCard = clone.querySelector('.movie-card');
            const rateBtn = clone.querySelector('.rate-btn');
            
            movieCard.addEventListener('click', function(e) {
                if (!e.target.classList.contains('rate-btn')) {
                    // Navigate to movie details
                    window.location.href = `/movies/${movie.id}`;
                }
            });
            
            rateBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                openRatingModal(movie);
            });
            
            container.appendChild(clone);
        });
    }

    function openRatingModal(movie) {
        currentMovieId = movie.id;
        document.getElementById('modal-movie-title').textContent = movie.title;
        document.getElementById('modal-movie-year').textContent = movie.year;
        selectedRating = 0;
        updateStarDisplay();
        modal.classList.remove('hidden');
    }

    function closeRatingModal() {
        modal.classList.add('hidden');
        currentMovieId = null;
        selectedRating = 0;
    }

    function updateStarDisplay() {
        ratingStars.forEach((star, index) => {
            if (index < selectedRating) {
                star.classList.add('text-yellow-500');
                star.classList.remove('text-gray-300');
            } else {
                star.classList.remove('text-yellow-500');
                star.classList.add('text-gray-300');
            }
        });
    }

    function highlightStars(rating) {
        ratingStars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('text-yellow-400');
                star.classList.remove('text-gray-300');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }

    function submitMovieRating(movieId, rating) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/movies/${movieId}/rate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}` // If using API tokens
            },
            body: JSON.stringify({ rating: rating })
        })
        .then(response => response.json())
        .then(data => {
            closeRatingModal();
            
            // Show success message
            showNotification('Rating submitted successfully!', 'success');
            
            // Refresh personal recommendations if available
            if (data.fresh_recommendations) {
                renderMovies(data.fresh_recommendations, 'personal-recommendations');
            }
        })
        .catch(error => {
            console.error('Error submitting rating:', error);
            showNotification('Error submitting rating. Please try again.', 'error');
        });
    }

    function showNotification(message, type) {
        // Simple notification system
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
});
</script>
@endsection



<!-- Command to generate sample data

Usage Instructions:

1. Run migrations: php artisan migrate
2. Seed basic data: php artisan db:seed --class=MovieSeeder
3. Generate sample ratings: php artisan movies:generate-data
4. Clear cache: php artisan cache:clear
5. Access recommendations at: /recommendations
 -->