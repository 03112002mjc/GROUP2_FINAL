document.addEventListener("DOMContentLoaded", function() {
    const passwordField = document.getElementById("password");
    const togglePassword = document.querySelector(".password-toggle-icon i");
    const toggleIconContainer = document.querySelector(".password-toggle-icon");

    // Function to toggle the visibility of the password
    function togglePasswordVisibility() {
        if (passwordField.type === "password") {
            passwordField.type = "text";
            togglePassword.classList.remove("fa-eye");
            togglePassword.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            togglePassword.classList.remove("fa-eye-slash");
            togglePassword.classList.add("fa-eye");
        }
    }

    // Function to show or hide the toggle icon based on input value
    function checkPasswordInput() {
        if (passwordField.value.length > 0) {
            toggleIconContainer.style.display = "block";
        } else {
            toggleIconContainer.style.display = "none";
        }
    }

    
    togglePassword.addEventListener("click", togglePasswordVisibility);
    passwordField.addEventListener("input", checkPasswordInput);

    
    checkPasswordInput();
});

