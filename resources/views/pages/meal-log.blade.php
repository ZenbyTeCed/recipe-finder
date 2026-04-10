@extends('layouts.app')

@section('content')

<div class="meal-log-page">
    <div class="meal-log-container">

        <div class="ml-header">
            <div>
                <h1>Meal Log</h1>
                <p>Track all your logged meals and nutrition</p>
            </div>
            <div class="ml-header-btn">
                <button class="ml-add-btn" id="mlAddBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    Log a Meal
                </button>
            </div>
        </div>

        <div class="ml-summary">
            <div class="ml-summary-header">
                <h4>Summary</h4>
                <p id="summaryText">Total nutrition for today</p>
            </div>
            <div class="ml-summary-cards">
                <div class="ml-summary-card ml-calories">
                    <p>Calories</p>
                    <span>{{ $totals['calories'] }}</span>
                </div>
                <div class="ml-summary-card ml-protein">
                    <p>Protein</p>
                    <span>{{ $totals['protein'] }}g</span>
                </div>
                <div class="ml-summary-card ml-carbs">
                    <p>Carbs</p>
                    <span>{{ $totals['carbs'] }}g</span>
                </div>
                <div class="ml-summary-card ml-fat">
                    <p>Fat</p>
                    <span>{{ $totals['fat'] }}g</span>
                </div>
            </div>
        </div>
        
        <div class="ml-tabs-filter">
            <div class="ml-tabs">
                <button class="ml-tab active">Today</button>
                <button class="ml-tab">This Week</button>
                <button class="ml-tab">All Time</button>
            </div>
            <div class="ml-filter">
                <input type="date" id="mlDateInput">
            </div>
        </div>

        <div class="ml-log-section">
            <div class="ml-log-header">
                <div class="ml-log-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                    <h3>Today</h3>
                    <span class="ml-log-total">{{ $totals['calories'] }} cal</span>
                </div>
            </div>

            <div class="ml-action-bar" id="mlActionBar" style="display: none; gap: 10px; margin-bottom: 15px;">
                <button class="ml-select-btn" id="mlSelectBtn">Select All</button>
                <button class="ml-delete-selected-btn" id="mlDeleteSelectedBtn">Delete Selected</button>
                <button class="ml-cancel-btn" id="mlCancelBtn">Cancel</button>
                <span id="mlSelectedCount" style="margin-left: auto; padding-top: 8px; font-weight: 500;"></span>
            </div>

            <div class="ml-log-entries" id="mlLogEntries">
                @forelse ($meals as $meal)
                    <div class="ml-log-entry" data-meal-key="{{ $meal['key'] }}">
                        <input type="checkbox" class="ml-entry-checkbox" style="display: none; margin-right: 10px;">
                        <div class="ml-entry-info">
                            <h4>{{ $meal['name'] ?? 'Unnamed'}}</h4>
                            <p>{{ $meal['serving'] ?? 'No Serving' }}</p>
                            <div class="ml-entry-macros">
                                <div class="ml-entry-macro">
                                    <p>Calories</p>
                                    <span>{{ $meal['calories'] }}</span>
                                </div>
                                <div class="ml-entry-macro">
                                    <p>Protein</p>
                                    <span>{{ $meal['protein'] }}g</span>
                                </div>
                                <div class="ml-entry-macro">
                                    <p>Carbs</p>
                                    <span>{{ $meal['carbs'] }}g</span>
                                </div>
                                <div class="ml-entry-macro">
                                    <p>Fat</p>
                                    <span>{{ $meal['fat'] }}g</span>
                                </div>
                            </div>
                        </div>
                        <div class="ml-update-delete">
                            <button class="ml-update-btn meal-edit-btn" data-meal-key="{{ $meal['key'] }}" data-meal-name="{{ $meal['name'] }}" data-meal-serving="{{ $meal['serving'] }}" data-meal-type="{{ $meal['meal_type'] ?? 'Lunch' }}" data-meal-calories="{{ $meal['calories'] }}" data-meal-protein="{{ $meal['protein'] }}" data-meal-carbs="{{ $meal['carbs'] }}" data-meal-fat="{{ $meal['fat'] }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                            </button>
                            <form action="/meal-log/delete" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="key" value="{{ $meal['key'] }}">
                                <button type="submit" class="ml-delete-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="ml-empty">
                        <p>No meals logged today. Start logging your meals!</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<!-- Custom Meal Log Modal -->
