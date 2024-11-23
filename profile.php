<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'Conection\connection.php';
$user_id = $_SESSION['user_id'];

$query = "SELECT fullName, email, Employee_image, role FROM Employee WHERE employeeID = ?";
$stmt  = mysqli_prepare($con, $query);

if($stmt){
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $fullName, $email, $profile_image, $role);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    if (empty($profile_image)) {
        $profile_image = 'images/defUSER.png';
    }
}else{
    die("Failed to prepare statement: " . mysqli_error($con));
}

// Debugging output
// var_dump($fullName, $email, $profile_image, $role);
// exit();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>


    <div class="prof-container">
        <div class="profile-card">
            <div class="profile-image">
                <img src="images/defUSER.png" alt="Profile Picture">
                <span class="change-photo-text">Change Photo</span>
                <!-- <input type="file" id="fileInput" accept="images/*"> -->
            </div>
            <div class="profile-info">
                <h2 class="hdr"><?php echo htmlspecialchars($fullName); ?></h2>

                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <span><?php echo htmlspecialchars($email); ?></span>
                </div>

                <div class="info-item">
                    <i class="fas fa-user-tag"></i>
                    <span><?php echo htmlspecialchars($role); ?></span>
                </div>

                <div class="info-item">
                    <i class="fa-solid fa-id-card-clip"></i>
                    <span><?php echo htmlspecialchars($user_id); ?></span>
                </div>
            </div>
            <div class="profile-options">
                <button class="btn" id='edit' onclick="togglePasswordForms('','editProfile')">Edit Profile</button>
                <!-- <button class="btn">Change Password</button> -->
                <button class="btn" onclick="window.location.href='login.php'">Logout</button>
            </div>
        </div>






        <!--  chang password form  -->
        <div class=" form-box form-container" id="editProfile">
            <header>Change Password</header>
            <form action="" method="post" >
                <div class="field input">
                    <label for="old-password">Current Password</label>
                    <input type="password" name="old_password" id="old-password" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="new-password">New Password</label>
                    <input type="password" name="new_password" id="new-password" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="confirm-password">Confirm New Password</label>
                    <input type="password" name="confirm_password" id="confirm-password" autocomplete="off" required>
                </div>
                
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Update Password" required>
                </div>
            </form>

            <div></div>
        </div>
    </div>



<script src="script.js"></script>
</body>
<!-- Add this after <body> -->
    <div id="header"></div>
<!-- 
    <script>
    fetch('partial/test.html')
        .then(response => response.text())
        .then(data => {
            document.getElementById('header').innerHTML = data;
        });
    </script> -->
</html>