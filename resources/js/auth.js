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

const googleAuthProvider = () => {
    const provider = new window.firebase.auth.GoogleAuthProvider();
    provider.setCustomParameters({ prompt: 'select_account' });
    return provider;
};

const sendGoogleTokenToLaravel = async (idToken) => {
    const response = await fetch('/auth/google', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ id_token: idToken }),
    });

    const data = await response.json();

    if (!response.ok) {
        throw new Error(data.error || 'Google login was rejected by the server.');
    }

    if (data.redirect) {
        window.location.href = data.redirect;
    }
};

const finishRedirectLogin = async () => {
    const googleLoginBtn = document.getElementById('google-login-btn');
    if (!googleLoginBtn) {
        return;
    }

    try {
        const auth = getFirebaseAuth();
        const result = await auth.getRedirectResult();

        if (!result?.user) {
            return;
        }

        const idToken = await result.user.getIdToken();
        await sendGoogleTokenToLaravel(idToken);
    } catch (error) {
        console.error('Google redirect login error:', {
            code: error?.code,
            message: error?.message,
            customData: error?.customData,
            fullError: error,
        });
        alert('Failed to login with Google: ' + (error?.message || 'Unknown error'));
    }
};

// Google Login Button
const googleLoginBtn = document.getElementById('google-login-btn');
if (googleLoginBtn) {
    finishRedirectLogin();

    googleLoginBtn.addEventListener('click', async () => {
        try {
            const auth = getFirebaseAuth();
            const provider = googleAuthProvider();
            const result = await auth.signInWithPopup(provider);
            const idToken = await result.user.getIdToken();
            await sendGoogleTokenToLaravel(idToken);
        } catch (error) {
            console.error('Google login error:', {
                code: error?.code,
                message: error?.message,
                customData: error?.customData,
                fullError: error,
            });

            if (error?.code === 'auth/internal-error' || error?.code === 'auth/popup-blocked') {
                try {
                    const auth = getFirebaseAuth();
                    await auth.signInWithRedirect(googleAuthProvider());
                    return;
                } catch (redirectError) {
                    console.error('Google redirect fallback error:', {
                        code: redirectError?.code,
                        message: redirectError?.message,
                        customData: redirectError?.customData,
                        fullError: redirectError,
                    });
                    alert('Failed to login with Google: ' + (redirectError?.message || 'Unknown error'));
                    return;
                }
            }

            alert('Failed to login with Google: ' + (error?.message || 'Unknown error'));
        }
    });
}
