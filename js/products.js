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


// array indexed as follows: [0]name, [1]color, [2]price, [3]stock [4]image-src, [5]desc.
const tshirts = [
    ['Legacy T-Shirt','Red','£7.99','good-stock','../media/images/tshirts/tshirt1.jpg','Perfect for those graduating this year. Get a bargain whilst we have the stock.'],
    ['Legacy T-Shirt','Green','£7.99','last-few','../media/images/tshirts/tshirt2.jpg','Limited stock. Grab these nostalgic items before they make their way onto eBay.'],
    ['Legacy T-Shirt','Blue','£7.99','out-of-stock','../media/images/tshirts/tshirt3.jpg','Sadly we are sold out of this legendary item. Keep an eye out for future stock.'],
    ['Legacy T-Shirt','Cyan','£7.99','good-stock','../media/images/tshirts/tshirt4.jpg','Perfect for those graduating this year. Get a bargain whilst we have the stock.'],
    ['Legacy T-Shirt','Magenta','£7.99','out-of-stock','../media/images/tshirts/tshirt5.jpg','Sadly we are sold out of this legendary item. Keep an eye out for future stock.'],
    ['Legacy T-Shirt','Yellow','£7.99','last-few','../media/images/tshirts/tshirt6.jpg','Limited stock. Grab these nostalgic items before they make their way onto eBay.'],
    ['Legacy T-Shirt','Black','£7.99','out-of-stock','../media/images/tshirts/tshirt7.jpg','Sadly we are sold out of this legendary item. Keep an eye out for future stock.'],
    ['Legacy T-Shirt','Grey','£7.99','good-stock','../media/images/tshirts/tshirt8.jpg','Perfect for those graduating this year. Get a bargain whilst we have the stock.'],
    ['Legacy T-Shirt','Burgundy','£7.99','last-few','../media/images/tshirts/tshirt9.jpg','Limited stock. Grab these nostalgic items before they make their way onto eBay.'],
];

// Container to which product cards will be added
const productsContainer = document.getElementById("products-container");

// All filter buttons
const filterButtons = document.querySelectorAll(".filter-btn");

//Restoring filters
//filters are read from sessionStorage when the fallback page loads, set to ‘all’ by default
const savedFilters = JSON.parse(sessionStorage.getItem("activeFilters"));

let activeFilters = savedFilters
    ? new Set(savedFilters)
    : new Set(["all"]);


// Function for displaying products on the page
function renderProducts() {
    
    // Clearing the container before redrawing
    productsContainer.innerHTML = "";

    // Check through the array of goods
    tshirts.forEach((item, index) => {
        // Checking active filters
        if (activeFilters.has("all") || activeFilters.has(item[3])) {
            createCard(item, index);
        }
    });
}

// Function for creating a single product card
function createCard(item, index) {
    // Creating an HTML div element
    const product = document.createElement("div");
    // Adding CSS classes
    product.classList.add("product-card");
    // availability status
    product.classList.add(item[3]);

    // HTML markup for product card
    product.innerHTML = `
        <img src="${item[4]}" alt="${item[0]}">
        <h3>${item[0]}</h3>
        <p><br></p>
        <p>Color: ${item[1]}</p>
        <p>Stock: ${item[3]}</p>
        <p>${item[5]}</p>
        <!-- 
            Link to the product page.
            The product ID is stored via sessionStorage. (only for Read More)
        -->
        <a href="item.html" class="read-more" data-id="${index}">Read more</a>

        <p id="price">${item[2]}</p>

        <div class="card-buttons">
            <!-- Button to add product to basket -->
            <button class="add-cart" data-id="${index}">Add to Cart</button>
        </div>
    `;
    // Adding a card to the DOM
    productsContainer.appendChild(product);
}

// Filter button handlers
filterButtons.forEach(btn => {
    btn.addEventListener("click", () => {
        const filter = btn.dataset.filter;

        // If the ‘all’ filter is selected
        if (filter === "all") {
            activeFilters = new Set(["all"]);
            filterButtons.forEach(b => b.classList.remove("active"));
            btn.classList.add("active");
            renderProducts();
            return;
        }

        // Remove the ‘all’ filter if another is selected
        activeFilters.delete("all");
        document.querySelector('[data-filter="all"]').classList.remove("active");

        // Filter switching
        if (activeFilters.has(filter)) {
            activeFilters.delete(filter);
            btn.classList.remove("active");
        } else {
            activeFilters.add(filter);
            btn.classList.add("active");
        }

        // If no filters are selected, return ‘all’
        if (activeFilters.size === 0) {
            activeFilters.add("all");
            document.querySelector('[data-filter="all"]').classList.add("active");
        }

        renderProducts();

        //Saving filters after every click
        sessionStorage.setItem("activeFilters",JSON.stringify([...activeFilters])
);
    });
});

// Handler for transition to item.html page
// Saves selected item in sessionStorage
document.addEventListener("click", (event) => {
    if (event.target.classList.contains("read-more")) {
        const id = event.target.dataset.id;
        sessionStorage.setItem("selectedItem", JSON.stringify(tshirts[id]));
    }
});

// Adding an item to the cart
// Uses localStorage
document.addEventListener("click", (event) => {
    if (event.target.classList.contains("add-cart")) {

        const index = event.target.dataset.id;
        const item = tshirts[index];

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

//Restoring the active class of buttons
filterButtons.forEach(btn => {
    if (activeFilters.has(btn.dataset.filter)) {
        btn.classList.add("active");
    } else {
        btn.classList.remove("active");
    }
});


// Initial rendering of goods
renderProducts();