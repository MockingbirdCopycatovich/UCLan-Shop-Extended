<?php include "conn.php"; ?>
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
        <!-- Sections with offers -->
        <?php
            $sql = "SELECT * FROM tbl_offers";
            $result = $conn->query($sql);

            while($row = $result->fetch_assoc()) {
                echo "<div class='offer'>";
                echo "<h3>" . htmlspecialchars($row['offer_title']) . "</h3>";
                echo "<p>" . htmlspecialchars($row['offer_desc']) . "</p>";
                echo "</div>";
            }
        ?>

        <!-- 
            First section of content.
        -->
        <section>
            <h1>WHERE OPPORTUNITY CREATES SUCCESS</h1>

            <p>Each student of Central Lancashire is automatically
                 becomes not only the student in University - 
                 You are becoming the part of our future and life.<br></p> 

            <p> With that we are welcome to present our merch shop! <br></p>

            <h2>Together</h2>

            <!-- 
                HTML5 video element.
                Video embedded locally, with controls (loop autoplay muted).
            -->
            <video src="../media/video/video.mp4" controls loop autoplay muted></video>
        </section>

        <!-- 
            Section with external multimedia content.
        -->
        <section>
            <h2> Join our global community </h2>

            <!-- 
                iframe is used to embed external video (YouTube),
                which is a mandatory requirement for index.php.
            -->
            <div class="iframe-box">
                <iframe
                    title="UCLan introduction video"
                    src="https://www.youtube.com/embed/vzbO3x3OUJQ"
                    allowfullscreen>
                </iframe>
            </div>
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
        Connecting the JavaScript file for index.php.
        Used to control the mobile menu.
    -->
    <script src="../js/index.js"></script>

</body>
</html>