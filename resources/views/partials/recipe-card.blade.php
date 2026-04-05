<div class="recipe-card" onclick="window.location='{{ route('recipe.show', $recipe['id']) }}'">
    <div class="recipe-card-image">
        <img src="{{ $recipe['image'] }}" alt="{{ $recipe['name'] }}">
        <span class="recipe-card-category">{{ $recipe['category'] ?? '' }}</span>
    </div>
    <div class="recipe-card-info">
        <h3>{{ $recipe['name'] }}</h3>
        <p class="recipe-card-cuisine">{{ $recipe['area'] ?? '' }} Cuisine</p>
        <div class="recipe-card-tags">
            <span class="recipe-card-tag">{{ $recipe['category'] ?? '' }}</span>
            <span class="recipe-card-tag">{{ $recipe['area'] ?? '' }}</span>
        </div>
    </div>
</div>