<?php include "conn.php"; ?>
<?php
    $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

    // Add
    if(isset($_GET['add'])){
        $id = (int)$_GET['add'];
        $cart[$id] = ($cart[$id] ?? 0) + 1;
    }

    // Remove
    if(isset($_GET['remove'])){
        $id = (int)$_GET['remove'];
        unset($cart[$id]);
    }

    // Clear
    if(isset($_GET['clear'])){
        $cart = [];
    }
    // Decrease
    if(isset($_GET['minus'])){
        $id = (int)$_GET['minus'];

        if(isset($cart[$id])){
            $cart[$id]--;

            if($cart[$id] <= 0){
                unset($cart[$id]);
            }
        }
    }

    // save cookie
    setcookie("cart", json_encode($cart), time() + 3600, "/");

    // CHECKOUT
    if(isset($_POST['checkout'])){

        if(!isset($_SESSION['user_id'])){
            header("Location: login.php");
            exit;
        }

        if(!empty($cart)){

            $total = 0;

            foreach($cart as $id => $qty){

                $stmt = $conn->prepare("SELECT product_price FROM tbl_products WHERE product_id=?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $res = $stmt->get_result()->fetch_assoc();

                if($res){
                    $total += $res['product_price'] * $qty;
                }
            }

            // INSERT ORDER
            // превращаем cart в строку: 1:2,3:1 (id:qty)
            $productString = [];

            foreach($cart as $id => $qty){
                $productString[] = $id . ":" . $qty;
            }

            $productString = implode(",", $productString);

            // INSERT ORDER
            $stmt = $conn->prepare("
                INSERT INTO tbl_orders (user_id, product_ids)
                VALUES (?, ?)
            ");

            $stmt->bind_param("is", $_SESSION['user_id'], $productString);
            $stmt->execute();

            // clear cart
            setcookie("cart", "", time() - 3600, "/");

            $successMsg = "Thank you! Your order has been placed.";
        }
    }

    // redirect after actions
    if(isset($_GET['add']) || isset($_GET['remove']) || isset($_GET['clear']) || isset($_GET['minus'])){

        if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
            echo "ok";
            exit;
        }

        header("Location: cart.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UCLan Shop</title>

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
        <section class="cart-page">

            <h2>Your Cart</h2>

            <?php if(!empty($successMsg)): ?>
                <p class="success-msg"><?php echo $successMsg; ?></p>
            <?php endif; ?>

            <?php
                $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

                if(empty($cart)){
                    echo "<p>Your cart is empty</p>";
                } else {

                    $total = 0;

                    foreach($cart as $id => $qty){

                        $stmt = $conn->prepare("SELECT * FROM tbl_products WHERE product_id=?");
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $product = $stmt->get_result()->fetch_assoc();

                        if($product){

                            $price = $product['product_price'];
                            $sum = $price * $qty;
                            $total += $sum;
                            ?>

                            <div class="cart-item">

                                <img src="../media/<?php echo htmlspecialchars($product['product_src']); ?>" class="cart-item-img">

                                <div class="cart-info">
                                    <h3><?php echo htmlspecialchars($product['product_title']); ?></h3>
                                    <p><?php echo htmlspecialchars($product['product_desc']); ?></p>
                                    <p>Price: £<?php echo htmlspecialchars($price); ?></p>
                                    <p>Subtotal: £<?php echo htmlspecialchars($sum); ?></p>
                                </div>

                                <a class="read-more" href="item.php?id=<?php echo $id; ?>">
                                    Read more
                                </a>

                                <div class="cart-actions">
                                    <div class="count-controls">
                                        <a class="btn-minus" href="cart.php?minus=<?php echo $id; ?>">-</a>
                                        <span><?php echo $qty; ?></span>
                                        <a class="btn-plus" href="cart.php?add=<?php echo $id; ?>">+</a>
                                    </div>
                                    
                                    <a href="cart.php?remove=<?php echo $id; ?>" class="remove">Remove</a>
                                </div>

                            </div>

                        <?php
                        }
                    }
                    ?>
                    <div class="cart-summary">

                        <div class="promo-section">
                            <input type="text" placeholder="Promo code">
                            <button>Apply</button>
                            <p></p>
                        </div>

                        <div class="summary-right">
                            <p id="total-price">Total: £<?php echo number_format($total, 2); ?></p>

                            <div class="cart-controls">
                                <a id="clearCart" href="cart.php?clear=1">Clear Cart</a>

                                <?php if(isset($_SESSION['user_id'])): ?>
                                    <form method="POST">
                                        <button type="submit" name="checkout" id="payBtn">Pay</button>
                                    </form>
                                <?php else: ?>
                                    <a href="login.php" class="login-buy">Login to checkout</a>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                    <?php
                }
            ?>

        </section>
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
        Connecting the JavaScript file for index.html.
        Used to control the mobile menu.
    -->
    <script src="../js/cart.js"></script>

</body>
</html>