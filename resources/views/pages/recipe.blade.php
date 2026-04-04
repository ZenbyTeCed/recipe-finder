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
                <img src="https://placehold.co/480x350" alt="Tuna Nicoise">
            </div>
            <div class="rd-hero-info">
                <div class="rd-hero-title">
                    <div>
                        <h1>Tuna Nicoise</h1>
                        <p>French · Seafood</p>
                    </div>
                    <form id="favoriteForm" action="/favorites/add" method="POST">
                        @csrf
                        <input type="hidden" name="recipe_id" value="tuna-nicoise">
                        <input type="hidden" name="name" value="Tuna Nicoise">
                        <input type="hidden" name="cuisine" value="French Cuisine">
                        <input type="hidden" name="category" value="Seafood">
                        <input type="hidden" name="time" value="25">
                        <input type="hidden" name="calories" value="450">
                        <input type="hidden" name="protein" value="35">
                        <input type="hidden" name="carbs" value="28">
                        <input type="hidden" name="fat" value="22">
                        <input type="hidden" name="image" value="https://placehold.co/480x350">
                        <button type="submit" class="rd-bookmark-btn" id="favoriteBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"/></svg>
                        </button>
                    </form>
                </div>
                <div class="rd-tags">
                    <span class="rd-tag">High Protein</span>
                    <span class="rd-tag">Gluten Free</span>
                    <span class="rd-tag">Pescatarian</span>
                </div>
                <div class="rd-meta">
                    <div class="rd-meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span>25 minutes</span>
                    </div>
                    <div class="rd-meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4"/></svg>
                        <span>450 calories</span>
                    </div>
                </div>
                <form action="/meal-log/store" method="POST">
                    @csrf
                    <input type="hidden" name="name" value="Tuna Nicoise">
                    <input type="hidden" name="serving" value="1 serving">
                    <input type="hidden" name="calories" value="450">
                    <input type="hidden" name="protein" value="35">
                    <input type="hidden" name="carbs" value="28">
                    <input type="hidden" name="fat" value="22">
                    <button type="submit" class="rd-log-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.4 15.63a7.875 6 135 1 1 6.23-6.23 4.5 3.43 135 0 0-6.23 6.23"/><path d="m8.29 12.71-2.6 2.6a2.5 2.5 0 1 0-1.65 4.65A2.5 2.5 0 1 0 8.7 18.3l2.59-2.59"/></svg>
                        Log This Meal
                    </button>
                </form>
            </div>
        </div>

        <div class="rd-nutrition-card">
            <div class="rd-card-header">
                <h4>Nutritional Information</h4>
                <p>Per serving</p>
            </div>
            <div class="rd-nutrition-stats">
                <div class="rd-nutrition-stat">
                    <p>Calories</p>
                    <span>450</span>
                    <p>kcal</p>
                </div>
                <div class="rd-nutrition-stat">
                    <p>Protein</p>
                    <span>35</span>
                    <p>grams</p>
                </div>
                <div class="rd-nutrition-stat">
                    <p>Carbs</p>
                    <span>28</span>
                    <p>grams</p>
                </div>
                <div class="rd-nutrition-stat">
                    <p>Fat</p>
                    <span>22</span>
                    <p>grams</p>
                </div>
                <div class="rd-nutrition-stat">
                    <p>Fiber</p>
                    <span>6</span>
                    <p>grams</p>
                </div>
            </div>
            <div class="rd-nutrition-bars">
                <div class="rd-bar-item">
                    <div class="rd-bar-label"><span>Protein</span><span>31%</span></div>
                    <div class="rd-bar-track"><div class="rd-bar-fill" style="width: 31%"></div></div>
                </div>
                <div class="rd-bar-item">
                    <div class="rd-bar-label"><span>Carbs</span><span>25%</span></div>
                    <div class="rd-bar-track"><div class="rd-bar-fill" style="width: 25%"></div></div>
                </div>
                <div class="rd-bar-item">
                    <div class="rd-bar-label"><span>Fat</span><span>44%</span></div>
                    <div class="rd-bar-track"><div class="rd-bar-fill" style="width: 44%"></div></div>
                </div>
            </div>
        </div>

        <div class="rd-ingredients-card">
            <div class="rd-card-header">
                <h4>Ingredients</h4>
            </div>
            <ul class="rd-ingredients-list">
                <li><span class="rd-bullet">•</span><span>4 eggs</span></li>
                <li><span class="rd-bullet">•</span><span>200g green beans</span></li>
                <li><span class="rd-bullet">•</span><span>2 tuna steaks</span></li>
                <li><span class="rd-bullet">•</span><span>200g cherry tomatoes</span></li>
                <li><span class="rd-bullet">•</span><span>100g black olives</span></li>
                <li><span class="rd-bullet">•</span><span>8 anchovy fillets</span></li>
                <li><span class="rd-bullet">•</span><span>Mixed lettuce</span></li>
                <li><span class="rd-bullet">•</span><span>Olive oil</span></li>
                <li><span class="rd-bullet">•</span><span>Lemon juice</span></li>
            </ul>
        </div>

        <div class="rd-instructions-card">
            <div class="rd-card-header">
                <h4>Instructions</h4>
            </div>
            <p class="rd-instructions-text">Boil the eggs for 8 minutes until hard-boiled. Cool and peel. Cook green beans in boiling salted water for 4-5 minutes. Drain and refresh in cold water. Arrange lettuce leaves on plates. Top with tuna, eggs, green beans, tomatoes, olives, and anchovies. Drizzle with olive oil and lemon juice. Season with salt and pepper.</p>
        </div>

    </div>
</div>

@push('scripts')
    @vite('resources/js/recipe.js')
@endpush

@endsection