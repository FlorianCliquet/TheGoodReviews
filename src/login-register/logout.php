<?php
session_start();
session_destroy();
header("Location: /TheGoodReviews/src/dashboards/public_dashboard.php");
exit; // Ensure the script stops executing after the redirect
?>