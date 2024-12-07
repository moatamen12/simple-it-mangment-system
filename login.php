
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