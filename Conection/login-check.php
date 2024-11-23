<?php

//authorizition access control
//check if user is isset


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

?>