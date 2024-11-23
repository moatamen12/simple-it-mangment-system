-- to ceate the tables for the system database
CREATE DATABASE it_sys
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;


-- table for Equipment
CREATE TABLE Equipment (
    equipmentID INT PRIMARY KEY AUTO_INCREMENT,
    type ENUM('Consumables', 'Products') NOT NULL,
    name VARCHAR(255) NOT NULL,
    purchaseDate DATE NOT NULL,
    warrantyEndDate DATE,
    price DECIMAL(10,2) NOT NULL,
    status ENUM('Active', 'Under Repair', 'Discarded') NOT NULL,
    INDEX idx_type (type)
);

-- table for Consumables
CREATE TABLE Consumables (
    consumableID INT PRIMARY KEY AUTO_INCREMENT,
    associatedProdID INT NOT NULL,
    threshold_quantity INT DEFAULT 0,
    reorder_quantity INT DEFAULT 0,
    FOREIGN KEY (consumableID) REFERENCES Equipment(equipmentID),
    FOREIGN KEY (associatedProdID) REFERENCES Products(productID),
    INDEX idx_associated_prod (associatedProdID)
);

-- table for Products
CREATE TABLE Products (
    productID INT PRIMARY KEY,
    barcode VARCHAR(255),
    quantity INT DEFAULT 0,
    FOREIGN KEY (productID) REFERENCES Equipment(equipmentID),
    INDEX idx_barcode (barcode)
);

-- table for Employee 
CREATE TABLE Employee (
    employeeID INT PRIMARY KEY AUTO_INCREMENT,
    fullName VARCHAR(255) NOT NULL,
    role ENUM('Stock Manager', 'Administrator', 'Agent', 'Repairer') NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    Employee_image varchar(1024),
    -- is_first_login BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_role (role)
);


CREATE TABLE Permissions (
    permissionID INT PRIMARY KEY AUTO_INCREMENT,
    permission_name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Role Permissions mapping
CREATE TABLE RolePermissions (
    roleID ENUM('Stock Manager', 'Administrator', 'Agent', 'Repairer'),
    permissionID INT,
    PRIMARY KEY (roleID, permissionID),
    FOREIGN KEY (permissionID) REFERENCES Permissions(permissionID)
);

-- Insert base permissions into the Permissions table TO
INSERT INTO Permissions (permission_name, description) VALUES
('view_equipment', 'Can view equipment details'),
('add_equipment', 'Can add new equipment'),
('edit_equipment', 'Can modify equipment details'),
('delete_equipment', 'Can remove equipment'),

('create_repair_request', 'Can create repair requests'),
('approve_repair_request', 'Can approve repair requests'),
('manage_users', 'Can manage user accounts'),
('view_reports', 'Can view system reports'),
('create_reports', 'Can create new reports'),

('add_eployees','Can remove equipment'),
('removing_employees','Can remove equipment'),
('view_employees','Can view equipment'),
('edit_employees','Can chang employees rols and ditals');


-- Assign permissions to roles
INSERT INTO RolePermissions (roleID, permissionID) VALUES
-- Administrator permissions (full access)
('Administrator', 1), ('Administrator', 2), ('Administrator', 3), 
('Administrator', 4), ('Administrator', 5), ('Administrator', 6),
('Administrator', 7), ('Administrator', 8), ('Administrator', 9),
('Administrator', 10), ('Administrator', 11), ('Administrator', 12),
('Administrator', 13);

-- Stock Manager permissions
('Stock Manager', 1), ('Stock Manager', 2), ('Stock Manager', 3),
('Stock Manager', 8), ('Stock Manager', 9),

-- Agent permissions
('Agent', 1), ('Agent', 5), ('Agent', 8),

-- Repairer permissions
('Repairer', 1), ('Repairer', 3), ('Repairer', 5), ('Repairer', 9);

--TO SHOW PERMISSIONS FOR EMPLOYEES
CREATE VIEW employee_permissions AS
SELECT 
    e.employeeID,
    e.fullName,
    e.role,
    GROUP_CONCAT(p.permission_name) as permissions
FROM 
    Employee e
    JOIN RolePermissions rp ON e.role = rp.roleID
    JOIN Permissions p ON rp.permissionID = p.permissionID
GROUP BY 
    e.employeeID;

-- table for Report
CREATE TABLE Report (
    reportID VARCHAR(255) PRIMARY KEY,
    repairDetails TEXT,
    productID INT,
    createdDate DATE ,
    createdBy VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (productID) REFERENCES Equipment(equipmentID)
);

-- table for Maintenance Contract
CREATE TABLE MaintenanceContract (
    contractID VARCHAR(36) PRIMARY KEY,
    productID INT NOT NULL,    
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (productID) REFERENCES Equipment(equipmentID)
);

-- table for Repair Request
CREATE TABLE RepairRequest (
    requestID INT PRIMARY KEY AUTO_INCREMENT,
    equipmentID INT NOT NULL,
    requestedBy INT NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (equipmentID) REFERENCES Equipment(equipmentID),
    FOREIGN KEY (requestedBy) REFERENCES Employee(employeeID)
);




-- table for Stock Manager
-- CREATE TABLE StockManager (
--     SM_ID INT PRIMARY KEY,
--     userName VARCHAR(255),
--     password VARCHAR(255),
--     FOREIGN KEY (SM_ID) REFERENCES Employee(employeeID)
-- );

-- -- table for Agent
-- CREATE TABLE Agent (
--     agentID INT PRIMARY KEY,
--     userName VARCHAR(255),
--     password VARCHAR(255),
--     FOREIGN KEY (agentID) REFERENCES Employee(employeeID)
-- );

-- -- table for Administrator
-- CREATE TABLE Administrator (
--     adminID INT PRIMARY KEY,
--     userName VARCHAR(255),
--     password VARCHAR(255),
--     FOREIGN KEY (adminID) REFERENCES Employee(employeeID)
-- );
-- Create Permissions table