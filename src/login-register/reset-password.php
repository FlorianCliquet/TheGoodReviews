<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/database.php";

$sql = "SELECT * FROM users
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    header("Location: forgot-password.php?message=Token%20invalid");
    exit(); 
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    header("Location: forgot-password.php?message=Token%20has%20expired");
    exit(); 
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <style>
        /* CSS for pop-up */
        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid black;
            z-index: 1000;
            display: none;
        }
    </style>
</head>
<body>
<div class="showcase">
    <div class="video-container">
        <video src="../../media/loginbackground.mp4" autoplay muted loop id="myVideo"></video>
    <div>
</div>
<div class="container">
    <form id="resetForm" method="post" action="process-reset-password.php">
        <div class="login-box">
            <h2>Reset Password</h2>
            <div class="user-box">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <label for="password">New password</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="user-box">
                <label >Repeat password</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>
            <div class="btn">
                <input type="submit" value="Send" name="Send" class="btn btn--login">
            </div>
        </div>
    </form>
</div>

<!-- Pop-up div for showing success/failure message -->
<div id="popup" class="popup">
    <span id="popupMessage"></span>
</div>

<script>
    // JavaScript to show pop-up message
    document.getElementById("resetForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent form submission
        
        var form = this;
        var formData = new FormData(form);

        fetch(form.action, {
            method: form.method,
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // Show pop-up with the response message
            showPopup(data);
            // Reset form after successful submission
            form.reset();
            // Redirect to login.php after 3 seconds
            setTimeout(function() {
                window.location.href = "login.php";
            }, 3000); // 3000 milliseconds = 3 seconds
        })
        .catch(error => console.error('Error:', error));
    });

    function showPopup(message) {
        var popup = document.getElementById("popup");
        var popupMessage = document.getElementById("popupMessage");
        popupMessage.textContent = message;
        popup.style.display = "block";

        // Hide pop-up after 6 seconds
        setTimeout(function() {
            popup.style.display = "none";
        }, 6000); // 6000 milliseconds = 6 seconds
    }
</script>

</body>
</html>
