@extends('layouts.app')

@section('content')

<div class="favorites-page">
    <div class="favorites-page-container">

        <div class="favorites-header">
            <h1>My Favorites</h1>
            <p><span>1</span> saved recipe</p>
        </div>

        <div class="results-section">
            <div class="recipe-card">

                {{-- Image --}}
                <div class="recipe-card-image">
                    <img src="https://placehold.co/400x250" alt="Tuna Nicoise">
                    <span class="recipe-card-category-favorite">Seafood</span>
                </div>

                {{-- Info --}}
                <div class="recipe-card-info">
                    <h3>Tuna Nicoise</h3>
                    <p class="recipe-card-cuisine">French Cuisine</p>

                    {{-- Time + Calories --}}
                    <div class="recipe-card-meta">
                        <span class="recipe-card-time">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            25 min
                        </span>
                        <span class="recipe-card-calories">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4"/>
                            </svg>
                            450 cal
                        </span>
                    </div>

                    {{-- Tags --}}
                    <div class="recipe-card-tags">
                        <span class="recipe-card-tag">High Protein</span>
                        <span class="recipe-card-tag">Gluten Free</span>
                    </div>

                    {{-- Nutrition --}}
                    <div class="recipe-card-nutrition">
                        <div class="recipe-card-nutrition-item">
                            <span class="recipe-card-nutrition-label">Protein</span>
                            <span class="recipe-card-nutrition-value">35g</span>
                        </div>
                        <div class="recipe-card-nutrition-item">
                            <span class="recipe-card-nutrition-label">Carbs</span>
                            <span class="recipe-card-nutrition-value">28g</span>
                        </div>
                        <div class="recipe-card-nutrition-item">
                            <span class="recipe-card-nutrition-label">Fat</span>
                            <span class="recipe-card-nutrition-value">22g</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection