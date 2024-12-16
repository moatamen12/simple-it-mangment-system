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
            <button class="tab-btn" data-tab="repairs">Repair Requests</button>
            <button class="tab-btn" data-tab="parts">Parts Request</button>
            <button class="tab-btn" data-tab="reports">Repair Reports</button>
        </div>

        <!-- Inventory Management Tab -->
        <div class="tab-content active" id="inventory">
            <div class="section-header">
                <h2>Inventory Management</h2>
                <button class="btn" onclick="showAddItemForm()">
                    <i class="fas fa-plus"></i> Add Item
                </button>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>ITEM-001</td>
                        <td>Printer Ink</td>
                        <td>Consumable</td>
                        <td>15</td>
                        <td><span class="status-badge in-stock">In Stock</span></td>
                        <td>
                            <button class="btn-icon" onclick="editItem('ITEM-001')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon" onclick="deleteItem('ITEM-001')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Repair Requests Tab -->
        <div class="tab-content" id="repairs">
            <div class="section-header">
                <h2>Repair Requests</h2>
            </div>
            <div class="request-cards">
                <div class="request-card">
                    <span class="request-type repair">Repair Request #REP-001</span>
                    <h3>Printer Maintenance</h3>
                    <p><i class="fas fa-user"></i> Requester: John Doe</p>
                    <p><i class="fas fa-clock"></i> Submitted: 2024-03-15</p>
                    <p><i class="fas fa-info-circle"></i> Status: Pending</p>
                    <div class="request-actions">
                        <button class="btn" data-btn-type="approve">Validate</button>
                        <button class="btn" data-btn-type="reject">Refuse</button>
                    </div>
                </div>
            </div>
        </div>

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
    </script>
</body>
</html>