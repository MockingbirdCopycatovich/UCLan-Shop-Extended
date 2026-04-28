// Get the burger menu button by its ID
const burgerBtn = document.getElementById("burgerBtn");

// Get the mobile menu container
// The menu will be shown and hidden using a CSS class
const mobileMenu = document.getElementById("mobileMenu");

// Add a ‘click’ event handler to the burger menu button.
// When clicked, DOM manipulation occurs.
burgerBtn.addEventListener("click", () => {
    // The classList.toggle method adds or removes the ‘active’ class.
    // In CSS, this class is used to display the mobile menu.
    mobileMenu.classList.toggle("active");
});

const logoutBtn = document.getElementById("logoutBtn");

if (logoutBtn) {
    logoutBtn.addEventListener("click", (e) => {
        e.preventDefault();

        const confirmLogout = confirm("Are you sure you want to log out?");
        if (!confirmLogout) return;

        fetch("logout.php", {
            method: "POST",
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(res => res.text())
        .then(() => {
            location.reload();
        });
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".login-form");
    const email = form.querySelector("input[name='email']");
    const password = form.querySelector("input[name='password']");
    
    const messageBox = document.createElement("div");
    messageBox.classList.add("form-messages");
    form.prepend(messageBox);

    function showMessage(msg, type = "error") {
        messageBox.innerHTML = msg;
        messageBox.className = "form-messages " + type;
    }

    function validateEmail(emailValue) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue);
    }

    function validatePassword(pass) {
        const hasUpper = /[A-Z]/.test(pass);
        const hasLower = /[a-z]/.test(pass);
        const hasNumber = /[0-9]/.test(pass);

        return {
            valid: hasUpper && hasLower && hasNumber && pass.length >= 6,
            hasUpper,
            hasLower,
            hasNumber,
            length: pass.length >= 6
        };
    }

    form.addEventListener("submit", function (e) {
        const emailValue = email.value.trim();
        const passValue = password.value;

        // Email check
        if (!validateEmail(emailValue)) {
            e.preventDefault();
            showMessage("Invalid email format");
            return;
        }

        // Passwird check
        const passCheck = validatePassword(passValue);

        if (!passCheck.valid) {
            e.preventDefault();

            let msg = "Password must contain:<br>";

            if (!passCheck.length) msg += "- At least 6 characters<br>";
            if (!passCheck.hasUpper) msg += "- 1 uppercase letter<br>";
            if (!passCheck.hasLower) msg += "- 1 lowercase letter<br>";
            if (!passCheck.hasNumber) msg += "- 1 number<br>";

            showMessage(msg);
            return;
        }

        showMessage("All good!", "success");
    });
});