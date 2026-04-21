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

// restore filters
let activeFilter = sessionStorage.getItem("activeFilter") || "all";

function applyFilter(filter) {
    products.forEach(card => {
        if (filter === "all") {
            card.style.display = "block";
        } else {
            card.style.display = card.classList.contains(filter) ? "block" : "none";
        }
    });

    filterButtons.forEach(btn => {
        btn.classList.toggle("active", btn.dataset.filter === filter);
    });

    sessionStorage.setItem("activeFilter", filter);
}

// initial load
applyFilter(activeFilter);

// click handlers
filterButtons.forEach(btn => {
    btn.addEventListener("click", () => {
        applyFilter(btn.dataset.filter);
    });
});