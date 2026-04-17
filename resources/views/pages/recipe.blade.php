@extends('layouts.app')

@section('content')

<div class="recipe-detail-page">
    <div class="recipe-detail-container">

        <a href="/home" class="rd-back-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Back to Search
        </a>

        <div class="rd-hero">
            <div class="rd-hero-image">
                <img src="{{ $meal['strMealThumb'] }}" alt="{{ $meal['strMeal'] }}">
            </div>
            <div class="rd-hero-info">
                <div class="rd-hero-title">
                    <div>
                        <h1>{{ $meal['strMeal'] }}</h1>
                        <p>{{ $meal['strArea'] ?? '' }} · {{ $meal['strCategory'] ?? '' }}</p>
                    </div>
                    @if (session()->has('firebase_uid'))
                        <form id="favoriteForm" action="{{ $isFavorited ? '/favorites/remove' : '/favorites/add' }}" method="POST">
                            @csrf
                            <input type="hidden" name="recipe_id" value="{{ $id }}">
                            <input type="hidden" name="name" value="{{ $meal['strMeal'] }}">
                            <input type="hidden" name="cuisine" value="{{ $meal['strArea'] ?? '' }} Cuisine">
                            <input type="hidden" name="category" value="{{ $meal['strCategory'] ?? '' }}">
                            <input type="hidden" name="time" value="{{ $cookTime ?? 0 }}">
                            <input type="hidden" name="calories" value="{{ $nutrition['calories'] ?? 0 }}">
                            <input type="hidden" name="protein" value="{{ $nutrition['protein'] ?? 0 }}">
                            <input type="hidden" name="carbs" value="{{ $nutrition['carbs'] ?? 0 }}">
                            <input type="hidden" name="fat" value="{{ $nutrition['fat'] ?? 0 }}">
                            <input type="hidden" name="image" value="{{ $meal['strMealThumb'] }}">

                            <button
                                type="submit"
                                class="rd-bookmark-btn {{ $isFavorited ? 'active' : '' }}"
                                id="favoriteBtn"
                                title="{{ $isFavorited ? 'Remove from favorites' : 'Add to favorites' }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    fill="{{ $isFavorited ? 'currentColor' : 'none' }}"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"/>
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>

                @if (!empty($tags))
                <div class="rd-tags">
                    @foreach ($tags as $tag)
                        <span class="rd-tag">{{ $tag }}</span>
                    @endforeach
                </div>
                @endif

                <div class="rd-meta">
                    @if ($cookTime)
                    <div class="rd-meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span>{{ $cookTime }} minutes</span>
                    </div>
                    @endif
                    @if ($hasRealNutrition)
                        <div class="rd-meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4"/></svg>
                            <span>{{ $nutrition['calories'] }} calories</span>
                        </div>
                    @endif
                </div>

                @if (session()->has('firebase_uid'))
                    <button
                        type="button"
                        class="rd-log-btn"
                        id="logMealBtn"
                        data-has-real-nutrition="{{ ($shouldForceManualLog ?? false) ? '0' : '1' }}"
                        title="{{ ($shouldForceManualLog ?? false) ? 'Nutrition data unavailable. You can log this meal manually.' : '' }}"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.4 15.63a7.875 6 135 1 1 6.23-6.23 4.5 3.43 135 0 0-6.23 6.23"/><path d="m8.29 12.71-2.6 2.6a2.5 2.5 0 1 0-1.65 4.65A2.5 2.5 0 1 0 8.7 18.3l2.59-2.59"/></svg>
                            Log This Meal
                    </button>
                @else
                    <a href="/meal-log" class="rd-log-btn" style="text-decoration: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.4 15.63a7.875 6 135 1 1 6.23-6.23 4.5 3.43 135 0 0-6.23 6.23"/><path d="m8.29 12.71-2.6 2.6a2.5 2.5 0 1 0-1.65 4.65A2.5 2.5 0 1 0 8.7 18.3l2.59-2.59"/></svg>
                        Log This Meal
                    </a>
                @endif
            </div>
        </div>

        @if ($hasRealNutrition)
        <div class="rd-nutrition-card">
            <div class="rd-card-header">
                <h4>Nutritional Information</h4>
                <p>
                    @if(!empty($isEstimatedNutrition))
                        Estimated per serving
                    @else
                        Per serving — powered by Spoonacular
                    @endif
                </p>
            </div>
            @if($nutritionMessage)
                <div style="background: #fef3c7; color: #92400e; padding: 10px; border-radius: 6px; margin-bottom: 10px; font-size: 14px;">
                    {{ $nutritionMessage }}

                    @if(!empty($isEstimatedNutrition))
                        <br><br>
                        @if (session()->has('firebase_uid'))
                            <button
                                type="button"
                                id="mlLogMealButton"
                                style="background: none; border: none; color: #ea580c; cursor: pointer; font-weight: 600; text-decoration: underline;">
                                Log this meal manually instead
                            </button>
                        @else
                            <a href="/meal-log" style="color: #ea580c; font-weight: 600;">Log in to track this meal</a>
                        @endif
                    @endif
                </div>
            @endif
            <div class="rd-nutrition-stats">
                <div class="rd-nutrition-stat">
                    <p>Calories</p>
                    <span>{{ $nutrition['calories'] }}</span>
                    <p>kcal</p>
                </div>
                <div class="rd-nutrition-stat">
                    <p>Protein</p>
                    <span>{{ $nutrition['protein'] }}</span>
                    <p>grams</p>
                </div>
                <div class="rd-nutrition-stat">
                    <p>Carbs</p>
                    <span>{{ $nutrition['carbs'] }}</span>
                    <p>grams</p>
                </div>
                <div class="rd-nutrition-stat">
                    <p>Fat</p>
                    <span>{{ $nutrition['fat'] }}</span>
                    <p>grams</p>
                </div>
                <div class="rd-nutrition-stat">
                    <p>Fiber</p>
                    <span>{{ $nutrition['fiber'] }}</span>
                    <p>grams</p>
                </div>
            </div>
            <div class="rd-nutrition-bars">
                <div class="rd-bar-item">
                    <div class="rd-bar-label"><span>Protein</span><span>{{ $nutrition['protein_pct'] }}%</span></div>
                    <div class="rd-bar-track"><div class="rd-bar-fill" style="width: {{ $nutrition['protein_pct'] }}%"></div></div>
                </div>
                <div class="rd-bar-item">
                    <div class="rd-bar-label"><span>Carbs</span><span>{{ $nutrition['carbs_pct'] }}%</span></div>
                    <div class="rd-bar-track"><div class="rd-bar-fill" style="width: {{ $nutrition['carbs_pct'] }}%"></div></div>
                </div>
                <div class="rd-bar-item">
                    <div class="rd-bar-label"><span>Fat</span><span>{{ $nutrition['fat_pct'] }}%</span></div>
                    <div class="rd-bar-track"><div class="rd-bar-fill" style="width: {{ $nutrition['fat_pct'] }}%"></div></div>
                </div>
            </div>
        </div>
        @else
        <div class="rd-nutrition-card">
            <div class="rd-card-header">
                <h4>Nutritional Information</h4>
                <p>{{ !empty($isEstimatedNutrition) ? 'Estimated values available' : 'Not available for this recipe' }}</p>
            </div>
            <p style="color: #6b7280; font-size: 14px;">
                @if($nutritionMessage)
                    {{ $nutritionMessage }}
                @endif
                @if (session()->has('firebase_uid'))
                    <button type="button" id="mlLogMealButton" style="background: none; border: none; color: #ea580c; cursor: pointer; font-weight: 600; text-decoration: underline;">Log this meal manually</button>
                @else
                    <a href="/meal-log" style="color: #ea580c; font-weight: 600;">Log in to track this meal</a>
                @endif
                @if(empty($isEstimatedNutrition) && session()->has('firebase_uid'))
                    or ask NutriBot for an estimate!
                @endif
            </p>
        </div>
        @endif

        <div class="rd-ingredients-card">
            <div class="rd-card-header">
                <h4>Ingredients</h4>
            </div>
            <ul class="rd-ingredients-list">
                @foreach ($ingredients as $ingredient)
                    <li><span class="rd-bullet">•</span><span>{{ $ingredient }}</span></li>
                @endforeach
            </ul>
        </div>

        <div class="rd-instructions-card">
            <div class="rd-card-header">
                <h4>Instructions</h4>
            </div>
            @php
                $steps = preg_split('/\r\n|\r|\n|\./', $meal['strInstructions']);
            @endphp

            <ol class="rd-instructions-list">
                @foreach ($steps as $step)
                    @if (trim($step) !== '')
                        <li>{{ trim($step) }}</li>
                    @endif
                @endforeach
            </ol>
        </div>

    </div>
