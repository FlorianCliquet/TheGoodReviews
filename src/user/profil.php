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

// Function to calculate the days passed since registration
function formatDaysPassed($days) {
    if ($days == 0) {
        return "Member since today";
    } elseif ($days == 1) {
        return "Member since 1 day";
    } else {
        return "Member since $days days";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userEmail = $_SESSION['user_email']; 

    if (isset($_POST['name'])) {
        $newName = $_POST['name'];
        $query = "UPDATE users SET full_name = ? WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $newName, $userEmail);
        mysqli_stmt_execute($stmt);
    }

    if (isset($_POST['password'])) {
        $newPassword = $_POST['password'];
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = ? WHERE email = ?"; 
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $userEmail);
        mysqli_stmt_execute($stmt);
    }

    if (isset($_FILES['icone']) && $_FILES['icone']['error'] === UPLOAD_ERR_OK) {
        $fileName = $_FILES['icone']['name'];
        $fileTmpName = $_FILES['icone']['tmp_name'];
        $fileSize = $_FILES['icone']['size'];
        $fileError = $_FILES['icone']['error'];
        $fileType = $_FILES['icone']['type'];

        // Get the file extension
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Valid file extensions
        $allowedExtensions = array('jpg', 'jpeg', 'png');

        if (in_array($fileExt, $allowedExtensions)) {
            // Generate a unique name for the file
            $newFileName = uniqid('', true) . '.' . $fileExt;
            
            // Upload the file to the destination directory
            move_uploaded_file($fileTmpName, '../../media/' . $newFileName);

            // Update the user's icon path in the database
            $query = "UPDATE users SET icone = ? WHERE email = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ss", $newFileName, $userEmail);
            mysqli_stmt_execute($stmt);
        }
    }

    if (isset($_POST['resetIcon']) && $_POST['resetIcon'] == 'true') {
        // Reset user's icon to default
        $defaultIconPath = '../../media/defaultpp.png';
        $query = "UPDATE users SET icone = ? WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $defaultIconPath, $userEmail);
        mysqli_stmt_execute($stmt);
    }

    header("Location: profil.php");
    exit();
}

// Fetch user information
$userEmail = $_SESSION['user_email']; 
$query = "SELECT *, DATEDIFF(NOW(), datecreation) AS days_passed FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $userEmail);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Calculate the days passed since registration
$user['days_passed'] = $user['days_passed'] > 0 ? $user['days_passed'] : 0;

$queryCountReviews = "SELECT COUNT(*) as review_count FROM Reviews WHERE User_email = ?";
$stmtCountReviews = mysqli_prepare($conn, $queryCountReviews);
mysqli_stmt_bind_param($stmtCountReviews, "s", $userEmail);
mysqli_stmt_execute($stmtCountReviews);
$resultCountReviews = mysqli_stmt_get_result($stmtCountReviews);
$rowCountReviews = mysqli_fetch_assoc($resultCountReviews);
$reviewCount = $rowCountReviews['review_count'];

