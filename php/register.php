<?php include "conn.php"; ?>

<form method="POST">
    <input type="text" name="name" placeholder="Enter your name" required>
    <input type="email" name="email" placeholder="Enter your email" required>
    <input type="password" name="password" placeholder="Create password" required>
    <input type="text" name="address" placeholder="Enter your address" required>
    <button>Register</button>
</form>

<?php
if($_SERVER["REQUEST_METHOD"] === "POST"){

    // validation
    if(strlen($_POST['password']) < 6){
        echo "<p>Password must be at least 6 characters</p>";
        exit;
    }

    // check email exists
    $check = $conn->prepare("SELECT user_id FROM tbl_users WHERE user_email=?");
    $check->bind_param("s", $_POST['email']);
    $check->execute();
    $check->store_result();

    if($check->num_rows > 0){
        echo "<p>Email already exists</p>";
        exit;
    }

    $hash = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("
        INSERT INTO tbl_users (user_name, user_email, user_pass, user_address)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param("ssss",
        $_POST['name'],
        $_POST['email'],
        $hash,
        $_POST['address']
    );

    if($stmt->execute()){
        echo "<p>Registration successful! <a href='login.php'>Login</a></p>";
    } else {
        echo "<p>Error occurred</p>";
    }
}
?>