<?php include "conn.php"; ?>

<form method="POST">
    <input type="email" name="email" placeholder="Enter your email" required>
    <input type="password" name="password" placeholder="Enter your password" required>
    <button>Login</button>
</form>

<?php
if($_SERVER["REQUEST_METHOD"] === "POST"){

    $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE user_email=?");
    $stmt->bind_param("s", $_POST['email']);
    $stmt->execute();

    $user = $stmt->get_result()->fetch_assoc();

    if($user && password_verify($_POST['password'], $user['user_pass'])){
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user'] = $user['user_name'];

        header("Location: index.php");
        exit;
    } else {
        echo "<p class='error'>Wrong email or password</p>";
    }
}
?>