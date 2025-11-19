@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-8 px-4 py-8 space-y-12">

  <!-- 1. Movie Details Section -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Poster and details -->
  </div>

  <!-- 2. Review Form (ONE instance only) -->
  <div>
    <form action="{{ route('reviews.store') }}" method="POST">
      <!-- Form content -->
    </form>
  </div>

  <!-- 3. Reviews List -->
  <div>
    <h3>Reviews</h3>
    @foreach($movie->reviews as $review)
      <!-- Review cards -->
    @endforeach
  </div>

  <!-- 4. Recommendations -->
  <div class="mt-8">
    <h2>Recommendations</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      
    </div>
  </div>

</div>

@push('scripts')
<script>
function toggleSpoiler(reviewId) { ... }
</script>
@endpush

@endsection