document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("form").addEventListener("submit", function (event) {
        let username = document.getElementById("username").value;
        let email = document.getElementById("email").value;
        let password = document.getElementById("password").value;
        let repeatPassword = document.getElementById("repeat_password").value;
        let errors = [];

        if (username === "" || email === "" || password === "" || repeatPassword === "") {
            errors.push("All fields are required");
        }

        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errors.push("Email is not valid");
        }

        if (password.length < 8) {
            errors.push("Password must be at least 8 characters long");
        }
        let specialCharacterRegex = /[!@#$%^&*(),.?":{}|<>]/;
        if (!specialCharacterRegex.test(password)) {
            errors.push("Password must contain at least one special character");
        }

        if (password !== repeatPassword) {
            errors.push("Password doesn't match");
        }

        if (errors.length > 0) {
            event.preventDefault();
            let errorDiv = document.getElementById("error-messages");
            errorDiv.innerHTML = "<div class='alert alert-danger'>" + errors.join("<br>") + "</div>";
        }
    });
});
