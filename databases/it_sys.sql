-- to ceate the tables for the system database
CREATE DATABASE it_sys
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- the user table
CREATE TABLE `user` (
    id BIGINT PRIMARY KEY AUTO_INCREMENT  AUTO_INCREMENT,
    full_name TEXT NOT NULL,
    password TEXT NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    employee_id BIGINT NOT NULL,
    start_date DATE NOT NULL,
    role ENUM('Administrator', 'Agent', 'Stock Manager') NOT NULL
);

CREATE TABLE department (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    location VARCHAR(255)
);

CREATE TABLE Agent (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    department_id BIGINT NOT NULL,
    FOREIGN KEY (id) REFERENCES `user`(id),
    FOREIGN KEY (department_id) REFERENCES department(id)
);

CREATE TABLE product (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name TEXT NOT NULL,
    barcode TEXT NOT NULL,
    in_stock BIGINT NOT NULL,
    status ENUM('Active', 'Under Repair', 'Discarded') DEFAULT 'Active',
    price decimal(10, 2) NOT NULL
);

CREATE TABLE consumables (
    id BIGINT PRIMARY KEY AUTO_INCREMENT  ,
    associated_product_id BIGINT NOT NULL,
    name TEXT NOT NULL,
    barcode TEXT NOT NULL,
    quantity BIGINT NOT NULL,
    status ENUM('Active', 'Under Repair', 'Discarded') DEFAULT 'Active',
    price decimal(10, 2) NOT NULL
);

CREATE TABLE maintenance_contract (
    contract_id BIGINT PRIMARY KEY AUTO_INCREMENT  ,
    product_id BIGINT NOT NULL,
    start_date date NOT NULL,
    end_date date NOT NULL,
    details TEXT NOT NULL,
    contract_status ENUM('Active', 'Expired', 'Pending Renewal') DEFAULT 'Active',
    FOREIGN KEY (product_id) REFERENCES product(id)
);

CREATE TABLE request (
    id BIGINT PRIMARY KEY AUTO_INCREMENT  ,
    status text CHECK (status IN ('Pending', 'In Progress', 'Completed', 'Refused')) NOT NULL,
    request_date date NOT NULL,
    approval_date date,
    reason text,
    user_id BIGINT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES `user`(id)
);

CREATE TABLE consumables_request(
    id BIGINT PRIMARY KEY AUTO_INCREMENT  ,
    consumable_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    status text CHECK (status IN ('Pending', 'In Progress', 'Completed', 'Refused')) NOT NULL,
    FOREIGN KEY (consumable_id) REFERENCES consumables(id),
    FOREIGN KEY (user_id) REFERENCES `user`(id) 
);

CREATE TABLE repair_request (
    id BIGINT PRIMARY KEY AUTO_INCREMENT  ,
    product_id BIGINT NOT NULL,
    agent_id BIGINT NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    FOREIGN KEY (product_id) REFERENCES product(id),
    FOREIGN KEY (agent_id) REFERENCES `user`(id)
);



-- insertion domy data
INSERT INTO `user` (full_name, password, email, employee_id, start_date, role) VALUES
('Alice Johnson', 'password123', 'alice.johnson@example.com', 0001, '2023-01-15', 'Administrator'),
('Bob Smith', 'securePass!456', 'bob.smith@example.com', 0002, '2023-02-20', 'Agent'),
('Carlos Martinez', 'pass789word', 'carlos.martinez@example.com', 0003, '2023-03-10', 'Stock Manager'),
('Diana Prince', 'Wonder@123', 'diana.prince@example.com', 0004, '2023-04-05', 'Agent'),
('Ethan Hunt', 'Mission*Impossible9', 'ethan.hunt@example.com', 0005, '2023-05-25', 'Agent');


INSERT INTO department (name, location) VALUES
('IT', 'Floor 1'),
('HR', 'Floor 2'),
('Finance', 'Floor 3'),
('Operations', 'Floor 4'),
('Marketing', 'Floor 5');

INSERT INTO Agent (id, department_id) VALUES
(2, 1),  -- Bob Smith assigned to IT
(4, 2),  -- Diana Prince assigned to HR
(5, 3);  -- Ethan Hunt assigned to Finance



