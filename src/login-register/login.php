<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: /TheGoodReviews/index.php");
   exit; // Ensure the script stops executing after the redirect
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="showcase">
    <div class="video-container">
        <video src="../../media/loginbackground.mp4" autoplay muted loop id="myVideo"></video>
    <div class="container">
        <?php
        if (isset($_POST["login"])) {
           $email = $_POST["email"];
           $password = $_POST["password"];
            require_once "database.php";
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            if ($user && password_verify($password, $user["password"])) {
                $_SESSION["user"] = "yes";
                $_SESSION["user_email"] = $user["email"];
                echo "<div class='alert alert-danger'>Login successfully</div>";
                header("Location: /TheGoodReviews/index.php");
                exit;
            } else {
                echo "<div class='alert alert-danger'>Email or password does not match</div>";
            }
        }
        ?>
    </div>
    </div>
    <div class="login-box">
        <h2>Login</h2>
      <form action="login.php" method="post">
        <div class="user-box">
            <input type="email" name="email" required="">
            <label>Email</label>
        </div>
        <div class="user-box">
            <input type="password" name="password" required="">
            <label>Password</label>
        </div>
        <div class="btn">
            <input type="submit" value="Login" name="login" class="btn--login">
        </div>
      </form>
      <div class="form-btn">
            <button type="button" class="btn--register"><a href="register.php">Register Here</a></button>
        </div>
        <div class="form-btn">
            <button type="button" class="btn--forgotpassword"><a href="forgot-password.php">Forgot password?</a></button>
        </div>
    </div>
</div>
</body>
</html>
