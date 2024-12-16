<?php 
include_once("Connection/connection.php");
// Validate user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$repair_query = "
    SELECT 
        rr.*, 
        p.name AS product_name, 
        u.full_name AS requester 
    FROM 
        repair_request rr
    JOIN 
        product p ON rr.product_id = p.id
    JOIN 
        `user` u ON rr.agent_id = u.id
    ORDER BY 
        rr.req_date DESC
";

$repair_result = $con->query($repair_query);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Manager Dashboard</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="JS/lodMenue.js"></script>
</head>
<body>
    <!-- Menu Placeholder -->
    <div id="menu-placeholder"></div>



    <!-- Main Content -->
    <div class="content-container">
        <!-- Tabs Navigation -->
        <div class="tabs">
            <button class="tab-btn active" data-tab="inventory">Inventory</button>
            <button class="tab-btn " data-tab="repairs">Repair Requests</button>
            <button class="tab-btn" data-tab="parts">Parts Request</button>
            <button class="tab-btn" data-tab="reports">Repair Reports</button>
        </div>

        <!-- Inventory Management Tab -->
        <div class="tab-content active" id="inventory">
            <div class="section-header">
                <h2>Inventory Management</h2>
                <div class="btn-group">
                    <button class="btn" onclick="showAddProductForm()">
                        <i class="fas fa-plus"></i> Add Product
                    </button>
                    <button class="btn" onclick="showAddConsumableForm()">
                        <i class="fas fa-plus"></i> Add Consumable
                    </button>
                </div>
            </div>

            <!-- Sub-tabs -->
            <div class="sub-tabs">
                <button class="sub-tab-btn active" data-subtab="products">Products</button>
                <button class="sub-tab-btn" data-subtab="consumables">Consumables</button>
            </div>

            <!-- Products Table -->
            <div class="sub-tab-content active" id="products">
            <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Barcode</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Price (€)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch Consumables
                        $consumables_query = "
                            SELECT * FROM product ORDER BY id DESC";
                        $consumables_result = $con->query($consumables_query);

                        if ($consumables_result && $consumables_result->num_rows > 0):
                            while($row = $consumables_result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['barcode']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td>
                                <span class="status-badge <?php echo strtolower(str_replace(' ', '-', $row['status'])); ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars(number_format($row['price'], 2)); ?></td>
                            <td>
                                <button class="btn-icon" onclick="showEditConsumableForm(
                                    '<?php echo $row['id']; ?>',
                                    '<?php echo addslashes($row['name']); ?>',
                                    '<?php echo addslashes($row['barcode']); ?>',
                                   '<?php echo $row['quantity']; ?>',
                                    '<?php echo $row['status']; ?>',
                                    '<?php echo $row['price']; ?>'
                                )">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="delete_consumable.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this consumable?');" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn-icon">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php
                            endwhile;
                        else:
                        ?>
                        <tr><td colspan="8">No consumables found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Consumables Table -->
            <div class="sub-tab-content" id="consumables">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Barcode</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Associated Product</th>
                            <th>Price (€)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch Consumables
                        $consumables_query = "
                            SELECT * FROM consumables ORDER BY id DESC";
                        $consumables_result = $con->query($consumables_query);

                        if ($consumables_result && $consumables_result->num_rows > 0):
                            while($row = $consumables_result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['barcode']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td>
                                <span class="status-badge <?php echo strtolower(str_replace(' ', '-', $row['status'])); ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($row['price'], 2)); ?></td>
                            <td>
                                <button class="btn-icon" onclick="showEditConsumableForm(
                                    '<?php echo $row['id']; ?>',
                                    '<?php echo addslashes($row['name']); ?>',
                                    '<?php echo addslashes($row['barcode']); ?>',
                                    '<?php echo $row['quantity']; ?>',
                                    '<?php echo $row['status']; ?>',
                                    '<?php echo $row['associated_product_id']; ?>',
                                    '<?php echo $row['price']; ?>'
                                )">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="delete_consumable.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this consumable?');" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn-icon">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php
                            endwhile;
                        else:
                        ?>
                        <tr><td colspan="8">No consumables found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Add Product Modal -->
        <div id="addProductModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeAddProductForm()">&times;</span>
                <h2>Add New Product</h2>
                <form action="add_product.php" method="POST">
                    <label for="product_name">Name:</label>
                    <input type="text" id="product_name" name="name" required>

                    <label for="product_barcode">Barcode:</label>
                    <input type="text" id="product_barcode" name="barcode" required>

                    <label for="product_quantity">Quantity:</label>
                    <input type="number" id="product_quantity" name="quantity" min="0" required>

                    <label for="product_status">Status:</label>
                    <select id="product_status" name="status" required>
                        <option value="Active">Active</option>
                        <option value="Under Repair">Under Repair</option>
                        <option value="Discarded">Discarded</option>
                    </select>

                    <label for="product_price">Price (€):</label>
                    <input type="number" id="product_price" name="price" step="0.01" min="0" required>

                    <button type="submit" class="btn">Add Product</button>
                </form>
            </div>
        </div>


        <!-- Add Consumable Modal -->
        <div id="addConsumableModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeAddConsumableForm()">&times;</span>
                <h2>Add New Consumable</h2>
                <form action="add_consumable.php" method="POST">
                    <label for="consumable_name">Name:</label>
                    <input type="text" id="consumable_name" name="name" required>

                    <label for="consumable_barcode">Barcode:</label>
                    <input type="text" id="consumable_barcode" name="barcode" required>

                    <label for="consumable_quantity">Quantity:</label>
                    <input type="number" id="consumable_quantity" name="quantity" min="0" required>

                    <label for="consumable_status">Status:</label>
                    <select id="consumable_status" name="status" required>
                        <option value="Active">Active</option>
                        <option value="Under Repair">Under Repair</option>
                        <option value="Discarded">Discarded</option>
                    </select>

                    <label for="associated_product_id">Associated Product:</label>
                    <select id="associated_product_id" name="associated_product_id" required>
                        <option value="">Select a Product</option>
                        <?php
                        // Fetch products for dropdown
                        $products_query = "SELECT id, name FROM product";
                        $products_result = $con->query($products_query);
                        while($product = $products_result->fetch_assoc()) {
                            echo "<option value='".htmlspecialchars($product['id'])."'>".htmlspecialchars($product['name'])."</option>";
                        }
                        ?>
                    </select>

                    <label for="consumable_price">Price (€):</label>
                    <input type="number" id="consumable_price" name="price" step="0.01" min="0" required>

                    <button type="submit" class="btn">Add Consumable</button>
                </form>
            </div>
        </div>

        <!-- Edit Consumable Modal -->
        <div id="editConsumableModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeEditConsumableForm()">&times;</span>
                <h2>Edit Consumable</h2>
                <form action="edit_consumable.php" method="POST">
                    <input type="hidden" id="edit_consumable_id" name="id">

                    <label for="edit_consumable_name">Name:</label>
                    <input type="text" id="edit_consumable_name" name="name" required>

                    <!-- Other fields similar to Add Consumable Modal -->

                    <button type="submit" class="btn">Update Consumable</button>
                </form>
            </div>
        </div>

















        <!-- Repair Requests Tab -->
        <div class="tab-content " id="repairs">
            <div class="section-header">
                <h2>Repair Requests</h2>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Requester</th>
                        <th>Submitted Date</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($repair_result && $repair_result->num_rows > 0): ?>
                        <?php while($row = $repair_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['requester']); ?></td>
                                <td><?php echo htmlspecialchars($row['req_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['DESCRIPTION']); ?></td>
                                <td>
                                    <span class="status-badge <?php echo strtolower(str_replace(' ', '-', $row['status'])); ?>">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn validate-btn" style="background-color: #4CAF50" onclick="updateRepairStatus(<?php echo $row['id']; ?>, 'Approved')">Validate</button>
                                    <button class="btn refuse-btn"   style="background-color:#e21b0c" onclick="updateRepairStatus(<?php echo $row['id']; ?>, 'Rejected')">Refuse</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7">No repair requests found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php
        $con->close();
        ?>

        <!-- Parts Request Tab -->
        <div class="tab-content" id="parts">
            <div class="section-header">
                <h2>Parts Request</h2>
                <button class="btn" onclick="showPartsRequestForm()">
                    <i class="fas fa-plus"></i> New Request
                </button>
            </div>
            <form class="parts-request-form">
                <div class="form-group">
                    <label>Part Name</label>
                    <input type="text" required>
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" min="1" required>
                </div>
                <div class="form-group">
                    <label>Priority</label>
                    <select required>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea rows="3"></textarea>
                </div>
                <button type="submit" class="btn">Submit Request</button>
            </form>
        </div>

        <!-- Repair Reports Tab -->
        <div class="tab-content" id="reports">
            <div class="section-header">
                <h2>Repair Reports</h2>
            </div>
            <div class="reports-list">
                <div class="report-card">
                    <h3>Repair Report #REP-001</h3>
                    <div class="report-details">
                        <p><strong>Equipment:</strong> HP Printer</p>
                        <p><strong>Issue:</strong> Paper jam mechanism</p>
                        <p><strong>Parts Used:</strong> Roller assembly</p>
                        <p><strong>Status:</strong> <span class="status-badge completed">Completed</span></p>
                        <p><strong>Date:</strong> 2024-03-15</p>
                        <div class="report-actions">
                            <button class="btn" onclick="viewReport('REP-001')">View Details</button>
                            <button class="btn" onclick="downloadReport('REP-001')">Download PDF</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab Switching
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                
                button.classList.add('active');
                document.getElementById(button.dataset.tab).classList.add('active');
            });
        });

        // Inventory Management Functions
        function showAddItemForm() {
            // Implementation for adding new item
            console.log('Showing add item form');
        }

        function editItem(id) {
            // Implementation for editing item
            console.log('Editing item:', id);
        }

        function deleteItem(id) {
            if(confirm('Are you sure you want to delete this item?')) {
                // Implementation for deleting item
                console.log('Deleting item:', id);
            }
        }

        // Report Management Functions
        function viewReport(id) {
            // Implementation for viewing report details
            console.log('Viewing report:', id);
        }

        function downloadReport(id) {
            // Implementation for downloading report as PDF
            console.log('Downloading report:', id);
        }


        // Sub-tabs handling
        document.querySelectorAll('.sub-tab-btn').forEach(button => {
            button.addEventListener('click', () => {
                const subTab = button.getAttribute('data-subtab');

                document.querySelectorAll('.sub-tab-btn').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.sub-tab-content').forEach(content => content.classList.remove('active'));

                button.classList.add('active');
                document.getElementById(subTab).classList.add('active');
            });
        });

        // Modal functions
        function showAddProductForm() {
            document.getElementById('addProductModal').style.display = 'block';
        }

        function closeAddProductForm() {
            document.getElementById('addProductModal').style.display = 'none';
        }

        function showAddConsumableForm() {
            document.getElementById('addConsumableModal').style.display = 'block';
        }

        function closeAddConsumableForm() {
            document.getElementById('addConsumableModal').style.display = 'none';
        }


        // Close modals when clicking outside
        window.onclick = function(event) {
            var addProductModal = document.getElementById('addProductModal');
            var addConsumableModal = document.getElementById('addConsumableModal');
            if (event.target == addProductModal) {
                addProductModal.style.display = "none";
            }
            if (event.target == addConsumableModal) {
                addConsumableModal.style.display = "none";
            }
        }




        
        function showEditConsumableForm(id, name, barcode, quantity, status, associated_product_id, price) {
            document.getElementById('editConsumableModal').style.display = 'block';
            document.getElementById('edit_consumable_id').value = id;
            document.getElementById('edit_consumable_name').value = name;
            // Set other fields similarly
        }

        function closeEditConsumableForm() {
            document.getElementById('editConsumableModal').style.display = 'none';
        }

    </script>
</body>
</html>