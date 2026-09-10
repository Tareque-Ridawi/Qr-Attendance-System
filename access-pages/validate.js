// Check The Form Before Submitting...
document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    const fullName = document.querySelector("input[name='full_name']");
    const userId = document.querySelector("input[name='user_id']");
    const email = document.querySelector("input[name='email']");
    const pass = document.querySelector("input[name='pass']");
    const repeatPass = document.getElementById("student-password-repeat") || document.getElementById("teacher-password-repeat");
    const checkbox = document.getElementById("agree-to-rules");

    const messages = {
        full_name: document.createElement("small"),
        user_id: document.createElement("small"),
        email: document.createElement("small"),
        pass: document.createElement("small"),
        repeat: document.createElement("small"),
    };

    for (const key in messages) {
        messages[key].style.color = "red";
    }

    fullName.parentNode.appendChild(messages.full_name);
    userId.parentNode.appendChild(messages.user_id);
    email.parentNode.appendChild(messages.email);
    pass.parentNode.appendChild(messages.pass);
    repeatPass.parentNode.appendChild(messages.repeat);

    let userIdAvailable = false;
    let checkTimeout;

    function validateFullName() {
        const value = fullName.value.trim();
        const valid = /^[a-zA-Z .]+$/.test(value);

        if (!value) {
            messages.full_name.textContent = "Full name is required.";
            fullName.setCustomValidity("Required");
        } else if (!valid) {
            messages.full_name.textContent = "Only letters, spaces, and periods allowed.";
            fullName.setCustomValidity("Invalid characters");
        } else {
            messages.full_name.textContent = "";
            fullName.setCustomValidity("");
        }
    }

    function validateUserId() {
        const value = userId.value.trim();
        const valid = /^[a-z0-9_]+$/.test(value);

        if (!value) {
            messages.user_id.textContent = "Username is required.";
            userId.setCustomValidity("Required");
            userIdAvailable = false;
            return;
        }

        if (!valid) {
            messages.user_id.textContent = "Only lowercase letters, numbers, and underscores allowed.";
            userId.setCustomValidity("Invalid username");
            userIdAvailable = false;
            return;
        }

        clearTimeout(checkTimeout);
        checkTimeout = setTimeout(() => {
            fetch(`check-username.php?user_id=${encodeURIComponent(value)}`)
                .then(response => response.text())
                .then(data => {
                    if (data === "taken") {
                        messages.user_id.textContent = "Username already taken.";
                        userId.setCustomValidity("Username taken");
                        userIdAvailable = false;
                    } else {
                        messages.user_id.textContent = "";
                        userId.setCustomValidity("");
                        userIdAvailable = true;
                    }
                })
                .catch(() => {
                    messages.user_id.textContent = "Error checking username.";
                    userId.setCustomValidity("Check failed");
                    userIdAvailable = false;
                });
        }, 300);
    }

    function validateEmail() {
        const value = email.value.trim();
        const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);

        if (!value) {
            messages.email.textContent = "Email is required.";
            email.setCustomValidity("Required");
        } else if (!valid) {
            messages.email.textContent = "Invalid email format.";
            email.setCustomValidity("Invalid email");
        } else {
            messages.email.textContent = "";
            email.setCustomValidity("");
        }
    }

    function validatePassword() {
        const value = pass.value.trim();
        if (!value) {
            messages.pass.textContent = "Password is required.";
            pass.setCustomValidity("Required");
        } else if (value.length < 6) {
            messages.pass.textContent = "Password must be at least 6 characters.";
            pass.setCustomValidity("Too short");
        } else {
            messages.pass.textContent = "";
            pass.setCustomValidity("");
        }
    }

    function validateRepeatPassword() {
        const password = pass.value.trim();
        const repeat = repeatPass.value.trim();

        if (repeat !== password) {
            messages.repeat.textContent = "Passwords do not match.";
            repeatPass.setCustomValidity("Mismatch");
        } else {
            messages.repeat.textContent = "";
            repeatPass.setCustomValidity("");
        }
    }

    fullName.addEventListener("input", validateFullName);
    userId.addEventListener("input", validateUserId);
    email.addEventListener("input", validateEmail);
    pass.addEventListener("input", () => {
        validatePassword();
        validateRepeatPassword();
    });
    repeatPass.addEventListener("input", validateRepeatPassword);

    form.addEventListener("submit", async (e) => {
        validateFullName();
        validateUserId();
        validateEmail();
        validatePassword();
        validateRepeatPassword();

        if (!checkbox.checked) {
            alert("Please agree to the terms and conditions.");
            e.preventDefault();
            return;
        }

        await new Promise(r => setTimeout(r, 400)); // Wait for debounce & fetch

        if (!form.checkValidity() || !userIdAvailable) {
            e.preventDefault();
        }
    });
});

// Clear all fields on load (browser autocomplete prevention)
window.addEventListener("load", () => {
    document.querySelectorAll("input[type='text'], input[type='email'], input[type='password']").forEach(input => {
        input.value = "";
    });
});
