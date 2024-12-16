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

    <div class="content-container">
        <div class="requests-section">
            <div class="tabs">
                <button class="tab-btn active" data-tab="repair">Repair Request</button>
                <button class="tab-btn" data-tab="consumables">Consumables Request</button>
            </div>

            <!-- Repair Request Form -->
            <div class="tab-content active" id="repair">
                <form class="request-form">
                    <div class="form-group">
                        <label for="branch">Branch</label>
                        <select id="branch" name="branch" required>
                            <option value="">Select Branch</option>
                            <option value="branch1">Branch 1</option>
                            <option value="branch2">Branch 2</option>
                            <option value="branch3">Branch 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="equipment">Equipment</label>
                        <input type="text" id="equipment" name="equipment" required>
                    </div>
                    <div class="form-group">
                        <label for="issue">Issue Description</label>
                        <textarea id="issue" name="issue" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="priority">Priority</label>
                        <select id="priority" name="priority" required>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <button type="submit" class="btn">Submit Repair Request</button>
                </form>
            </div>

            <!-- Consumables Request Form -->
            <div class="tab-content" id="consumables">
                <form class="request-form">
                    <div class="form-group">
                        <label for="con-branch">Branch</label>
                        <select id="con-branch" name="branch" required>
                            <option value="">Select Branch</option>
                            <option value="branch1">Branch 1</option>
                            <option value="branch2">Branch 2</option>
                            <option value="branch3">Branch 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="item">Item Name</label>
                        <input type="text" id="item" name="item" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" name="quantity" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason</label>
                        <textarea id="reason" name="reason" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn">Submit Consumables Request</button>
                </form>
            </div>
        </div>

        <!-- Request Status Section -->
        <div class="status-section">
            <h2>Recent Requests</h2>
            <div class="status-cards">
                <div class="status-card">
                    <span class="status-badge pending">Pending</span>
                    <h3>Repair Request #REP001</h3>
                    <p>Printer Maintenance - Branch 1</p>
                    <p class="status-date">Submitted: 2024-03-15</p>
                </div>
                <div class="status-card">
                    <span class="status-badge approved">Approved</span>
                    <h3>Consumables Request #CON002</h3>
                    <p>Paper Supplies - Branch 2</p>
                    <p class="status-date">Submitted: 2024-03-14</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab switching functionality
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons and content
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                
                // Add active class to clicked button and corresponding content
                button.classList.add('active');
                document.getElementById(button.dataset.tab).classList.add('active');
            });
        });

        // Form submission handling
        document.querySelectorAll('.request-form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                // Add your form submission logic here
                alert('Request submitted successfully!');
                form.reset();
            });
        });
    </script>
</body>
</html>