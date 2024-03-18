<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: /TheGoodReviews/src/login-register/login.php");
   exit; // Ensure the script stops executing after the redirect
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="/src/login-register/style.css">
    <title>TheGoodReviews</title>
</head>
<body>
    <div class="container">
        <h1>Qu'est ce que je vasi faire de tout cette osseille ?</h1>
        <a href="/src/login-register/logout.php" class="btn btn-warning">Logout</a>
    </div>
</body>
</html>
