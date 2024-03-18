<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include '../login-register/database.php';

session_start();

if (!isset($_SESSION['user'])) {
    // Redirect to login page if user is not connected
    header("Location: ../login-register/login.php");
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userEmail = $_SESSION['user_email']; // Ensure this matches the session variable used after login

    if(isset($_POST['name'])) {
        $newName = $_POST['name'];
        $query = "UPDATE users SET full_name = ? WHERE email = ?"; // Corrected column name
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $newName, $userEmail);
        mysqli_stmt_execute($stmt);
    }

    if(isset($_POST['password'])) {
        $newPassword = $_POST['password'];
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = ? WHERE email = ?"; // Corrected column name
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $userEmail);
        mysqli_stmt_execute($stmt);
    }

    // Redirect to the profile page after updating the information
    header("Location: profil.php");
    exit();
}

// Fetch user information
$userEmail = $_SESSION['user_email']; // Ensure this matches the session variable used after login
$query = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $userEmail);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>TheGoodReviews - User Profile</title>
    <link rel="stylesheet" href="profil.css">
</head>
<body>
<nav class="glassmorphism-nav">
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="../user/profil.php">Profil</a></li>
            <li><a href="../login-register/logout.php">Logout</a></li>
        </ul>
    </nav>
    <h1>User Profile</h1>
    <h2>Welcome, <?php echo $user['full_name']; ?>!</h2> <!-- Corrected column name -->

    <h3>Change Name</h3>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="New Name" required>
        <button type="submit">Change Name</button>
    </form>

    <h3>Change Password</h3>
    <form method="POST" action="">
        <input type="password" name="password" placeholder="New Password" required>
        <button type="submit">Change Password</button>
    </form>
</body>
</html>
