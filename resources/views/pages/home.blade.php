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
                @if ($query || $category || $area)
                    <a href="{{ route('home') }}" class="clear-filters-btn">
                        <button>Clear Filters</button>
                    </a>
                @else
                    <button>Clear Filters</button>
                @endif
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
                        <!-- <div class="recipe-card-meta">
                            <span class="recipe-card-time">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                N/A
                            </span>
                            <span class="recipe-card-calories">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flame-icon lucide-flame"><path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4"/></svg>
                                N/A
                            </span>
                        </div> -->
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
    // Auto-submit on typing with debounce
    let debounceTimer;
    document.getElementById('search-input').addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            document.getElementById('search-form').submit();
        }, 600); // waits 600ms after user stops typing
    });
</script>

@endsection