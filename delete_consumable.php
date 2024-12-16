<?php
// filepath: /d:/XAMPP/htdocs/gl-projet/delete_consumable.php
include_once("Connection/connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);

    $stmt = $con->prepare("DELETE FROM consumables WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: STOK-MAniger.php?message=Consumable+deleted+successfully");
        exit();
    } else {
        header("Location: STOK-MAniger.php?error=Failed+to+delete+consumable");
        exit();
    }

    $stmt->close();
}

$con->close();
?>