<?php
// filepath: /d:/XAMPP/htdocs/gl-projet/edit_consumable.php
include_once("Connection/connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $name = $con->real_escape_string($_POST['name']);
    // Retrieve other fields

    $stmt = $con->prepare("UPDATE consumables SET name = ?, /* other fields */ WHERE id = ?");
    $stmt->bind_param("si", $name, $id); // Assuming 'name' is a string and 'id' is an integer

    if ($stmt->execute()) {
        header("Location: STOK-MAniger.php?message=Consumable+updated+successfully");
        exit();
    } else {
        header("Location: STOK-MAniger.php?error=Failed+to+update+consumable");
        exit();
    }

    $stmt->close();
}

$con->close();
?>