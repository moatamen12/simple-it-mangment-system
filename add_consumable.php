<?php
// filepath: /d:/XAMPP/htdocs/gl-projet/add_consumable.php
include_once("Connection/connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $con->real_escape_string($_POST['name']);
    $barcode = $con->real_escape_string($_POST['barcode']);
    $quantity = intval($_POST['quantity']);
    $status = $con->real_escape_string($_POST['status']);
    $associated_product_id = intval($_POST['associated_product_id']);
    $price = floatval($_POST['price']);

    $stmt = $con->prepare("INSERT INTO consumables (associated_product_id, name, barcode, quantity, status, price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issisd", $associated_product_id, $name, $barcode, $quantity, $status, $price);

    if ($stmt->execute()) {
        header("Location: STOK-MAniger.php?message=Consumable+added+successfully");
        exit();
    } else {
        header("Location: STOK-MAniger.php?error=Failed+to+add+consumable");
        exit();
    }

    $stmt->close();
}

$con->close();
?>