<div class="ml-modal-overlay" id="mlModalOverlay">
    <div class="ml-modal">
        <div class="ml-modal-header">
            <div>
                <h3>Log a Meal</h3>
                <p>Add your meal details manually</p>
            </div>
            <button class="ml-modal-close" id="mlModalClose">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>

        <form class="ml-modal-form" id="mlModalForm" action="/meal-log/store" method="POST">
            @csrf
            <div class="ml-modal-field">
                <label for="ml-name">Meal Name</label>
                <input type="text" id="ml-name" name="name" placeholder="e.g. Chicken Adobo" required>
            </div>
            <div class="ml-modal-field">
                <label for="ml-serving">Serving Size</label>
                <input type="text" id="ml-serving" name="serving" placeholder="e.g. 1 cup, 1 plate" required>
            </div>
            <div class="ml-modal-field">
                <label for="ml-mealtype">Meal Type</label>
                <select id="ml-mealtype" name="meal_type">
                    <option value="Breakfast">Breakfast</option>
                    <option value="Lunch" selected>Lunch</option>
                    <option value="Dinner">Dinner</option>
                    <option value="Snack">Snack</option>
                </select>
            </div>
            <div class="ml-modal-macros">
                <div class="ml-modal-field">
                    <label for="ml-calories">Calories</label>
                    <input type="number" id="ml-calories" name="calories" placeholder="0" min="0" required>
                </div>
                <div class="ml-modal-field">
                    <label for="ml-protein">Protein (g)</label>
                    <input type="number" id="ml-protein" name="protein" placeholder="0" min="0" required>
                </div>
                <div class="ml-modal-field">
                    <label for="ml-carbs">Carbs (g)</label>
                    <input type="number" id="ml-carbs" name="carbs" placeholder="0" min="0" required>
                </div>
                <div class="ml-modal-field">
                    <label for="ml-fat">Fat (g)</label>
                    <input type="number" id="ml-fat" name="fat" placeholder="0" min="0" required>
                </div>
            </div>
            <div class="ml-modal-nutribot-hint">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg>
                <p>Not sure about the macros? Ask <button type="button" class="ml-nutribot-link" id="mlNutribotLink">NutriBot</button> — just tell it the meal name and serving size!</p>
            </div>
            <button type="submit" class="ml-modal-submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Log Meal
            </button>
        </form>
    </div>
</div>

<!-- Edit Meal Modal -->
<div class="ml-modal-overlay" id="mlEditModalOverlay">
    <div class="ml-modal">
        <div class="ml-modal-header">
            <div>
                <h3>Edit Meal</h3>
                <p>Update your meal details</p>
            </div>
            <button class="ml-modal-close" id="mlEditModalClose">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>

        <form class="ml-modal-form" id="mlEditModalForm">
            @csrf
            <input type="hidden" id="ml-edit-key" name="key">
            <div class="ml-modal-field">
                <label for="ml-edit-name">Meal Name</label>
                <input type="text" id="ml-edit-name" name="name" placeholder="e.g. Chicken Adobo" required>
            </div>
            <div class="ml-modal-field">
                <label for="ml-edit-serving">Serving Size</label>
                <input type="text" id="ml-edit-serving" name="serving" placeholder="e.g. 1 cup, 1 plate" required>
            </div>
            <div class="ml-modal-field">
                <label for="ml-edit-mealtype">Meal Type</label>
                <select id="ml-edit-mealtype" name="meal_type">
                    <option value="Breakfast">Breakfast</option>
                    <option value="Lunch">Lunch</option>
                    <option value="Dinner">Dinner</option>
                    <option value="Snack">Snack</option>
                </select>
            </div>
            <div class="ml-modal-macros">
                <div class="ml-modal-field">
                    <label for="ml-edit-calories">Calories</label>
                    <input type="number" id="ml-edit-calories" name="calories" placeholder="0" min="0" required>
                </div>
                <div class="ml-modal-field">
                    <label for="ml-edit-protein">Protein (g)</label>
                    <input type="number" id="ml-edit-protein" name="protein" placeholder="0" min="0" required>
                </div>
                <div class="ml-modal-field">
                    <label for="ml-edit-carbs">Carbs (g)</label>
                    <input type="number" id="ml-edit-carbs" name="carbs" placeholder="0" min="0" required>
                </div>
                <div class="ml-modal-field">
                    <label for="ml-edit-fat">Fat (g)</label>
                    <input type="number" id="ml-edit-fat" name="fat" placeholder="0" min="0" required>
                </div>
            </div>
            <button type="submit" class="ml-modal-submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Update Meal
            </button>
        </form>
    </div>
</div>

@push('scripts')
    @vite('resources/js/meal-log.js')
@endpush