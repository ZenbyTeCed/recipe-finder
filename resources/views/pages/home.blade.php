@extends('layouts.app')

@section('content')

<div class="home-page">

    <div class="home-page-container">
        <div class="hero-section">
            <h1>Discover Delicious Recipes</h1>
            <p>Search thousands of recipes with detailed nutritional information</p>
        </div>

        <div class="search-section">
            <div class="search-header">
                <h4>Search Recipes</h4>
                <p>Find the perfect recipe for any occasion</p>
            </div>

            <form method="GET" action="{{ route('home') }}" class="search-filters" id="search-form">
                <input
                    type="text"
                    name="query"
                    value="{{ $query ?? '' }}"
                    placeholder="Search by recipe name or ingredient..."
                    id="search-input"
                >

                <div class="filter-options">
                    <div class="filter-option">
                        <label>Category</label>
                        <select name="category" onchange="document.getElementById('search-form').submit()">
                            <option value="">All Categories</option>
                            @foreach ($validCategories as $cat)
                                <option value="{{ $cat }}" {{ ($category ?? '') === $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-option">
                        <label>Cuisine</label>
                        <select name="area" onchange="document.getElementById('search-form').submit()">
                            <option value="">All Cuisines</option>
                            @foreach ($validAreas as $a)
                                <option value="{{ $a }}" {{ ($area ?? '') === $a ? 'selected' : '' }}>
                                    {{ $a }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            <div class="results-info">
                <p>Found <span>{{ $totalCount }}</span> recipes</p>
                <div class="results-actions">
                    @if ($query || $category || $area)
                        <a href="{{ route('home') }}" class="clear-filters-btn">Clear Filters</a>
                    @endif
                    @if (!$query && !$category && !$area)
                        <button onclick="handleReload(this)" class="reload-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="results-section">
            @forelse ($recipes as $recipe)
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
            @empty
                <div class="no-results">
                    <p>No recipes found. Try a different search or filter.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>

<script>
    let debounceTimer;
    document.getElementById('search-input').addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            document.getElementById('search-form').submit();
        }, 600);
    });

    function handleReload(btn) {
        btn.classList.add('spinning');
        setTimeout(function () {
            window.location.href = '{{ route('home') }}';
        }, 600); // waits for spin to finish before navigating
    }
</script>

@endsection