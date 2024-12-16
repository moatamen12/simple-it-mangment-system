<?php 
include_once("Connection/connection.php");

if(!isset($_SESSION['user_id'])){
    header('location: login.php');
    exit();
}

// Define the upload directory
define('UPLOAD_DIR', 'images/profiles/');
define('DEFAULT_PROFILE_IMAGE', 'images/defUSER.png');

// Create uploads directory if it doesn't exist
$upload_path_full = __DIR__ . '/' . UPLOAD_DIR;
if (!file_exists($upload_path_full)) {
    mkdir($upload_path_full, 0777, true);
}


// Handle profile updates
if(isset($_POST['update_profile'])) {
    $user_id = $_SESSION['user_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone_number'];

    // Handle image upload
    if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);

        if(in_array(strtolower($filetype), $allowed)) {
            $new_filename = uniqid() . '.' . $filetype;
            $upload_path = UPLOAD_DIR . $new_filename;
            $upload_path_full = __DIR__ . '/' . $upload_path;

            if(move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path_full)) {
                $update_image = ", profile_image = ?";
                $relative_path = $upload_path; // Store relative path in DB
            } else {
                $message = "Failed to upload image";
                $message_type = "error";
            }
        } else {
            $message = "Invalid file type for profile image";
            $message_type = "error";
        }
    }

    // Update user information
    $query = "UPDATE user SET full_name = ?, email = ?, phone_number = ?" . 
             (isset($update_image) ? $update_image : "") . 
             " WHERE id = ?";
             
    $stmt = $con->prepare($query);
    
    if(isset($update_image)) {
        $stmt->bind_param("ssssi", $full_name, $email, $phone, $relative_path, $user_id);
    } else {
        $stmt->bind_param("sssi", $full_name, $email, $phone, $user_id);
    }
    
    if($stmt->execute()) {
        $message = "Profile updated successfully!";
        $message_type = "success";
    } else {
        $message = "Error updating profile";
        $message_type = "error";
    }
}

// Password update handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Get current user password
    $stmt = $con->prepare("SELECT password FROM user WHERE id = ?");
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($current_password === $user['password']) {
        if ($new_password === $confirm_password) {
            $stmt = $con->prepare("UPDATE user SET password = ? WHERE id = ?");
            $stmt->bind_param('si', $new_password, $_SESSION['user_id']);
            
            if ($stmt->execute()) {
                $password_message = "Password updated successfully";
                $message_type = "success";
            } else {
                $password_message = "Failed to update password";
                $message_type = "error";
            }
        } else {
            $password_message = "New passwords do not match";
            $message_type = "error";
        }
    } else {
        $password_message = "Current password is incorrect";
        $message_type = "error";
    }
}





$user_id = $_SESSION['user_id'];
$stmt = $con->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Calculate profile completion
$total_fields = 5; // Total number of profile fields to check
$completed_fields = 0;

if(!empty($user['full_name'])) $completed_fields++;
if(!empty($user['email'])) $completed_fields++;
if(!empty($user['phone_number'])) $completed_fields++;
if(!empty($user['profile_image'])) $completed_fields++;
if(!empty($user['role'])) $completed_fields++;
if(!empty($user['employee_id'])) $completed_fields++;

$completion_percentage = ($completed_fields / $total_fields) * 100;



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>

    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="JS/lodMenue.js"></script>
</head>
<body>
    <!-- Menu Placeholder -->
    <div id="menu-placeholder"></div>


    <div class="prof-container">
        <div class="combined-card">
            <!-- Header with Image and Role -->
            <div class="profile-header">


                <div class="profile-basic">
                    <div class="profile-image">
                        <img src="<?php 
                        echo !empty($user['profile_image']) 
                            ? htmlspecialchars($user['profile_image']) 
                            : DEFAULT_PROFILE_IMAGE; 
                        ?>" alt="Profile Picture" class="profile-pic">
                    </div>


                    <div class="user-info">
                        <h2><?php echo htmlspecialchars($user['role']); ?></h2>
                        <span><?php echo htmlspecialchars($user['email']); ?></span><br>
                        <span><?php echo htmlspecialchars($user['employee_id']); ?></span>
                    </div>
                </div>
                <button class="btn prf" onclick="window.location.href='logout.php'">Logout</button>

            </div>

            <?php if (isset($password_message)): ?>
                <div class="message-container">
                    <div class="successMessage <?php echo $message_type; ?>">
                        <?php echo htmlspecialchars($password_message); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Profile Tabs Container -->
            <div class="profile-tabs">
                <div class="tabs-header">
                    <button class="tab-btn active" data-tab="settings">Settings</button>
                    <button class="tab-btn" data-tab="security">Security</button>
                </div>

                <!-- Settings Tab -->
                <div class="tab-content active" id="settings">
                    <form class="settings-form" method="post" enctype="multipart/form-data" name="update_profile">
                        <input type="hidden" name="update_profile" value="1">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="profile_image" class="btn upload-btn">
                                <i class="fas fa-camera"></i> Change Profile Photo
                            </label>
                            <input type="file" id="profile_image" name="profile_image" accept="image/*" style="display: none">
                        </div>
                        <button type="submit" class="btn">Save Changes</button>
                    </form>
                </div>

                <!-- Security Tab -->
                <div class="tab-content" id="security">
                    <div class="password-change">
                        <h3>Change Password</h3>
                        <form class="password-form" method="post" onsubmit="return validatePassword()">
                            <input type="hidden" name="update_password" value="1">
                            <div class="form-group">
                                <label>Current Password</label>
                                <input type="password" name="current_password" required>
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" id="new_password" required>
                                <div class="password-strength"></div>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" required>
                            </div>
                            <button type="submit" class="btn">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab Switching
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                
                button.classList.add('active');
                document.getElementById(button.dataset.tab).classList.add('active');
            });
        });
    </script>
</body>
</html>








