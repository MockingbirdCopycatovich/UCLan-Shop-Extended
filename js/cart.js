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

// Get the current basket or create an empty one
let cart = JSON.parse(localStorage.getItem("cart")) || [];
// Current active discount (promo code)
let activeDiscount = null;
// Total order amount
let finalTotal = 0;

// DOM elements
const cartContainer = document.getElementById("cart-container");
const clearCartBtn = document.getElementById("clearCart");
const payBtn = document.getElementById("payBtn");

// Basket rendering function
function renderCart() {
    // Cleaning the container before redrawing
    cartContainer.innerHTML = "";
    
    // If the cart is empty
    if (cart.length === 0) {
        cartContainer.innerHTML = "<p>Your cart is empty.</p>";
        return;
    }

    // Passing through the items in the cart
    cart.forEach((item, index) => {
        const [name, color, price, availability, image, desc] = item.data;

        // Creating a product card
        const card = document.createElement("div");
        card.classList.add("cart-item");

        card.innerHTML = `
            <img src="${image}" class="cart-item-img">

            <div class="cart-info">
                <h3>${name}</h3>
                <p>${desc}</p>
                <p>Color: <b>${color}</b></p>
                <p>Status: <b>${availability}</b></p>
                <p>Price: <b>${price}</b></p>
            </div>

            <!-- 
                Link to the product page.
                The product ID is stored via sessionStorage. (only for Read More)
            -->
            <a href="item.html" class="read-more" data-id="${index}">Read more</a>

            <!-- Managing the quantity and removal of goods -->
            <div class="cart-actions">
                <div class="count-controls">
                    <button class="minus" data-id="${index}">-</button>
                    <span>${item.count}</span>
                    <button class="plus" data-id="${index}">+</button>
                </div>

                <button class="remove" data-id="${index}">Remove</button>
            </div>
        `;
        // Adding a card to the DOM
        cartContainer.appendChild(card);
    });

    // Assigning event handlers
    setListeners();
    // Recalculation of the total amount
    updateTotal();
}

// Assigning cart control button handlers
function setListeners() {
    document.querySelectorAll(".plus").forEach(btn =>
        btn.addEventListener("click", (e) => {
            const id = e.target.dataset.id;
            cart[id].count++;
            saveCart();
            renderCart();
        })
    );

    // Reduction in quantity
    document.querySelectorAll(".minus").forEach(btn =>
        btn.addEventListener("click", (e) => {
            const id = e.target.dataset.id;
            if (cart[id].count > 1) cart[id].count--;
            saveCart();
            renderCart();
        })
    );

    // Deleting a product
    document.querySelectorAll(".remove").forEach(btn =>
        btn.addEventListener("click", (e) => {
            const id = e.target.dataset.id;
            cart.splice(id, 1);
            saveCart();
            renderCart();
            updateTotal();
        })
    );
}

// Function for calculating the total cost of an order
function updateTotal() {
    const totalElement = document.getElementById("total-price");

    let total = 0;
    // Calculation of cost based on quantity
    cart.forEach(item => {
        let priceNum = Number(item.data[2].replace('£', ''));
        total += priceNum * item.count;
    });

    // Application of discount (if active)
    if (activeDiscount) {
        if (activeDiscount.type === "100percent") {
            total = total * (1 - activeDiscount.value / 100);
        }
    }

    finalTotal = total;
    totalElement.textContent = `Total: £${finalTotal.toFixed(2)}`;
}
// Saving the basket in localStorage
function saveCart() {
    localStorage.setItem("cart", JSON.stringify(cart));
}
// Emptying the cart
clearCartBtn.addEventListener("click", () => {
    cart = [];
    saveCart();
    renderCart();
    updateTotal();
    activeDiscount = null;
    promoMessage.textContent = "";
    promoInput.value = "";
});

// Payment button (simulated purchase)
payBtn.addEventListener("click", () => {

    // Check: cart is empty
    if (cart.length === 0) {
        alert("Your cart is empty.");
        return;
    }

    // Checking the availability of products
    const unavailable = cart.filter(item => item.data[3] === "out-of-stock");

    if (unavailable.length > 0) {
        alert("Some items are out of stock. Purchase failed.");
        return;
    }

    // Output of the total amount
    alert(`Purchase successful! Amount paid: £${finalTotal.toFixed(2)}`);
});

// DOM elements for the promo code
const promoInput = document.getElementById("promoInput");
const promoMessage = document.getElementById("promoMessage");
const applyPromoBtn = document.getElementById("applyPromo");

// Using a promo code
applyPromoBtn.addEventListener("click", () => {
    const code = promoInput.value.trim();

    // Promotional code verification
    if (code === "IWillGiveVlad100") {
        activeDiscount = { type: "100percent", value: 100 };
        promoMessage.textContent = "Promo applied: 100% off";
    } 
    else {
        activeDiscount = null;
        promoMessage.textContent = "Invalid promo code";
    }

    updateTotal();
});

// Handler for transition to item.html page
// Saves selected item in sessionStorage
document.addEventListener("click", (event) => {
    if (event.target.classList.contains("read-more")) {
        const id = event.target.dataset.id;
        sessionStorage.setItem(
            "selectedItem",
            JSON.stringify(cart[id].data)
        );
    }
});


// Initial rendering of the basket
renderCart();