</div>

@if (session()->has('firebase_uid'))
<!-- Log Meal Modal -->
<div class="modal-overlay" id="logMealOverlay">
    <div class="modal">
        <div class="modal-header">
            <div>
                <h3>Log Meal</h3>
                <p>How many servings did you have?</p>
            </div>
            <button class="modal-close" id="modalCloseBtn">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <label for="servings">Servings</label>
            <input type="number" id="servings" value="1" min="0.5" step="0.5">
            <div class="modal-nutrition">
                <p><strong>Total Calories:</strong> <span id="modalCalories">{{ $nutrition['calories'] ?? 0 }}</span> cal</p>
                <p><strong>Protein:</strong> <span id="modalProtein">{{ $nutrition['protein'] ?? 0 }}</span>g</p>
                <p><strong>Carbs:</strong> <span id="modalCarbs">{{ $nutrition['carbs'] ?? 0 }}</span>g</p>
                <p><strong>Fat:</strong> <span id="modalFat">{{ $nutrition['fat'] ?? 0 }}</span>g</p>
            </div>
        </div>
        <form action="/meal-log/store" method="POST" id="logMealForm">
            @csrf
            <input type="hidden" name="name" value="{{ $meal['strMeal'] }}">
            <input type="hidden" name="serving" id="servingInput" value="1 serving">
            <input type="hidden" name="calories" id="caloriesInput" value="{{ $nutrition['calories'] ?? 0 }}">
            <input type="hidden" name="protein" id="proteinInput" value="{{ $nutrition['protein'] ?? 0 }}">
            <input type="hidden" name="carbs" id="carbsInput" value="{{ $nutrition['carbs'] ?? 0 }}">
            <input type="hidden" name="fat" id="fatInput" value="{{ $nutrition['fat'] ?? 0 }}">
            <button type="submit" class="modal-log-btn">Log Meal</button>
        </form>
    </div>
