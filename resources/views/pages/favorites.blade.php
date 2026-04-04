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
            <div class="recipe-card">
                <div class="recipe-card-image" onclick="window.location='/recipe'">
                    <img src="{{ $recipe['image'] }}" alt="{{ $recipe['name'] }}">
                    <span class="recipe-card-category-favorite">{{ $recipe['category'] }}</span>
                </div>
                <div class="recipe-card-info" onclick="window.location='/recipe'">
                    <h3>{{ $recipe['name'] }}</h3>
                    <p class="recipe-card-cuisine">{{ $recipe['cuisine'] }}</p>
                    <div class="recipe-card-meta">
                        <span class="recipe-card-time">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            {{ $recipe['time'] }} min
                        </span>
                        <span class="recipe-card-calories">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flame-icon lucide-flame"><path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4"/></svg>
                            {{ $recipe['calories'] }} cal
                        </span>
                    </div>
                    <div class="recipe-card-nutrition">
                        <div class="recipe-card-nutrition-item">
                            <span class="recipe-card-nutrition-label">Protein</span>
                            <span class="recipe-card-nutrition-value">{{ $recipe['protein'] }}g</span>
                        </div>
                        <div class="recipe-card-nutrition-item">
                            <span class="recipe-card-nutrition-label">Carbs</span>
                            <span class="recipe-card-nutrition-value">{{ $recipe['carbs'] }}g</span>
                        </div>
                        <div class="recipe-card-nutrition-item">
                            <span class="recipe-card-nutrition-label">Fat</span>
                            <span class="recipe-card-nutrition-value">{{ $recipe['fat'] }}g</span>
                        </div>
                    </div>
                </div>
                <form action="/favorites/remove" method="POST" class="recipe-card-unfavorite">
                    @csrf
                    <input type="hidden" name="recipe_id" value="{{ $recipe['recipe_id'] }}">
                    <button type="submit" class="unfavorite-btn" title="Remove from favorites">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart"><path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"/></svg>
                    </button>
                </form>
            </div>
        @empty
            <div class="ml-empty">
                <p>No favorites yet. Start saving recipes you love!</p>
            </div>
        @endforelse
    </div>
    </div>
</div>

@endsection