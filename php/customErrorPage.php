<?php include "conn.php"; 
// Receive an error code
$errorCode = $_SERVER['REDIRECT_STATUS'] ?? 500;

// Error message
$messages = [
    400 => "Bad Request",
    401 => "Unauthorized",
    403 => "Forbidden",
    404 => "Page Not Found",
    500 => "Internal Server Error"
];

$message = $messages[$errorCode] ?? "Unknown Error";
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
    <link rel="stylesheet" href="/~vvasilev1/UCLan-Shop-Extended/css/style.css">

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
            <img src="/~vvasilev1/UCLan-Shop-Extended/media/images/logo_reverse.png" alt="Logo">
        </div>

        <!-- Text name of the site -->
        <div class="header-text">Student Shop</div>

        <!-- Welcome words for logined users -->
        <?php if(isset($_SESSION['user'])): ?>
            <div class="welcome-box">
                <span class="welcome-text">Welcome</span>
                <span class="welcome-user"><?php echo htmlspecialchars($_SESSION['user']); ?></span>
            </div>
        <?php endif; ?>

        <!-- 
            Main navigation for desktop screens
        -->
        <nav class="nav-links">
            <a href="/~vvasilev1/UCLan-Shop-Extended/php/index.php">Home</a>
            <a href="/~vvasilev1/UCLan-Shop-Extended/php/products.php">Products</a>
            <a href="/~vvasilev1/UCLan-Shop-Extended/php/cart.php">Cart</a>

            <!-- Extra navigation -->
            <?php if(isset($_SESSION['user'])): ?>
                <a href="#" id="logoutBtn">Logout</a>
            <?php else: ?>
                <a href="/~vvasilev1/UCLan-Shop-Extended/php/login.php">Login</a>
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
            <a href="/~vvasilev1/UCLan-Shop-Extended/php/index.php">Home</a>
            <a href="/~vvasilev1/UCLan-Shop-Extended/php/products.php">Products</a>
            <a href="/~vvasilev1/UCLan-Shop-Extended/php/cart.php">Cart</a>

            <!-- Extra navigation for mobile -->
            <?php if(isset($_SESSION['user'])): ?>
                <a href="#" id="logoutBtn">Logout</a>
            <?php else: ?>
                <a href="/~vvasilev1/UCLan-Shop-Extended/php/login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <!-- 
        MAIN: Main content of the page.
        The semantic element <main> is used.
    -->
    <main>
        <div class="error-container">
            <h1>Error <?= $errorCode ?></h1>
            <p>
                Oops! Something went wrong.<br>
                Your error code is <strong><?= $errorCode ?></strong>
            </p>
            <a href="/~vvasilev1/UCLan-Shop-Extended/php/index.php" class="btn-home">Go back to Home</a>
        </div>   
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
        Connecting the JavaScript file for index.php.
        Used to control the mobile menu.
    -->
    <script src="/~vvasilev1/UCLan-Shop-Extended/js/index.js"></script>

</body>
</html>