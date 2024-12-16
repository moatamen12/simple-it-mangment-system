<?php
include_once("Connection/connection.php");



function getAgentLocation($con, $agent_id) {
    $location = "Unknown Location";
    
    $query = "SELECT d.location 
              FROM department d 
              INNER JOIN agent a ON d.id = a.department_id 
              WHERE a.id = ?";
              
    if($stmt = $con->prepare($query)) {
        $stmt->bind_param("i", $agent_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if(isset($row['location'])) {
                $location = $row['location'];
            }
        }
        $stmt->close();
    }
    return $location;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $consumable_id = intval($_POST['consumable_id']);
    $quantity = intval($_POST['quantity']);
    $description = $con->real_escape_string($_POST['description']);
    $stock_status = $con->real_escape_string($_POST['stock_status']);
    $user_id = $_SESSION['user_id'];
    
    // Get agent location
    $agent_location = getAgentLocation($con, $user_id);

    // Validate stock status
    $valid_stock = ['In Stock', 'Out of Stock', 'LOW IN STUCK'];
    if (!in_array($stock_status, $valid_stock)) {
        $stock_status = 'In Stock';
    }

    $stmt = $con->prepare("INSERT INTO consumables_request (consumable_id, user_id, req_date, quantity, description, status, STOCK, request_location) VALUES (?, ?, NOW(), ?, ?, 'Pending', ?, ?)");
    $stmt->bind_param("iiisss", $consumable_id, $user_id, $quantity, $description, $stock_status, $agent_location);

    if ($stmt->execute()) {
        header("Location: Agent.php?success=Consumable request submitted");
    } else {
        header("Location: Agent.php?error=Failed to submit request");
    }
    $stmt->close();
}
$con->close();
?>