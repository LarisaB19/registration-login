document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("loginForm");
    loginForm.addEventListener("submit", function (event) {
        resetErrorMessages();
        if (!validateForm()) {
            event.preventDefault();
        }
    });
    function resetErrorMessages() {
        const errorMessages = document.querySelectorAll(".error-message");
        errorMessages.forEach((element) => {
            element.style.display = "none";
        });
    }
    function validateForm() {
        let isValid = true;
        const usernameOrEmail = document.getElementById("username_or_email").value;
        const password = document.getElementById("password").value;
        if (usernameOrEmail.trim() === "") {
            displayErrorMessage("username-email-error", "Username/Email is required.");
            isValid = false;
        }
        if (password.trim() === "") {
            displayErrorMessage("password-error", "Password is required.");
            isValid = false;
        }
        return isValid;
    }

    function displayErrorMessage(elementId, message) {
        const errorElement = document.getElementById(elementId);
        errorElement.textContent = message;
        errorElement.style.display = "block";
    }
});
