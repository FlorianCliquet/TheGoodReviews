<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <meta charset="UTF-8">
    <link href="style.css" rel="stylesheet">
    <link href="modal.css" rel="stylesheet">
</head>
<body>
<nav class="glassmorphism-nav">
    <ul>
        <li><a href="../../index.php">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Services</a></li>
        <li><a href="#">Contact</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
    </ul>
</nav>

<h1>Forgot Password</h1>

<form action="send-password-reset.php" method="post">

    <label for="email">Email</label>
    <input type="email" name="email" id="email" required>

    <button type="submit">Send</button>

</form>

</body>
</html>
