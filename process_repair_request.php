<?php
// filepath: process_repair_request.php
include_once("Connection/connection.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $description = $con->real_escape_string($_POST['description']);
    $agent_id = $_SESSION['user_id']; // Ensure user_id is stored in session

    $stmt = $con->prepare("INSERT INTO repair_request (product_id, agent_id, req_date, description, status) VALUES (?, ?, NOW(), ?, 'Pending')");
    $stmt->bind_param("iis", $product_id, $agent_id, $description);

    if ($stmt->execute()) {
        header("Location: Agent.php?success=Repair request submitted");
    } else {
        header("Location: Agent.php?error=Failed to submit request");
    }
    $stmt->close();
}
$con->close();
?>