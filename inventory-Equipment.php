<?php 
include_once("Connection/connection.php");

// Get search parameters
$search = isset($_POST['ID']) ? $_POST['ID'] : '';
$type = isset($_POST['type']) ? $_POST['type'] : 'all';

// Base query with type filter
$baseQuery = "SELECT 
    'Product' as item_type,
    id as item_id,
    name,
    barcode as identifier,
    status,
    'General' as department
FROM product
UNION ALL
SELECT 
    'Consumable' as item_type,
    id as item_id,
    name,
    barcode as identifier,
    status,
    'General' as department
FROM consumables";

// Add search conditions
if (!empty($search) || $type !== 'all') {
    $conditions = [];
    $params = [];
    $types = '';

    if (!empty($search)) {
        $conditions[] = "(identifier LIKE ? OR name LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $types .= 'ss';
    }

    if ($type !== 'all') {
        $conditions[] = "item_type = ?";
        $params[] = $type === 'Products' ? 'Product' : 'Consumable';
        $types .= 's';
    }

    $query = "SELECT * FROM ($baseQuery) as combined";
    if (!empty($conditions)) {
        $query .= " WHERE " . implode(' AND ', $conditions);
    }
    
    $stmt = $con->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
} else {
    $stmt = $con->prepare($baseQuery);
}

$stmt->execute();
$result = $stmt->get_result();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Equipment</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="JS/lodMenue.js"></script>
</head>
<body>
    <div id="menu-placeholder"></div>

    <div class='top-search-container'>
        <header>Search Inventory</header>
        <form action="" method="post" class="search-form">
            <div class="search-input-group">
                <input type="search" name="ID" id="searchbar" placeholder="Enter the ID of the Equipment">
                <button type='submit' class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <div class="search-type">
                <label class="radio-label">
                    <input type="radio" name="type" value="all" checked>
                    <span>All</span>
                </label>
                <label class="radio-label">
                    <input type="radio" name="type" value="Consumables">
                    <span>Consumables</span>
                </label>
                <label class="radio-label">
                    <input type="radio" name="type" value="Products">
                    <span>Products</span>
                </label>
            </div>
        </form>
    </div>

    <div class="results-container">
        <table class="requests-table">
            <thead>
                <tr>
                    <th>Equipment ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td data-label="ID"><?php echo htmlspecialchars($row['identifier']); ?></td>
                    <td data-label="Name"><?php echo htmlspecialchars($row['name']); ?></td>
                    <td data-label="Type"><?php echo htmlspecialchars($row['item_type']); ?></td>
                    <td data-label="Department"><?php echo htmlspecialchars($row['department']); ?></td>
                    <td data-label="Status"><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <button class="btn" style="background-color: #7ed6b7;" 
                                data-btn-type="approve" 
                                data-item-id="<?php echo $row['item_id']; ?>"
                                data-item-type="<?php echo $row['item_type']; ?>">
                            Request
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>