// Determine user status based on review count
if ($reviewCount > 44) {
    $status = "Nerd";
} elseif ($reviewCount > 24) {
    $status = "Introvert";
} elseif ($reviewCount > 9) {
    $status = "Gaming enjoyer";
} elseif ($reviewCount > 4) {
    $status = "Gamer";
} elseif ($reviewCount > 1) {
    $status = "Enthusiast";
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
                        <img src="<?php echo $user['icone']; ?>" id="profileImage" alt="<?php echo $user['full_name']; ?>" class="rounded-circle" width="150">
                        <div class="mt-3">
                            <h4><?php echo $user['full_name']; ?></h4>
                            <p class="text-secondary mb-1"><?php echo $status;?></p>
                            <!-- Follow and Message buttons -->
                            <?php if (isset($_GET['edit']) && $_GET['edit'] == 'true') { ?>
                                <button class="btn btn-danger" id="deletePhotoButton">Delete Photo</button>
                                <button class="btn btn-info" id="changePhotoButton">Change Photo</button>
                            <?php } else { ?>
                                <button class="btn btn-primary" id="followButton">Follow</button>
                                <button class="btn btn-outline-primary" id="messageButton">Message</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
              </div>
              <div class="card mt-3">
                <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0">
                        <a <?php if ($user['X'] !== null){ ?> href="https://X.com/<?php echo $user['X']; ?>" <?php } else { ?> href="https://X.com/" <?php } ?> class='target'>
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
                        <a href="https://discord.com" class='target'>
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
                        <span id="fullNameDisplay"><?php echo $user['full_name']; ?></span>
                        <input type="text" class="form-control" id="newFullName" style="display: none;">
                        <button class="btn btn-primary mt-2" id="submitFullName" style="display: none;">Submit</button>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Email</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $user['email']; ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Password</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <!-- Add password display and editing functionality -->
                        <span id="passwordDisplay"><?php echo str_repeat("*", strlen($user['password'])); ?></span>
                        <input type="password" class="form-control" id="newPassword" style="display: none;">
                        <button class="btn btn-primary mt-2" id="submitPassword" style="display: none;">Submit</button>
                    </div>
                    </div>
                    </div>
                    <hr>
                    <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Member Since</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo formatDaysPassed($user['days_passed']); ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-12">
                      <button class="btn btn-info" id="editButton">Edit</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>

<!-- Add JavaScript to toggle between views and handle updates -->
<script>
    const editButton = document.getElementById('editButton');
    const fullNameDisplay = document.getElementById('fullNameDisplay');
    const newFullNameInput = document.getElementById('newFullName');
    const submitFullNameButton = document.getElementById('submitFullName');
    const passwordDisplay = document.getElementById('passwordDisplay');
    const newPasswordInput = document.getElementById('newPassword');
    const submitPasswordButton = document.getElementById('submitPassword');
    const deletePhotoButton = document.getElementById('deletePhotoButton');
    const changePhotoButton = document.getElementById('changePhotoButton');

    const followButton = document.getElementById('followButton');
    const messageButton = document.getElementById('messageButton');

    editButton.addEventListener('click', function() {
        if (newFullNameInput.style.display === 'none') {
            // Switch to editing mode
            fullNameDisplay.style.display = 'none';
            newFullNameInput.style.display = 'block';
            submitFullNameButton.style.display = 'block';
            newFullNameInput.value = fullNameDisplay.textContent.trim();
            newFullNameInput.focus();
            
            // Show password field in editing mode
            passwordDisplay.style.display = 'none';
            newPasswordInput.style.display = 'block';
            submitPasswordButton.style.display = 'block';
            newPasswordInput.value = '';
            
            // Change button texts
            followButton.textContent = 'Delete Photo';
            messageButton.textContent = 'Change Photo';
        } else {
            // Switch to display mode
            fullNameDisplay.style.display = 'inline';
            newFullNameInput.style.display = 'none';
            submitFullNameButton.style.display = 'none';
            
            // Show password field in display mode
            passwordDisplay.style.display = 'inline';
            newPasswordInput.style.display = 'none';
            submitPasswordButton.style.display = 'none';
            
            // Reset button texts
            followButton.textContent = 'Follow';
            messageButton.textContent = 'Message';
            
            // Submit the new full name and password via form submission
            const newFullName = newFullNameInput.value.trim();
            const newPassword = newPasswordInput.value.trim();
            if (newFullName !== '' || newPassword !== '') {
                // Set up a form to submit the new full name and/or password
                const form = document.createElement('form');
                form.method = 'post';
                form.enctype = 'multipart/form-data';
                form.action = 'profil.php'; // Update to your PHP file
                
                if (newFullName !== '') {
                    const inputFullName = document.createElement('input');
                    inputFullName.type = 'hidden';
                    inputFullName.name = 'name';
                    inputFullName.value = newFullName;
                    form.appendChild(inputFullName);
                }
                
                if (newPassword !== '') {
                    const inputPassword = document.createElement('input');
                    inputPassword.type = 'hidden';
                    inputPassword.name = 'password';
                    inputPassword.value = newPassword;
                    form.appendChild(inputPassword);
                }

                // Add input for file upload
                const inputFile = document.createElement('input');
                inputFile.type = 'file';
                inputFile.name = 'icone';
                form.appendChild(inputFile);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    });

    deletePhotoButton.addEventListener('click', function() {
        // Reset user's icon to default
        const form = document.createElement('form');
        form.method = 'post';
        form.action = 'profil.php'; // Update to your PHP file
        const inputReset = document.createElement('input');
        inputReset.type = 'hidden';
        inputReset.name = 'resetIcon';
        inputReset.value = 'true';
        form.appendChild(inputReset);
        document.body.appendChild(form);
        form.submit();
    });

    changePhotoButton.addEventListener('click', function() {
    // Create a file input element
    const inputFile = document.createElement('input');
    inputFile.type = 'file';
    inputFile.accept = 'image/*'; // Accept only image files
    inputFile.style.display = 'none'; // Hide the file input

    // Add the file input to the document body
    document.body.appendChild(inputFile);

    // Add event listener for file input change
    inputFile.addEventListener('change', function() {
        const file = inputFile.files[0]; // Get the selected file
        if (file) {
            const reader = new FileReader(); // Create a FileReader object
            reader.onload = function(e) {
                // Display the selected image preview
                document.getElementById('profileImage').src = e.target.result;
            };
            reader.readAsDataURL(file); // Read the file as a data URL
            
            // Submit the form with the selected file
            const form = new FormData();
            form.append('icone', file);
            form.append('submitPhoto', 'true'); // Add a flag to indicate photo submission

            // Use fetch API to submit the form data
            fetch('profil.php', {
                method: 'POST',
                body: form
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                console.log(data); // Log the response for debugging
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
        }
    });

    // Trigger a click event on the file input
    inputFile.click();
});


</script>

</body>
</html>
