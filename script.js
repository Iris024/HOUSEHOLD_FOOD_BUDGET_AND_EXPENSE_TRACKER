const signInButton = document.getElementById('signInButton');
const signUpButton = document.getElementById('signUpButton');
const registerForm = document.getElementById('Register');
const logInForm = document.getElementById('Log-In');

// Switch to Log-In form when "Sign In" button is clicked
signInButton.addEventListener('click', function() {
    registerForm.style.display = 'none';
    logInForm.style.display = 'block';
});

// Switch to Registration form when "Sign Up" button is clicked
signUpButton.addEventListener('click', function() {
    logInForm.style.display = 'none';
    registerForm.style.display = 'block';
});