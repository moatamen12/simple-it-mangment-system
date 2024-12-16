<?php
// filepath: process_consumable_request.php
include_once("Connection/connection.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $consumable_id = intval($_POST['consumable_id']);
    $quantity = intval($_POST['quantity']);
    $user_id = $_SESSION['user_id']; // Ensure user_id is stored in session

    $stmt = $con->prepare("INSERT INTO consumables_request (consumable_id, user_id, req_date, quantity, status) VALUES (?, ?, NOW(), ?, 'Pending')");
    $stmt->bind_param("iii", $consumable_id, $user_id, $quantity);

    if ($stmt->execute()) {
        header("Location: Agent.php?success=Consumable request submitted");
    } else {
        header("Location: Agent.php?error=Failed to submit request");
    }
    $stmt->close();
}
$con->close();
?>