<?php
// filepath: /d:/XAMPP/htdocs/gl-projet/add_product.php
include_once("Connection/connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $con->real_escape_string($_POST['name']);
    $barcode = $con->real_escape_string($_POST['barcode']);
    $quantity = intval($_POST['quantity']);
    $status = $con->real_escape_string($_POST['status']);
    $price = floatval($_POST['price']);

    $stmt = $con->prepare("INSERT INTO product (name, barcode, in_stock, status, price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisd", $name, $barcode, $quantity, $status, $price);

    if ($stmt->execute()) {
        header("Location: STOK-MAniger.php?message=Product+added+successfully");
        exit();
    } else {
        header("Location: STOK-MAniger.php?error=Failed+to+add+product");
        exit();
    }

    $stmt->close();
}

$con->close();
?>