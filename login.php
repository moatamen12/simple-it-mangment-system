
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <title>Login</title>
</head>
<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'Conection\connection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $employeeID = isset($_POST['EmployeeID']) ? trim($_POST['EmployeeID']) : '';

    // Prepare SQL query with email and EmployeeID
    $query = "SELECT * FROM Employee WHERE email = ? AND employeeID = ?";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) 
    {
        // Bind parameters: 'si' means string and integer
        mysqli_stmt_bind_param($stmt, 'si', $email, $employeeID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) 
        {
            $user = mysqli_fetch_assoc($result);
            
            if (password_verify($password, $user['password_hash'])) 
            {
                // Regenerate session ID for security
                session_regenerate_id(true);
        
                // Set session variables
                $_SESSION['user_id'] = $user['employeeID'];

                // Update last login
                $update_query = "UPDATE Employee SET last_login = CURRENT_TIMESTAMP WHERE employeeID = ?";
                $update_stmt = mysqli_prepare($con, $update_query);
                if ($update_stmt) {
                    mysqli_stmt_bind_param($update_stmt, 'i', $user['employeeID']);
                    mysqli_stmt_execute($update_stmt);
                    mysqli_stmt_close($update_stmt);
                }
                // Redirect to profile page
                header("Location: profile.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No user found with that email and EmployeeID.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $error = "Failed to prepare the SQL statement.";
        error_log("SQL prepare failed: " . mysqli_error($con));
    }
    
}
?>

<body>

    <div class="container">
        <div class="box form-box  " id="loginForm">
            <header>Login</header>
            <form  id="loginForm" action="" method="post">
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="EmployeeID">EmployeeID</label>
                    <input type="text" name="EmployeeID" id="EmployeeID" autocomplete="off" required>
                </div>
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login"  required>
                </div>
            </form>
                 <?php if (!empty($error)): ?>
                <div style="color: red;"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
        </div><br>
   
    </div>  
</body>
</html>