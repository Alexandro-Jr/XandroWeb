CREATE DATABASE ul_financial_app;
USE ul_financial_app;

-- Table for storing receipts
CREATE TABLE receipts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    student_name VARCHAR(255) NOT NULL,
    id_number VARCHAR(50) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    date DATE NOT NULL,
    bank VARCHAR(100),
    slip_number VARCHAR(50),
    category ENUM('income', 'expense') NOT NULL,
    description TEXT,
    payment_method ENUM('cash', 'card', 'bank_transfer') NOT NULL,
    file_path VARCHAR(255), -- Stores uploaded receipt image/file path
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for storing reports
CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_income DECIMAL(10,2) DEFAULT 0.00,
    total_expenses DECIMAL(10,2) DEFAULT 0.00,
    balance DECIMAL(10,2) AS (total_income - total_expenses),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(255) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'card') NOT NULL,
    date DATE NOT NULL,
    description TEXT,
    receipt_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE payments add student_id INT NOT NULL;