</div>

<!-- Manual Meal Logging Modal (shown when Spoonacular fails) -->
<div class="ml-modal-overlay" id="rdMealLogModalOverlay">
    <div class="ml-modal">
        <div class="ml-modal-header">
            <div>
                <h3>Log a Meal</h3>
                <p>Add your meal details manually</p>
            </div>
            <button class="ml-modal-close" id="rdMealLogModalClose">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>

        <form class="ml-modal-form" id="rdMealLogModalForm" action="/meal-log/store" method="POST">
            @csrf
            <div class="ml-modal-field">
                <label for="rd-meal-name">Meal Name</label>
                <input type="text" id="rd-meal-name" name="name" value="{{ $meal['strMeal'] }}" required readonly style="background-color: #f3f3f5; cursor: not-allowed;">
            </div>
            <div class="ml-modal-field">
                <label for="rd-meal-serving">Serving Size</label>
                <input type="text" id="rd-meal-serving" name="serving" placeholder="e.g. 1 cup, 1 plate" required>
            </div>
            <div class="ml-modal-field">
                <label for="rd-meal-type">Meal Type</label>
                <select id="rd-meal-type" name="meal_type">
                    <option value="Breakfast">Breakfast</option>
                    <option value="Lunch" selected>Lunch</option>
                    <option value="Dinner">Dinner</option>
                    <option value="Snack">Snack</option>
                </select>
            </div>
            <div class="ml-modal-macros">
                <div class="ml-modal-field">
                    <label for="rd-meal-calories">Calories</label>
                    <input type="number" id="rd-meal-calories" name="calories" placeholder="0" min="0" required>
                </div>
                <div class="ml-modal-field">
                    <label for="rd-meal-protein">Protein (g)</label>
                    <input type="number" id="rd-meal-protein" name="protein" placeholder="0" min="0" required>
                </div>
                <div class="ml-modal-field">
                    <label for="rd-meal-carbs">Carbs (g)</label>
                    <input type="number" id="rd-meal-carbs" name="carbs" placeholder="0" min="0" required>
                </div>
                <div class="ml-modal-field">
                    <label for="rd-meal-fat">Fat (g)</label>
                    <input type="number" id="rd-meal-fat" name="fat" placeholder="0" min="0" required>
                </div>
            </div>
            <div class="ml-modal-nutribot-hint">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg>
                <p>Not sure about the macros? Ask <button type="button" class="ml-nutribot-link" id="rdNutribotLink">NutriBot</button> — just tell it the meal name and serving size!</p>
            </div>
            <button type="submit" class="ml-modal-submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Log Meal
            </button>
        </form>
    </div>
</div>

@push('scripts')
@vite('resources/js/recipe.js')
@endpush
@endif

@endsection
