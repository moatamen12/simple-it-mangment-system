<?php
//session_start();
include("Conection/connection.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $query = "SELECT * FROM Employee WHERE email = ? LIMIT 1";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['employeeID'];

                
                // Update last login timestamp
                $update_query = "UPDATE Employee SET last_login = CURRENT_TIMESTAMP WHERE employeeID = ?";
                $update_stmt = mysqli_prepare($con, $update_query);
                mysqli_stmt_bind_param($update_stmt, 'i', $user['employeeID']);
                
                if (!mysqli_stmt_execute($update_stmt)) {
                    // Log error but don't block login
                    error_log("Failed to update last login: " . mysqli_error($con));
                }

                header("Location: profile.html");
                exit();
            }
            
            
        }
        $_SESSION['error'] = "Invalid email or password!";
    }
    header("Location:login.html");
    exit();
}
?>