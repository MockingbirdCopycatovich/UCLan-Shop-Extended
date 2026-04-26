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


// Button to scroll the page up
const scrollTopBtn = document.getElementById("scrollTopBtn");

// Button appears when scrolling
window.addEventListener("scroll", () => {
    if (window.scrollY > 150) {
        scrollTopBtn.style.display = "block";
    } else {
        scrollTopBtn.style.display = "none";
    }
});

// Smooth scrolling to the top
scrollTopBtn.addEventListener("click", () => {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
});


// Filter System
const filterButtons = document.querySelectorAll(".filter-btn");
const products = document.querySelectorAll(".product-card");

let activeFilters = JSON.parse(sessionStorage.getItem("activeFilters")) || ["all"];

function applyFilters() {

    if (activeFilters.length === 0) {
        activeFilters = ["all"];
    }

    products.forEach(card => {
        if (activeFilters.includes("all")) {
            card.style.display = "block";
            return;
        }

        const match = activeFilters.some(filter =>
            card.classList.contains(filter)
        );

        card.style.display = match ? "block" : "none";
    });

    filterButtons.forEach(btn => {
        const isActive =
            activeFilters.includes(btn.dataset.filter) ||
            (activeFilters.includes("all") && btn.dataset.filter === "all");

        btn.classList.toggle("active", isActive);
    });

    sessionStorage.setItem("activeFilters", JSON.stringify(activeFilters));
}


//  Init function
applyFilters();


// Click handler for filters
filterButtons.forEach(btn => {
    btn.addEventListener("click", () => {
        const filter = btn.dataset.filter;

        if (filter === "all") {
            activeFilters = ["all"];
        } else {
            activeFilters = activeFilters.filter(f => f !== "all");

            if (activeFilters.includes(filter)) {
                activeFilters = activeFilters.filter(f => f !== filter);
            } else {
                activeFilters.push(filter);
            }

            if (activeFilters.length === 0) {
                activeFilters = ["all"];
            }
        }

        applyFilters();
    });
});
document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".add-cart").forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.id;

            fetch(`cart.php?add=${id}`, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(res => res.text())
            .then(() => {
                alert("Product added to cart");
            });
        });
    });

});