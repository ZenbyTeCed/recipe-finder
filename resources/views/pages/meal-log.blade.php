@extends('layouts.app')

@section('content')

<div class="meal-log-page">
    <div class="meal-log-container">

        <div class="ml-header">
            <div>
                <h1>Meal Log</h1>
                <p>Track all your logged meals and nutrition</p>
            </div>
        </div>

        <div class="ml-summary">
            <div class="ml-summary-header">
                <h4>Summary</h4>
                <p>Total nutrition for today</p>
            </div>
            <div class="ml-summary-cards">
                <div class="ml-summary-card ml-calories">
                    <p>Calories</p>
                    <span>450</span>
                </div>
                <div class="ml-summary-card ml-protein">
                    <p>Protein</p>
                    <span>35g</span>
                </div>
                <div class="ml-summary-card ml-carbs">
                    <p>Carbs</p>
                    <span>28g</span>
                </div>
                <div class="ml-summary-card ml-fat">
                    <p>Fat</p>
                    <span>22g</span>
                </div>
            </div>
        </div>

        <div class="ml-tabs">
            <button class="ml-tab active">Today</button>
            <button class="ml-tab">This Week</button>
            <button class="ml-tab">All Time</button>
        </div>

        <div class="ml-log-section">
            <div class="ml-log-header">
                <div class="ml-log-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                    <h3>Today</h3>
                </div>
                <span class="ml-log-total">450 cal</span>
            </div>

            <div class="ml-log-entries">
                <div class="ml-log-entry">
                    <div class="ml-entry-info">
                        <h4>Tuna Nicoise</h4>
                        <p>1 serving</p>
                        <div class="ml-entry-macros">
                            <div class="ml-entry-macro">
                                <p>Calories</p>
                                <span>450</span>
                            </div>
                            <div class="ml-entry-macro">
                                <p>Protein</p>
                                <span>35g</span>
                            </div>
                            <div class="ml-entry-macro">
                                <p>Carbs</p>
                                <span>28g</span>
                            </div>
                            <div class="ml-entry-macro">
                                <p>Fat</p>
                                <span>22g</span>
                            </div>
                        </div>
                    </div>
                    <button class="ml-delete-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection