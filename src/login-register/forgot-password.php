<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <meta charset="UTF-8">
    <link href="style.css" rel="stylesheet">
    <link href="modal.css" rel="stylesheet">
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
</body>
</html>
