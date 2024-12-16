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
    $product_id = intval($_POST['product_id']);
    $description = $con->real_escape_string($_POST['description']);
    $urgency = $con->real_escape_string($_POST['urgency']);
    $agent_id = $_SESSION['user_id'];
    
    // Get agent location
    $agent_location = getAgentLocation($con, $agent_id);

    // Validate urgency level
    $valid_urgency = ['Low', 'Medium', 'High', 'Critical'];
    if (!in_array($urgency, $valid_urgency)) {
        $urgency = 'Medium';
    }

    $stmt = $con->prepare("INSERT INTO repair_request (product_id, agent_id, req_date, description, urgency, status, request_location) VALUES (?, ?, NOW(), ?, ?, 'Pending', ?)");
    $stmt->bind_param("iisss", $product_id, $agent_id, $description, $urgency, $agent_location);
    
    if ($stmt->execute()) {
        header("Location: Agent.php?success=Repair request submitted");
    } else {
        header("Location: Agent.php?error=Failed to submit request");
    }
    $stmt->close();
}
$con->close();
?>