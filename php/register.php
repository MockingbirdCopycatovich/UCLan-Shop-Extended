<?php include "conn.php"; 
    $error = "";
    $success = "";

    if($_SERVER["REQUEST_METHOD"] === "POST"){

        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];
        $address = htmlspecialchars($_POST['address']);

        // validation
        if(strlen($password) < 6){
            $error = "Password must be at least 6 characters";
        }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $error = "Invalid email format";
        } else {
            // check email exists
            $check = $conn->prepare("SELECT user_id FROM tbl_users WHERE user_email=?");
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();

            if($check->num_rows > 0){
                $error = "Email already exists";
            } else {

                $hash = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $conn->prepare("
                    INSERT INTO tbl_users (user_name, user_email, user_pass, user_address)
                    VALUES (?, ?, ?, ?)
                ");

                $stmt->bind_param("ssss", $name, $email, $hash, $address);

                if($stmt->execute()){
                    $success = "Registration successful! You can now login.";
                } else {
                    $error = "Something went wrong. Try again.";
                }
            }
        }
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
        
        <div class = "login-page">

            <div class="login-box">
                <h2>Register</h2>

                <?php if($error): ?>
                    <p class="error-msg"><?php echo $error; ?></p>
                <?php endif; ?>

                <?php if($success): ?>
                    <p class="success-msg"><?php echo $success; ?></p>
                <?php endif; ?>

                <form method="POST" class="login-form">
                    <input type="text" name="name" placeholder="Enter your name" required>
                    <input type="email" name="email" placeholder="Enter your email" required>
                    <input type="password" name="password" placeholder="Create password" required>
                    <input type="text" name="address" placeholder="Enter your address" required>
                    <button type="submit">Register</button>
                </form>
                
                <p class="login-extra">
                    Already have an account? <a href="login.php">Login</a>
                </p>
            </div>
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
    <script src="../js/index.js"></script>
</body>
</html>