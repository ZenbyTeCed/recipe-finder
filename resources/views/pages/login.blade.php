@extends('layouts.default')

@section('content')

<div class="login-page">
    <div>

        <div>
            <img>
            <h1>WellCook</h1>
        </div>

        <div>
            <p>
                Discover Delicious Recipes with Complete Nutrition Info
            </p>
            <p>
                Search thousands of recipes, track your daily nutrition goals, and maintain a healthy lifestyle with our comprehensive meal planning platform.
            </p>
        </div>

        <div>
            <div>
                <img>
                <h3>Recipe Search</h3>
                <p>Filter by ingredients, cuisine, and dietary needs</p>
            </div>

            <div>
                <img>
                <h3>Nutrition Tracking</h3>
                <p>Detailed macros and calorie information</p>
            </div>
            
            <div>
                <img>
                <h3>Goal Dashboard</h3>
                <p>Track daily progress toward your targets</p>
            </div>

            <div>
                <img>
                <h3>Meal Logging</h3>
                <p>Keep a history of everything you eat</p>
            </div>
        </div>

    </div>

    <div>
        <div>
            <h3>Welcome Back</h3>
            <p>Enter your credentials to access you account</p>
            <form>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Login</button>
            </form>

            <div>
                <hr>
                <p>OR</p>
                <hr>
            </div>

            <button>
                Login with Google
            </button>

            <div>
                <p>Don't have an account? <a href="/register">Create Account</a></p>
            </div>
        </div>
    </div>
</div>

@endsection