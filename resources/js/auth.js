const getFirebaseAuth = () => {
    if (!window.firebase) {
        throw new Error('Firebase SDK failed to load. Please refresh the page and try again.');
    }

    if (!window.firebaseConfig?.apiKey || !window.firebaseConfig?.authDomain || !window.firebaseConfig?.projectId) {
        throw new Error('Firebase configuration is missing. Check FIREBASE_API_KEY, FIREBASE_AUTH_DOMAIN, and FIREBASE_PROJECT_ID.');
    }

    if (!window.firebase.apps.length) {
        window.firebase.initializeApp(window.firebaseConfig);
    }

    return window.firebase.auth();
};

// Google Login Button
const googleLoginBtn = document.getElementById('google-login-btn');
if (googleLoginBtn) {
    googleLoginBtn.addEventListener('click', async () => {
        try {
            const auth = getFirebaseAuth();
            const provider = new window.firebase.auth.GoogleAuthProvider();
            const result = await auth.signInWithPopup(provider);
            const idToken = await result.user.getIdToken();

            // Send token to Laravel
            const response = await fetch('/auth/google', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ id_token: idToken }),
            });

            const data = await response.json();

            if (data.redirect) {
                window.location.href = data.redirect;
            }
        } catch (error) {
            console.error('Google login error:', error);
            alert('Failed to login with Google: ' + error.message);
        }
    });
}
