document.addEventListener("DOMContentLoaded", function() {
    // 1. Registration form validation
    const registerForm = document.getElementById("registerForm");
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const fullname = document.getElementById('fullname').value.trim();
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            let errors = [];

            const nameWords = fullname.split(/\s+/);
            if (nameWords.length < 2) {
                errors.push("Please enter your full name (both first and last name).");
            }

            const usernameRegex = /^[a-zA-Z0-9]{4,}$/;
            if (!usernameRegex.test(username)) {
                errors.push("Username must be at least 4 characters long and contain only letters and numbers.");
            }

            if (password.length < 6) {
                errors.push("Password must be at least 6 characters long.");
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert(errors.join("\n"));
            }
        });
    }

    // 2. Login form validation
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            let errors = [];

            if (username.length === 0) {
                errors.push("Username is required.");
            }

            if (password.length < 4) {
                errors.push("Password must be at least 4 characters long.");
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert(errors.join("\n"));
            }
        });
    }

    // 3. Checkout form validation
    const checkoutForm = document.getElementById("checkoutForm");
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            const phone = document.getElementById('phone').value.trim();
            const dateInput = document.getElementById('delivery_date').value;
            const address = document.getElementById('address').value.trim();
            let errors = [];

            const phoneRegex = /^[0-9+]{9,15}$/;
            if (!phoneRegex.test(phone)) {
                errors.push("Please enter a valid phone number (9 to 15 digits).");
            }

            const selectedDate = new Date(dateInput);
            const today = new Date();
            today.setHours(0,0,0,0);
            if (selectedDate <= today) {
                errors.push("Delivery date must be a future date.");
            }

            if (address.length < 10) {
                errors.push("Please enter a detailed delivery address (minimum 10 characters).");
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert(errors.join("\n"));
            }
        });
    }

    // 4. Contact form validation
    const contactForm = document.getElementById("contactForm");
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const subject = document.getElementById('subject').value.trim();
            const message = document.getElementById('message').value.trim();
            let errors = [];

            if (name.length < 2) {
                errors.push("Name must be at least 2 characters long.");
            }
            if (subject.length < 4) {
                errors.push("Subject must be at least 4 characters long.");
            }
            if (message.length < 15) {
                errors.push("Message details must be at least 15 characters long.");
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert(errors.join("\n"));
            }
        });
    }
});
