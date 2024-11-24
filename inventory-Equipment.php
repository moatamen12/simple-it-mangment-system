<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include 'Conection/connection.php';
$result = null;
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $searchId = isset($_POST['ID']) ? trim($_POST['ID']) : '';
    $searchType = isset($_POST['type']) ? $_POST['type'] : 'all';


    //bases quary
    if($searchType ==='all'){
        $query = "SELECT * FROM Equipment WHERE equipmentID = ?";
    }else if($searchType === 'consumable'){
        $query = "SELECT * FROM Equipment WHERE equipmentID = ? AND type = ?";
    }

    //prepare statment for each case
    $stmt = mysqli_prepare($con, $query);
    if($stmt){
        $searchTerm = "%$searchId%";
        if($searchType === 'all'){
            mysqli_stmt_bind_param($stmt, 's', $searchTerm);
        }else{
            mysqli_stmt_bind_param($stmt, 'ss', $searchTerm, $searchType);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

    } else {
        echo "Error preparing query: " . mysqli_error($con);
    }
} 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Equipment</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class='top-search-container'>
        <header class="hdr">Search Inventory </header>
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
                    <input type="radio" name="type" value="consumable">
                    <span>Consumables</span>
                </label>
                <label class="radio-label">
                    <input type="radio" name="type" value="product">
                    <span>Products</span>
                </label>
            </div>
        </form>
                    <!-- Display results -->
        <?php if ($result !== null): ?>
        <div class="results-container">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Purchase Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['equipmentID']); ?></td>
                                <td><?php echo htmlspecialchars($row['type']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['purchaseDate']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No results found.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    </div>




    
</body>
</html>