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


// Promo code logic

const promoInput = document.getElementById("promoInput");
const applyPromo = document.getElementById("applyPromo");
const promoMessage = document.getElementById("promoMessage");

const promoCodes = {};

applyPromo.addEventListener("click", () => {

    const code = promoInput.value.trim().toUpperCase();

    if (code === "") {
        promoMessage.textContent = "Please enter a promo code";
        promoMessage.style.color = "red";
        return;
    }

    if (promoCodes[code]) {
        promoMessage.textContent = `Promo code applied! Discount: ${promoCodes[code]}%`;
        promoMessage.style.color = "green";

        const totalElement = document.getElementById("total-price");

        if (totalElement) {
            let totalText = totalElement.textContent.replace("Total: £", "");
            let total = parseFloat(totalText);

            let discount = promoCodes[code];
            let newTotal = total - (total * discount / 100);

            totalElement.textContent = `Total: £${newTotal.toFixed(2)}`;
        }

    } else {
        promoMessage.textContent = "Promo code does not exist";
        promoMessage.style.color = "red";
    }

});