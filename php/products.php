<?php include "conn.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>

    <!-- 
        Connecting an external CSS file.
        All styles are separated.
    -->
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
    <!-- 
        HEADER:
        The semantic element <header> is used
        to place the logo and navigation.
    -->
    <header>
        <!-- Website logo -->
        <div class="logo">
            <img src="../media/images/logo_reverse.png" alt="Logo">
        </div>

        <!-- Text name of the site -->
        <div class="header-text">Student Shop</div>

        <!-- Welcome words for logined users -->
        <?php if(isset($_SESSION['user'])): ?>
            <span>Welcome, <?php echo $_SESSION['user']; ?></span>
        <?php endif; ?>

        <!-- 
            Main navigation for desktop screens
        -->
        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="cart.php">Cart</a>

            <!-- Extra navigation -->
            <?php if(isset($_SESSION['user'])): ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>

        <!-- 
            Burger menu button for mobile navigation.
        -->
        <div class="burger" id="burgerBtn">
            ☰
        </div>

        <!-- 
            Mobile menu.
            Displayed/hidden when clicking on the burger icon. Hidden when the screen is large enough.
        -->
        <nav class="mobile-menu" id="mobileMenu">
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="cart.php">Cart</a>

            <!-- Extra navigation for mobile -->
            <?php if(isset($_SESSION['user'])): ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <!-- 
        MAIN: Main content of the page.
        The semantic element <main> is used.
    -->
    <main>
        <!-- 
            Product filtering block.
            Used to control the display of products
            depending on their availability status.
        -->
        <div class="product-filters">
            <!-- The ‘All’ button clears all filters -->
            <button class="filter-btn active" data-filter="all">All</button>
            <!-- Filter products with good stock -->
            <button class="filter-btn" data-filter="good-stock">Good Stock</button>
            <!-- Filter products with limited quantity -->
            <button class="filter-btn" data-filter="low-stock">Low Stock</button>
            <!-- Filter products that are out of stock -->
            <button class="filter-btn" data-filter="out-of-stock">Out of Stock</button>
        </div>

        <!-- 
            Container for product cards.
            Content is created dynamically using JavaScript (DOM manipulation).
        -->
        <div id="products-container" class="products-grid">
            <?php
                $sql = "SELECT * FROM tbl_products";
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()):
            ?>
            <div class="product-card <?php echo htmlspecialchars($row['product_stock']); ?>">

                <img src="../media/<?php echo htmlspecialchars($row['product_src']); ?>" 
                    alt="<?php echo htmlspecialchars($row['product_title']); ?>">

                <h3><?php echo htmlspecialchars($row['product_title']); ?></h3>
                
                <p> <?php echo htmlspecialchars($row['product_desc']); ?></p>
                
                <p>Status: <?php echo htmlspecialchars($row['product_stock']); ?></p>

                <a class="read-more" href="item.php?id=<?php echo $row['product_id']; ?>">
                    Read more
                </a>
                
                <p id="price">Price: £<?php echo htmlspecialchars($row['product_price']); ?></p>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <button class="add-cart" data-id="<?php echo $row['product_id']; ?>">
                        Add to Cart
                    </button>
                <?php else: ?>
                    <a class="login-buy" href="login.php">Login to buy</a>
                <?php endif; ?>

            </div>

            <?php endwhile; ?>
        </div>

        <!-- 
           ‘Back to top’ button.
            Displayed when scrolling down the page.
        -->
        <button id="scrollTopBtn">Back to top</button>
    </main>

    <!-- 
        FOOTER:
        Semantic element <footer>
        Contains contact information and useful links. (Copied entirely from the example video)
    -->
    <footer>
        <div class="footer-col">
            <h3>Links</h3>
            <a href = "https://msuclanac.sharepoint.com/sites/CyprusStudentHub" > Student Union </a>
        </div>

        <div class="footer-col">
            <h3>Contact</h3>
            <p> Email: <a href = "mailto:suinformation@uclan.ac.uk">suinformation@uclan.ac.uk</a> <br></p>
            <p> Tel: <a href = "01772893000">01772 89 3000</a> <br> </p>
        </div>

        <div class="footer-col">
            <h3>Location</h3>
            <p> University of Central Lancashire Students' union <br></p>
            <p> Fylde road, Preston. PR1 7BY <br></p>
            <p> Registered in England <br></p>
            <p> Company Number: 7623917 <br></p>
            <p> Registered Charity Number: 1142616 <br></p>
        </div>
    </footer>

    <!-- 
        Connecting the JavaScript file for products.php.
        Used to control:
        1)Mobile menu
        2)Product cards
        3)Product filters
    -->
    <script src="../js/products.js"></script>

</body>
</html>