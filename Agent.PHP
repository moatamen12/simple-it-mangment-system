<?php
include_once("Connection/connection.php");

// Fetch consumables for the dropdown
$consumables_query = "SELECT id, name FROM consumables WHERE status = 'Active'";
$consumables_result = $con->query($consumables_query);

// Fetch products for the repair request
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
                <button type="submit" class="btn">Submit Request</button>
            </form>
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