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
    <title>Registration Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="showcase">
    <div class="video-container">
        <video src="../../media/loginbackground.mp4" autoplay muted loop id="myVideo"></video>
    </div>
    <div class="container">
        <div class="login-box">
            <h2>Register</h2>
            <?php
            if (isset($_POST["submit"])) {
               $fullName = $_POST["fullname"];
               $email = $_POST["email"];
               $password = $_POST["password"];
               $passwordRepeat = $_POST["repeat_password"];
               
               $passwordHash = password_hash($password, PASSWORD_DEFAULT);

               $errors = array();
               
               if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
                array_push($errors,"All fields are required");
               }
               if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
               }
               if (strlen($password)<8) {
                array_push($errors,"Password must be at least 8 characters long");
               }
               if ($password!==$passwordRepeat) {
                array_push($errors,"Password does not match");
               }
               require_once "database.php";
               $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
               $stmt->bind_param("s", $email);
               $stmt->execute();
               $result = $stmt->get_result();
               $rowCount = $result->num_rows;
               if ($rowCount>0) {
                array_push($errors,"Email already exists!");
               }
               if (count($errors)>0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
               } else {
                $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $fullName, $email, $passwordHash);
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>You are registered successfully.</div>";
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
               }
            }
            ?>
            <form action="register.php" method="post">
                <div class="user-box">
                    <input type="text" name="fullname" required="">
                    <label>Full Name</label> 
                </div>
                <div class="user-box">
                    <input type="email" name="email" required="">
                    <label>Email</label>
                </div>
                <div class="user-box">
                    <input type="password" name="password" required="">
                    <label>Password</label>
                </div>
                <div class="user-box">
                    <input type="password" name="repeat_password" required="">
                    <label>Repeat Password</label>
                </div>
                <div class="btn">
                    <input type="submit" class="btn--login" value="Register" name="submit">
                </div>
            </form>
            <div class="form-btn">
                <a href="login.php" class="btn--sub-login">Login Here</a>
            </div>  
        </div>
    </div>
</div>
</body>
</html>
