// Firebase is now initialized in the layout, no need to initialize here

// Google Login Button
const googleLoginBtn = document.getElementById('google-login-btn');
if (googleLoginBtn) {
    googleLoginBtn.addEventListener('click', async () => {
        try {
            const provider = new firebase.auth.GoogleAuthProvider();
            const result = await firebase.auth().signInWithPopup(provider);
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