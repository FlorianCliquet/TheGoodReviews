<?php
session_start();
session_destroy();
header("Location: /TheGoodReviews/src/login-register/login.php");
exit; // Ensure the script stops executing after the redirect
?>