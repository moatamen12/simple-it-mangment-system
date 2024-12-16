<?php 
include_once("Connection/connection.php");

// Handle status updates
if(isset($_POST['update_status'])) {
    $request_id = $_POST['request_id'];
    $new_status = $_POST['new_status'];
    
    $update_stmt = $con->prepare("UPDATE consumables_request SET status = ? WHERE id = ?");
    $update_stmt->bind_param("si", $new_status, $request_id);
    $update_stmt->execute();
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Get search term if submitted
$search = isset($_POST['ID']) ? $_POST['ID'] : '';

// Base query
$query = "SELECT 
    cr.id,
    cr.req_date,
    cr.description,
    cr.STOCK,
    cr.status,
    u.full_name as requester,
    c.name as consumable_name
FROM consumables_request cr
JOIN user u ON cr.user_id = u.id
JOIN consumables c ON cr.consumable_id = c.id";


if (!empty($search)) {
    $query .= " WHERE (cr.id LIKE ? 
                OR u.full_name LIKE ? 
                OR c.name LIKE ?
                OR cr.status LIKE ?)";
    $searchTerm = "%$search%";
    $params = array($searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $types = "ssss";
} else {
    $params = array();
    $types = "";
}

$query .= " ORDER BY 
    CASE cr.status 
        WHEN 'Pending' THEN 1
        WHEN 'In Progress' THEN 2
        ELSE 3
    END,
    cr.req_date DESC";

$stmt = $con->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}


$stmt->execute();
$result = $stmt->get_result();

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consumable Requests</title>
    
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="JS/lodMenue.js"></script>
</head>
<body>
    <!-- Menu Placeholder -->
    <div id="menu-placeholder"></div>

    <div class='top-search-container'>
        <header>Search Requests</header>
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

    <!-- REoai.HTML -->
    <div class="results-container">
        <h2>Consumables Requests</h2>
        <table class="requests-table">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Requester</th>
                    <th>Consumable Name</th>
                    <th>Stuck</th>
                    <th>Date</th>
                    <th>statu</th>
                    <th>Action</th>    
                </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td data-label="Request ID"><?php echo htmlspecialchars($row['id']); ?></td>
                    <td data-label="Requester"><?php echo htmlspecialchars($row['requester']); ?></td>
                    <td data-label="Consumable"><?php echo htmlspecialchars($row['consumable_name']); ?></td>
                    <td data-label="Stock Status"><?php echo htmlspecialchars($row['STOCK']); ?></td>
                    <td data-label="Date"><?php echo htmlspecialchars($row['req_date']); ?></td>
                    <td data-label="Status">
                        <span class="status-badge" style="
                            padding: 6px 12px;
                            border-radius: 4px;
                            font-weight: 500;
                            color: white;
                            background-color: <?php 
                                echo match(strtolower($row['status'])) {
                                    'pending' => '#ffa726',
                                    'in progress' => '#77c77a',
                                    'completed' => '#4CAF50',
                                    'refused' => '#f44336',
                                    default => '#808080'
                                };
                            ?>">
                            <?php echo htmlspecialchars($row['status']); ?>
                        </span>
                    </td>
                    <td data-label="Actions">
                        <?php if($row['status'] === 'Pending'): ?>
                            <button class="btn" style="background-color: #4CAF50;" data-btn-type="approve" 
                                    onclick="updateStatus(<?php echo $row['id']; ?>, 'Completed')">
                                Approve
                            </button>
                            <button class="btn" style="background-color: #f44336;" data-btn-type="reject"
                                    onclick="updateStatus(<?php echo $row['id']; ?>, 'Refused')">
                                Reject
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>