
import { initializeApp } from "firebase/app";

import {
    getAuth,
    GoogleAuthProvider,
    signInWithPopup
} from "firebase/auth";

// Firebase configuration
const firebaseConfig = {
    apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
    authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
    projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID,
    storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET,
    messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID,
    appId: import.meta.env.VITE_FIREBASE_APP_ID,
};

// Initialize Firebase
const firebaseApp = initializeApp(firebaseConfig);

const auth = getAuth(firebaseApp);

const googleProvider = new GoogleAuthProvider();

googleProvider.setCustomParameters({
    prompt: "select_account"
});

// Google Login
document.addEventListener("DOMContentLoaded", () => {
    const googleButton = document.getElementById("firebase-google-login");

    if (!googleButton) {
        return;
    }

    googleButton.addEventListener("click", async () => {
        const originalText = googleButton.innerHTML;

        try {
            googleButton.disabled = true;
            googleButton.innerHTML = "Signing in...";

            // Firebase Google popup
            const result = await signInWithPopup(auth, googleProvider);

            // Get Firebase ID token
            const idToken = await result.user.getIdToken();

            // Get CSRF token
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content");

            if (!csrfToken) {
                throw new Error(
                    "CSRF token not found. Please check guest layout."
                );
            }

            // Send token to Laravel
            const response = await fetch("/auth/firebase", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    id_token: idToken,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(
                    data.message || "Google login failed."
                );
            }

            // Redirect to Laravel response URL
            window.location.href = data.redirect || "/";

        } catch (error) {
            console.error("Firebase Google Login Error:", error);

            alert(
                error.message || "Google login failed. Please try again."
            );

            googleButton.disabled = false;
            googleButton.innerHTML = originalText;
        }
    });
});
