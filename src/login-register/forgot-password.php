<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <meta charset="UTF-8">
    <link href="style.css" rel="stylesheet">
</head>
<body>
<div class="showcase">
    <div class="video-container">
        <video src="../../media/loginbackground.mp4" autoplay muted loop id="myVideo"></video>
    </div>
</div>
<div class="login-box">
    <h2>Forgot Password</h2>
    <form action="send-password-reset.php" method="post">
        <div class="user-box">
            <input type="email" name="email" id="email" required="">
            <label>Email</label>
            <button type="submit" class="btn--reset">Send</button>
        </div> 
    </form>
</div>

<!-- Popup div for showing message -->
<div id="popup" class="popup">
    <span id="popupMessage"></span>
</div>

<script>
    // JavaScript to show pop-up message
    document.addEventListener("DOMContentLoaded", function() {
        var message = "<?php echo isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '' ?>";
        if (message) {
            showPopup(message);
        }   
    });

    function showPopup(message) {
        var popup = document.getElementById("popup");
        var popupMessage = document.getElementById("popupMessage");
        popupMessage.textContent = message;
        popup.style.display = "absolute";

        // Hide pop-up after 6 seconds
        setTimeout(function() {
            popup.style.display = "none";
        }, 12000); // 6000 milliseconds = 6 seconds
    }
</script>
</body>
</html>
