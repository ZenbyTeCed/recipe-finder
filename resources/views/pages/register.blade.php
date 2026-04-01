@extends('layouts.app')

@section('content')

<div>
    <div>

        <div>
            <img>
            <h1>WellCook</h1>
        </div>

        <div>
            <p>
                Start Your Healthy Journey Today
            </p>
            <p>
                Join thousands of users who are achieving their nutrition goals with RecipeFinder. Create your free account and get started in seconds.
            </p>
        </div>

        <div>
            <div>
                <div>
                    <img>
                </div>
                <div>
                    <h3>Personalized Goals</h3>
                    <p>Set custom calorie and macro targets based on your fitness objectives</p>
                </div>
            </div>

            <div>
                <div>
                    <img>
                </div>
                <div>
                    <h3>Recipe Discovery</h3>
                    <p>Browse curated recipes filtered by your dietary preferences</p>
                </div>
            </div>

            <div>
                <div>
                    <img>
                </div>
                <div>
                    <h3>Progress Tracking</h3>
                    <p>Monitor your daily intake with beautiful visual dashboards</p>
                </div>
            </div>

            <div>
                <div>
                    <img>
                </div>
                <div>
                    <h3>Meal History</h3>
                    <p>Keep a complete log of all your meals and favorites</p>
                </div>
            </div>
        </div>

    </div>

    <div>
        <div>
            <h3>Create Account</h3>
            <p>Sign up to start tracking your nutrition goals</p>
            <form>
                <label for="fullname" >Full Name</label>
                <input type="text" id="fullname" name="fullname" required>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <button type="submit">Create Account</button>
            </form>

            <div>
                <p>Already have an account? <a href="/login">Login</a></p>
            </div>
        </div>
    </div>
</div>

@endsection