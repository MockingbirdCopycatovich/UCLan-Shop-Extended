<?php include "conn.php"; 
    $error = "";

    if(!isset($_GET['id'])){
        die("No product selected");
    }

    $id = (int)$_GET['id'];

    if($_SERVER["REQUEST_METHOD"] === "POST"){

        $rating = $_POST['rating'] ?? null;

        if(empty($rating)){
            $error = "Please select rating";
        }

        if(empty($error)){

            $stmt = $conn->prepare("
                INSERT INTO tbl_reviews 
                (product_id, user_id, review_title, review_desc, review_rating)
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "iissi",
                $id,
                $_SESSION['user_id'],
                $_POST['title'],
                $_POST['desc'],
                $_POST['rating']
            );

            $stmt->execute();

            // redirect to avoid duplicate submit
            header("Location: item.php?id=".$id);
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Details</title>

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
        <?php
            if(!isset($_GET['id'])){
                die("No product selected");
            }

            $id = (int)$_GET['id'];

            $stmt = $conn->prepare("SELECT * FROM tbl_products WHERE product_id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $product = $stmt->get_result()->fetch_assoc();

            if(!$product){
                die("Product not found");
            }
        ?>

        <div class="item-page">
            <div class="item-card">

                <img src="../media/<?php echo htmlspecialchars($product['product_src']); ?>">
                <div class = "item-info">
                    <h2><?php echo htmlspecialchars($product['product_title']); ?></h2>
                    <p class="item-desc"><?php echo htmlspecialchars($product['product_desc']); ?></p>

                    <p class="item-price" id="price">£<?php echo htmlspecialchars($product['product_price']); ?></p>

                    <p class="item-stock">Status: <?php echo htmlspecialchars($product['product_stock']); ?></p>

                    <?php
                        $stmt = $conn->prepare("SELECT AVG(review_rating) as avg_rating FROM tbl_reviews WHERE product_id=?");
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $res = $stmt->get_result()->fetch_assoc();

                        $rating = $res['avg_rating'] ? round($res['avg_rating'],1) : "No ratings yet";

                        echo "<p><strong>Rating:</strong> $rating</p>";
                    ?>

                    <?php if(isset($_SESSION['user_id'])): ?>
                        <button class="add-cart" data-id="<?php echo $id; ?>">
                            Add to cart
                        </button>
                    <?php else: ?>
                        <a class="login-buy" href="login.php">Login to buy</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- 
            Button to return to the product page.
            Provides convenient navigation within the application.
        -->
        <a href="products.php" class="back-btn">Back to Products</a>
        <!-- 
            Button to return to the cart page.
            Provides convenient navigation within the application.
        -->
        <a href="cart.php" class="back-btn">Back to Cart</a>

        <!-- REVIEWS -->
        <h3>Leave a review</h3>

        <?php if(isset($_SESSION['user_id'])): ?>

        <form method="POST" class="review-form">

            <div class="form-group">
                <label for="title">Review title</label>
                <input type="text" id="title" name="title" placeholder="e.g. Great quality!" required>
            </div>

            <div class="form-group">
                <label for="desc">Your review</label>
                <textarea id="desc" name="desc" placeholder="Write your experience..." required></textarea>
            </div>

            <div class="form-group">
                <label>Rating</label>

                <?php if(!empty($error)): ?>
                    <p class="error-msg"><?php echo $error; ?></p>
                <?php endif; ?>

                <div class="rating">
                    <input type="radio" name="rating" value="5" id="star5"><label for="star5">★</label>
                    <input type="radio" name="rating" value="4" id="star4"><label for="star4">★</label>
                    <input type="radio" name="rating" value="3" id="star3"><label for="star3">★</label>
                    <input type="radio" name="rating" value="2" id="star2"><label for="star2">★</label>
                    <input type="radio" name="rating" value="1" id="star1"><label for="star1">★</label>
                </div>
            </div>

            <button type="submit" class="submit-review">Submit Review</button>

        </form>

        <?php else: ?>
            <p>You must login to leave a review.</p>
        <?php endif; ?>

        <h3>Customer Reviews</h3>

        <?php
            $stmt = $conn->prepare("
                SELECT r.*, u.user_name 
                FROM tbl_reviews r
                JOIN tbl_users u ON r.user_id = u.user_id
                WHERE r.product_id=?
                ORDER BY r.review_id DESC
            ");

            $stmt->bind_param("i", $id);
            $stmt->execute();
            $reviews = $stmt->get_result();
        ?>

        <?php if($reviews->num_rows > 0): ?>

        <div class="reviews-container">

            <?php while($rev = $reviews->fetch_assoc()): ?>

            <div class="review-card">
                <h4><?php echo htmlspecialchars($rev['review_title']); ?></h4>

                <p class="review-user">
                    By <?php echo htmlspecialchars($rev['user_name']); ?>
                </p>

                <div class="review-rating">
                    <?php
                        for($i = 1; $i <= 5; $i++){
                            echo $i <= $rev['review_rating'] ? "★" : "☆";
                        }
                    ?>
                </div>

                <p class="review-text">
                    <?php echo htmlspecialchars($rev['review_desc']); ?>
                </p>
            </div>

            <?php endwhile; ?>

        </div>

        <?php else: ?>
            <p>No reviews yet. Be the first!</p>
        <?php endif; ?>

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
    <script src="../js/item.js"></script>

</body>
</html>