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

            <div class="search-filters">
                <input type="text" placeholder="Search by recipe name or ingredient...">

                <div class="filter-options">
                    <div class="filter-option">
                        <label>Category</label>
                        <select name="category">
                            <option value="">All Categories</option>
                            <option value="breakfast">Breakfast</option>
                            <option value="lunch">Lunch</option>
                            <option value="dinner">Dinner</option>
                            <option value="snack">Snack</option>
                            <option value="dessert">Dessert</option>
                        </select>
                    </div>
                    <div class="filter-option">
                        <label>Cuisine</label>
                        <select name="cuisine">
                            <option value="">All Cuisines</option>
                            <option value="italian">Italian</option>
                            <option value="asian">Asian</option>
                            <option value="mexican">Mexican</option>
                            <option value="american">American</option>
                            <option value="french">French</option>
                            <option value="mediterranean">Mediterranean</option>
                            <option value="japanese">Japanese</option>
                            <option value="chinese">Chinese</option>
                            <option value="indian">Indian</option>
                        </select>
                    </div>
                    <div class="filter-option">
                        <label>Max Cook Time</label>
                        <select name="max_time">
                            <option value="">Anytime</option>
                            <option value="15">Under 30 min</option>
                            <option value="30">Under 60 min</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="results-info">
                <p>Found <span>0</span> recipes</p>
                <button>Clear Filters</button>
            </div>
        </div>
        
        <div class="results-section">
            <div class="recipe-card" onclick="window.location='/recipe'">
                <div class="recipe-card-image">
                    <img src="https://placehold.co/400x250" alt="Tuna Nicoise">
                    <span class="recipe-card-category">Seafood</span>
                </div>
                <div class="recipe-card-info"">
                    <h3>Tuna Nicoise</h3>
                    <p class="recipe-card-cuisine">French Cuisine</p>
                    <div class="recipe-card-meta">
                        <span class="recipe-card-time">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            25 min
                        </span>
                        <span class="recipe-card-calories">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flame-icon lucide-flame"><path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4"/></svg>
                            450 cal
                        </span>
                    </div>
                    <div class="recipe-card-tags">
                        <span class="recipe-card-tag">High Protein</span>
                        <span class="recipe-card-tag">Gluten Free</span>
                    </div>
                </div>
            </div>

            <div class="recipe-card" onclick="window.location='/recipe'">
                <div class="recipe-card-image">
                    <img src="https://placehold.co/400x250" alt="Tuna Nicoise">
                    <span class="recipe-card-category">Seafood</span>
                </div>
                <div class="recipe-card-info">
                    <h3>Tuna Nicoise</h3>
                    <p class="recipe-card-cuisine">French Cuisine</p>
                    <div class="recipe-card-meta">
                        <span class="recipe-card-time">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            25 min
                        </span>
                        <span class="recipe-card-calories">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flame-icon lucide-flame"><path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4"/></svg>
                            450 cal
                        </span>
                    </div>
                    <div class="recipe-card-tags">
                        <span class="recipe-card-tag">High Protein</span>
                        <span class="recipe-card-tag">Gluten Free</span>
                    </div>
                </div>
            </div>

            <div class="recipe-card" onclick="window.location='/recipe'">
                <div class="recipe-card-image">
                    <img src="https://placehold.co/400x250" alt="Tuna Nicoise">
                    <span class="recipe-card-category">Seafood</span>
                </div>
                <div class="recipe-card-info">
                    <h3>Tuna Nicoise</h3>
                    <p class="recipe-card-cuisine">French Cuisine</p>
                    <div class="recipe-card-meta">
                        <span class="recipe-card-time">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            25 min
                        </span>
                        <span class="recipe-card-calories">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flame-icon lucide-flame"><path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4"/></svg>
                            450 cal
                        </span>
                    </div>
                    <div class="recipe-card-tags">
                        <span class="recipe-card-tag">High Protein</span>
                        <span class="recipe-card-tag">Gluten Free</span>
                    </div>
                </div>
            </div>

            <div class="recipe-card" onclick="window.location='/recipe'">
                <div class="recipe-card-image">
                    <img src="https://placehold.co/400x250" alt="Tuna Nicoise">
                    <span class="recipe-card-category">Seafood</span>
                </div>
                <div class="recipe-card-info">
                    <h3>Tuna Nicoise</h3>
                    <p class="recipe-card-cuisine">French Cuisine</p>
                    <div class="recipe-card-meta">
                        <span class="recipe-card-time">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            25 min
                        </span>
                        <span class="recipe-card-calories">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flame-icon lucide-flame"><path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4"/></svg>
                            450 cal
                        </span>
                    </div>
                    <div class="recipe-card-tags">
                        <span class="recipe-card-tag">High Protein</span>
                        <span class="recipe-card-tag">Gluten Free</span>
                    </div>
                </div>
            </div>

            <div class="recipe-card" onclick="window.location='/recipe'">
                <div class="recipe-card-image">
                    <img src="https://placehold.co/400x250" alt="Tuna Nicoise">
                    <span class="recipe-card-category">Seafood</span>
                </div>
                <div class="recipe-card-info">
                    <h3>Tuna Nicoise</h3>
                    <p class="recipe-card-cuisine">French Cuisine</p>
                    <div class="recipe-card-meta">
                        <span class="recipe-card-time">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            25 min
                        </span>
                        <span class="recipe-card-calories">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flame-icon lucide-flame"><path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4"/></svg>
                            450 cal
                        </span>
                    </div>
                    <div class="recipe-card-tags">
                        <span class="recipe-card-tag">High Protein</span>
                        <span class="recipe-card-tag">Gluten Free</span>
                    </div>
                </div>
            </div>

            <div class="recipe-card" onclick="window.location='/recipe'">
                <div class="recipe-card-image">
                    <img src="https://placehold.co/400x250" alt="Tuna Nicoise">
                    <span class="recipe-card-category">Seafood</span>
                </div>
                <div class="recipe-card-info">
                    <h3>Tuna Nicoise</h3>
                    <p class="recipe-card-cuisine">French Cuisine</p>
                    <div class="recipe-card-meta">
                        <span class="recipe-card-time">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            25 min
                        </span>
                        <span class="recipe-card-calories">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flame-icon lucide-flame"><path d="M12 3q1 4 4 6.5t3 5.5a1 1 0 0 1-14 0 5 5 0 0 1 1-3 1 1 0 0 0 5 0c0-2-1.5-3-1.5-5q0-2 2.5-4"/></svg>
                            450 cal
                        </span>
                    </div>
                    <div class="recipe-card-tags">
                        <span class="recipe-card-tag">High Protein</span>
                        <span class="recipe-card-tag">Gluten Free</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection