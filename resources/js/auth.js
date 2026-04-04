firebase.initializeApp(firebaseConfig);

document.getElementById('google-login-btn').addEventListener('click', async () => {
    const provider = new firebase.auth.GoogleAuthProvider();
    try {
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
        console.error(error);
    }
});