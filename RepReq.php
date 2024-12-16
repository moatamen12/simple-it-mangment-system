<?php
include_once("Connection/connection.php");

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handle status updates
if(isset($_POST['update_status'])) {
    $request_id = $_POST['request_id'];
    $new_status = $_POST['new_status'];
    
    $update_stmt = $con->prepare("UPDATE repair_request SET status = ? WHERE id = ?");
    $update_stmt->bind_param("si", $new_status, $request_id);
    $update_stmt->execute();
    
    header("Location: RepReq.PHP");
    exit();
}


// Get search term if submitted
$search = isset($_POST['ID']) ? $_POST['ID'] : '';

// Base query
$query = "SELECT 
    rr.id,
    rr.req_date,
    rr.description,
    rr.status,
    u.full_name as requester,
    p.name as product_name
FROM repair_request rr
JOIN user u ON rr.agent_id = u.id
JOIN product p ON rr.product_id = p.id";

// Add search condition if search term exists
if (!empty($search)) {
    $query .= " WHERE (rr.id LIKE ? 
                OR u.full_name LIKE ? 
                OR p.name LIKE ?
                OR rr.status LIKE ?)";
    $searchTerm = "%$search%";
    $params = array($searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $types = "ssss";
} else {
    $params = array();
    $types = "";
}

// Add ordering to place 'Pending' and 'In Progress' first
$query .= " ORDER BY 
    CASE rr.status 
        WHEN 'Pending' THEN 1
        WHEN 'In Progress' THEN 2
        ELSE 3
    END,
    rr.req_date DESC";

// Prepare the statement
$stmt = $con->prepare($query);
if (!$stmt) {
    die("Prepare failed: " . $con->error);
}

// Bind parameters if search is used
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

// Execute and get the result
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Repair Requests</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="JS/lodMenue.js"></script>
    <style>
        .status-badge {
            padding: 6px 12px;
            border-radius: 4px;
            color: white;
            font-weight: bold;
        }
        .status-pending { background-color: #ff9800; }
        .status-in progress { background-color: #ffa726; }
        .status-approved { background-color: #4CAF50; }
        .status-rejected { background-color: #f44336; }
        .error { color: red; margin: 10px 0; }
        .btn {
            border: none;
            color: white;
            padding: 6px 12px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 2px 1px;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div id="menu-placeholder"></div>

    <div class='top-search-container'>
        <header>Search Repair Requests</header>
        <form action="" method="post" class="search-form">
            <div class="search-input-group">
                <input type="search" name="ID" id="searchbar" 
                       placeholder="Search by ID, Name, Status..." 
                       value="<?php echo htmlspecialchars($search); ?>">
                <button type='submit' class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Repair Requests Table -->
    <div class="results-container">
        <h2>Repair Requests</h2>
        <table class="requests-table">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Requester</th>
                    <th>Product</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>    
                </tr>
            </thead>
            <tbody>
                <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td data-label="Request ID"><?php echo htmlspecialchars($row['id']); ?></td>
                            <td data-label="Requester"><?php echo htmlspecialchars($row['requester']); ?></td>
                            <td data-label="Product"><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td data-label="Date"><?php echo htmlspecialchars($row['req_date']); ?></td>
                            <td data-label="Status">
                                <span class="status-badge <?php echo strtolower(str_replace(' ', '_', $row['status'])); ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td data-label="Action">
                                <?php if($row['status'] === 'Pending'): ?>
                                    <!-- Approve Form -->
                                    <form method="post" style="display: inline;" onsubmit="return confirm('Are you sure you want to approve this request?');">
                                        <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="new_status" value="Approved">
                                        <input type="hidden" name="update_status" value="1">
                                        <button type="submit" class="btn" style="background-color: #4CAF50;">
                                            Approve
                                        </button>
                                    </form>
                                    
                                    <!-- Reject Form -->
                                    <form method="post" style="display: inline;" onsubmit="return confirm('Are you sure you want to reject this request?');">
                                        <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="new_status" value="Rejected">
                                        <input type="hidden" name="update_status" value="1">
                                        <button type="submit" class="btn" style="background-color: #f44336;">
                                            Reject
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No repair requests found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>