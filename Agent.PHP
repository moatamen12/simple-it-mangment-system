<?php

include_once("Connection/connection.php");
// Validate user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}



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

// Function to get last five requests
function getLastFiveRequests($con, $agent_id) {
    $requests = array();
    
    $query = "(SELECT 'Consumable' as type, cr.req_date, cr.status, c.name as item_name, 
              cr.quantity, cr.description, cr.STOCK, cr.request_location
              FROM consumables_request cr 
              JOIN consumables c ON cr.consumable_id = c.id 
              WHERE cr.user_id = ?)
              UNION ALL
              (SELECT 'Repair' as type, rr.req_date, rr.status, p.name as item_name,
              1 as quantity, rr.description, '' as STOCK, rr.request_location
              FROM repair_request rr 
              JOIN product p ON rr.product_id = p.id 
              WHERE rr.agent_id = ?)
              ORDER BY req_date DESC LIMIT 5";
              
    if($stmt = $con->prepare($query)) {
        $stmt->bind_param("ii", $agent_id, $agent_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
        $stmt->close();
    }
    return $requests;
}

// Get agent ID from session
$agent_id = $_SESSION['user_id'];

// Get agent location
$agent_location = getAgentLocation($con, $agent_id);

// Get last five requests
$last_requests = getLastFiveRequests($con, $agent_id);

// Initialize if query returns null
if (!$last_requests) {
    $last_requests = array();
}


// Get agent's location from department
$agent_id = $_SESSION['user_id'];
$agent_location = getAgentLocation($con, $agent_id);

// Existing code for consumables and products queries
$consumables_query = "SELECT id, name FROM consumables WHERE status = 'Active'";
$consumables_result = $con->query($consumables_query);

$products_query = "SELECT id, name FROM product WHERE status = 'Active'";
$products_result = $con->query($products_query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agent Dashboard</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="JS/lodMenue.js"></script>
</head>
<body>
    <!-- Menu Placeholder -->
    <div id="menu-placeholder"></div>


   <!-- Tab Navigation -->
    <div class="content-container">
        <div class="tabs">
            <button class="tab-btn active" data-tab="consumables-form">Request Consumable</button>
            <button class="tab-btn" data-tab="repairs-form">Request Repair</button>
            <button class="tab-btn" data-tab="recent-requests">Recent Requests</button>
        </div>

        <!-- Consumables Request Form -->
        <div class="tab-content active" id="consumables-form">
            <form class="request-form" action="process_consumable_request.php" method="POST">
                <h2>Request Consumable</h2>
                <div class="form-group">
                    <label>Select Consumable:</label>
                    <select name="consumable_id" required>
                        <?php while ($row = $consumables_result->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>">
                                <?php echo htmlspecialchars($row['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <textarea name="description" placeholder="Request Description" required></textarea>
                </div>
                

                <div class="form-group">
                    <select name="stock_status" required>
                        <option value="In Stock">In Stock</option>
                        <option value="Out of Stock">Out of Stock</option>
                        <option value="LOW IN STUCK">Low in Stock</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Quantity:</label>
                    <input type="number" name="quantity" min="1" required>
                </div>
                <button type="submit" class="btn">Submit Request</button>
            </form>
        </div>    

        <!-- Repair Request Form -->
        <div class="tab-content" id="repairs-form">
            <form class="request-form" action="process_repair_request.php" method="POST">
                <h2>Request Repair</h2>
                <div class="form-group">
                    <label>Select Product:</label>
                    <select name="product_id" required>
                        <?php while ($row = $products_result->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>">
                                <?php echo htmlspecialchars($row['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <select name="urgency" required>
                        <option value="Low">Low</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="High">High</option>
                        <option value="Critical">Critical</option>
                    </select>
                </div>
                <button type="submit" class="btn">Submit Request</button>
            </form>
        </div>
   



   <!-- New Recent Requests Tab -->
   <div id="recent-requests" class="tab-content">
        <div class="recent-requests">
            <h2>Recent Requests</h2>
            <div class="requests-grid">
                <?php if (empty($last_requests)): ?>
                    <div class="no-requests">No recent requests found.</div>
                <?php else: ?>
                    <?php foreach($last_requests as $request): ?>
                        <div class="request-card <?php echo strtolower(str_replace(' ', '-', $request['status'])); ?>">
                            <div class="card-header">
                                <span class="request-type <?php echo strtolower($request['type']); ?>">
                                    <?php echo htmlspecialchars($request['type']); ?>
                                </span>
                                <span class="status-badge <?php echo strtolower(str_replace(' ', '-', $request['status'])); ?>">
                                    <?php echo htmlspecialchars($request['status']); ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <p><strong>Item:</strong> <?php echo htmlspecialchars($request['item_name']); ?></p>
                                <p><strong>Date:</strong> <?php echo htmlspecialchars($request['req_date']); ?></p>
                                <p><strong>Location:</strong> <?php echo htmlspecialchars($request['request_location']); ?></p>
                                <p><strong>Description:</strong> <?php echo htmlspecialchars($request['description']); ?></p>
                                <?php if($request['type'] == 'Consumable'): ?>
                                    <p><strong>Quantity:</strong> <?php echo htmlspecialchars($request['quantity']); ?></p>
                                    <p><strong>Stock Status:</strong> <?php echo htmlspecialchars($request['STOCK']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

    
    <script>
    // Tab Switching Script
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const target = button.getAttribute('data-tab');

                // Remove active class from all buttons and contents
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                // Add active class to clicked button and target content
                button.classList.add('active');
                document.getElementById(target).classList.add('active');
            });
        });
    });
    </script>
</script>
</body>
</html>