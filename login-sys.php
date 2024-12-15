<?php 
include_once('Conection/connection.php');

if (isset($_POST['submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $EmployeeID = $_POST['EmployeeID'];

    $stmt = $con->prepare("SELECT * FROM user WHERE email = ? AND employee_id = ?");
    $stmt->bind_param('si',$email,$EmployeeID);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 1){
        $user = $result->fetch_assoc();
        if($password === $user['password']){
            //session vars
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];

            header('location: index.html');
            exit();
        }
        else{
            $error = "Incorrect password";
            header('Location: login.php?error='.urlencode($error));
            exit();
        }
    }else{
        $error = "User not found ";
        header('Location: login.php?error='.urlencode($error));
        exit();
    }
    $stmt->close();
}
else {
    $error = 'AN ERROR OCERD PLEASE TRY AGAIN';
    header('Location: login.php?error=' . urlencode($error));
    exit();
}
$con->close();
?>