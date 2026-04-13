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

// Get the selected product from sessionStorage
// The data was saved on the products.html page
const selected = sessionStorage.getItem("selectedItem");

// Convert the JSON string into a JavaScript object (array)
const item = selected ? JSON.parse(selected) : null;

// Container for displaying goods
const container = document.getElementById("item-container");

// Check: if data is missing (e.g., direct transition to the page)
if (!item) {
    // Error message if item not found
    container.innerHTML = "<p>Item not found.</p>";
} else {
    // HTML markup for product card
    container.innerHTML = `
        <div class="item-card">
            <img src="${item[4]}" alt="${item[0]}">
            
            <div class="item-info">
                <h2>${item[0]}</h2>
                <p>Color: ${item[1]}</p>
                <p>Stock: ${item[3]} </p>
                <p class="item-price" id="price">${item[2]}</p>
                <p class="item-desc">${item[5]}</p>
                <button class="add-cart-big">Add to Cart</button>
            </div>
        </div>
    `;
}
// Adding an item to the cart
// Uses localStorage
document.addEventListener("click", (event) => {
    if (event.target.classList.contains("add-cart-big")) {

        // Get the current basket or create an empty one
        let cart = JSON.parse(localStorage.getItem("cart")) || [];

        // Check whether this item is already in the cart
        const existingIndex = cart.findIndex(
            cartItem =>
                cartItem.data[0] === item[0] &&
                cartItem.data[1] === item[1]
        );

        // Increasing the quantity
        if (existingIndex !== -1) {
            cart[existingIndex].count++;
        } else { // or adding a new product
            cart.push({
                data: item,
                count: 1
            });
        }

        // Save the cart
        localStorage.setItem("cart", JSON.stringify(cart));
        alert("Added to cart!");
    }
});