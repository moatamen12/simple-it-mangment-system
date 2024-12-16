<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $error = '';
    if (isset($_GET['error'])) {
        $error = htmlspecialchars($_GET['error']);
    } elseif (isset($_SESSION['error'])) {
        $error = htmlspecialchars($_SESSION['error']);
        unset($_SESSION['error']); // Clear the error after displaying
    }

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <title>Login</title>
</head>
<body>

    <div class="container">
        <div class="box form-box  " id="loginForm">
            <header>Login</header>
            <form  id="loginForm" action="login-sys.php" method="post">
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
                <div class="errorMessage"><?php echo $error; ?></div>
            <?php endif; ?>
        </div><br>
    </div>  
</body>
</html>