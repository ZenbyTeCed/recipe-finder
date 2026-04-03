@extends('layouts.app')

@section('content')

<div>
    <div>
        <div>
            <h1>Profile Settings</h1>
            <p>Manage your account and nutritional goals</p>
        </div>
        
        <div>
            <div>
                <h4>Personal Information</h4>
                <p>Update your account details</p>
            </div>
            <div>
                <label name="name" for="fullname">Name</label>
                <input placeholder="">
            </div>
            <div>
                <label name="email" for="email">Email</label>
                <input placeholder="">
                <p>Email cannot be changed</p>
            </div>
        </div>

        <div>
            <div>
                <div>
                    <svg></svg>
                    <h4>Nutritional Goals</h4>
                </div>
                <p>Set your daily macro targets</p>
            </div>
            <div>
                <label name="calorie" for="calorie">Daily Calorie Goal</label>
                <input type="number">
                <p>Recommended: 1500-2500 calories</p>
            </div>
            <div>
                <div>
                    <label>Protein (grams)</label>
                    <input type="number">
                </div>
                <div>
                    <label>Carbs (grams)</label>
                    <input type="number">
                </div>
                <div>
                    <label>Fat (grams)</label>
                    <input type="number">
                </div>
            </div>
            <div>
                <p>💡 Your macro goals should add up to approximately your calorie goal: (150 × 4) + (200 × 4) + (65 × 9) = 1985 calories</p>
            </div>
        </div>

        <div>
            <button>Cancel</button>
            <button>Save Changes</button>
        </div>
    </div>
</div>

@endsection