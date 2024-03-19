<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include '../login-register/database.php';

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login-register/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userEmail = $_SESSION['user_email']; 
    if(isset($_POST['name'])) {
        $newName = $_POST['name'];
        $query = "UPDATE users SET full_name = ? WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $newName, $userEmail);
        mysqli_stmt_execute($stmt);
    }

    if(isset($_POST['password'])) {
        $newPassword = $_POST['password'];
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = ? WHERE email = ?"; 
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $userEmail);
        mysqli_stmt_execute($stmt);
    }

    header("Location: profil.php");
    exit();
}

// Fetch user information
$userEmail = $_SESSION['user_email']; 
$query = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $userEmail);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

$queryCountReviews = "SELECT COUNT(*) as review_count FROM Reviews WHERE User_email = ?";
$stmtCountReviews = mysqli_prepare($conn, $queryCountReviews);
mysqli_stmt_bind_param($stmtCountReviews, "s", $userEmail);
mysqli_stmt_execute($stmtCountReviews);
$resultCountReviews = mysqli_stmt_get_result($stmtCountReviews);
$rowCountReviews = mysqli_fetch_assoc($resultCountReviews);
$reviewCount = $rowCountReviews['review_count'];
if ($reviewCount > 44) {
    $status = "Nerd";
} else if ($reviewCount > 24){
    $status = "Introvert";
} else if ($reviewCount > 9){
    $status = "Gaming enjoyer";
} else if ($reviewCount > 4){
    $status = "Gamer";
} else if ($reviewCount > 1){
    $status = "Enthusiat";
} else {
    $status = "Random";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>TheGoodReviews - User Profile</title>
    <link rel="stylesheet" href="profil.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="main-body">
    
          <nav class="glassmorphism-nav">
        <ul>
            <li><a href="../../index.php">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="../user/profil.php">Profil</a></li>
        </ul>
    </nav>    
          <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                  <img src="<?php echo $user['icone']; ?>" alt="<?php echo $user['full_name']; ?>" class="rounded-circle" width="150">
                    <div class="mt-3">
                    <h4><?php echo $user['full_name']; ?></h4>
                      <p class="text-secondary mb-1"><?php echo $status;?></p>
                      <button class="btn btn-primary">Follow</button>
                      <button class="btn btn-outline-primary">Message</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card mt-3">
                <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0">
                        <a <?php if ($user['X'] !== null){ ?> href="https://twitter.com/<?php echo $user['X']; ?>" <?php } else { ?> href="https://twitter.com/" <?php } ?>>
                            <img src="../../media/logo-black.png" width="24" height="24" class="mr-2 icon-inline text-info">
                            <span class="text-secondary" style="text-decoration: none; color: inherit;">X</span>
                        </a>
                    </h6>
                    <span class="text-secondary">
                        <?php if ($user['X'] == null){
                            echo "Not set";
                        } else {
                            echo $user['X'];
                        } ?>
                    </span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0">
                        <a <?php if ($user['Discord'] !== null){ ?> href="https://twitter.com/<?php echo $user['X']; ?>" <?php } else { ?> href="https://twitter.com/" <?php } ?>>
                            <img src="../../media/discord.png" width="24" height="24" class="mr-2 icon-inline text-info">
                            <span class="text-secondary" style="text-decoration: none; color: inherit;">Discord</span>
                        </a>
                    </h6>
                    <span class="text-secondary">
                        <?php if ($user['Discord'] == null){
                            echo "Not set";
                        } else {
                            echo $user['Discord'];
                        } ?>
                    </span>
                </li>
                </ul>
              </div>
            </div>
            <div class="col-md-8">
              <div class="card mb-3">
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Full Name</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      Kenneth Valdez
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Email</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      fip@jukmuh.al
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Phone</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      (239) 816-9029
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Mobile</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      (320) 380-4539
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Address</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      Bay Area, San Francisco, CA
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-12">
                      <a class="btn btn-info " target="__blank" href="https://www.bootdey.com/snippets/view/profile-edit-data-and-skills">Edit</a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row gutters-sm">
                <div class="col-sm-6 mb-3">
                  <div class="card h-100">
                    <div class="card-body">
                      <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">assignment</i>Project Status</h6>
                      <small>Web Design</small>
                      <div class="progress mb-3" style="height: 5px">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <small>Website Markup</small>
                      <div class="progress mb-3" style="height: 5px">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <small>One Page</small>
                      <div class="progress mb-3" style="height: 5px">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <small>Mobile Template</small>
                      <div class="progress mb-3" style="height: 5px">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <small>Backend API</small>
                      <div class="progress mb-3" style="height: 5px">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 66%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 mb-3">
                  <div class="card h-100">
                    <div class="card-body">
                      <h6 class="d-flex align-items-center mb-3"><i class="material-icons text-info mr-2">assignment</i>Project Status</h6>
                      <small>Web Design</small>
                      <div class="progress mb-3" style="height: 5px">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <small>Website Markup</small>
                      <div class="progress mb-3" style="height: 5px">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <small>One Page</small>
                      <div class="progress mb-3" style="height: 5px">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <small>Mobile Template</small>
                      <div class="progress mb-3" style="height: 5px">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <small>Backend API</small>
                      <div class="progress mb-3" style="height: 5px">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 66%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>



            </div>
          </div>

        </div>
    </div>
    </body>
</html>