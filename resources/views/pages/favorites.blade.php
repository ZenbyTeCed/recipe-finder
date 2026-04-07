@extends('layouts.app')

@section('content')

<div class="favorites-page">
    <div class="favorites-page-container">

        <div class="favorites-header">
            <h1>My Favorites</h1>
            <p><span>{{ $count }}</span> saved recipe{{ $count !== 1 ? 's' : '' }}</p>
        </div>

        <div class="results-section">
            @forelse ($favorites as $recipe)
                <div class="recipe-card" onclick="window.location='{{ route('recipe.show', $recipe['recipe_id']) }}'">
                    <div class="recipe-card-image">
                        <img src="{{ $recipe['image'] }}" alt="{{ $recipe['name'] }}">
                        <span class="recipe-card-category-favorite">{{ $recipe['category'] ?? '' }}</span>
                    </div>

                    <div class="recipe-card-info">
                        <h3>{{ $recipe['name'] }}</h3>
                        <p class="recipe-card-cuisine">{{ $recipe['cuisine'] ?? '' }}</p>

                        <div class="recipe-card-tags">
                            <span class="recipe-card-tag">{{ $recipe['category'] ?? '' }}</span>
                            <span class="recipe-card-tag">{{ $recipe['cuisine'] ?? '' }}</span>
                        </div>
                    </div>

                    <form action="/favorites/remove" method="POST" class="recipe-card-unfavorite" onclick="event.stopPropagation()">
                        @csrf
                        <input type="hidden" name="recipe_id" value="{{ $recipe['recipe_id'] }}">
                        <button type="submit" class="unfavorite-btn" title="Remove from favorites" onclick="event.stopPropagation()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart">
                                <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"/>
                            </svg>
                        </button>
                    </form>
                </div>
            @empty
                <div class="favorites-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-crack-icon lucide-heart-crack">
                        <path d="M12.409 5.824c-.702.792-1.15 1.496-1.415 2.166l2.153 2.156a.5.5 0 0 1 0 .707l-2.293 2.293a.5.5 0 0 0 0 .707L12 15"/>
                        <path d="M13.508 20.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5a5.5 5.5 0 0 1 9.591-3.677.6.6 0 0 0 .818.001A5.5 5.5 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5z"/>
                    </svg>
                    <p>No favorite recipes yet</p>
                    <p>Start adding recipes to your favorites from the recipe detail page</p>
                    <button onclick="window.location='/home'">Browse Recipes</button>
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection