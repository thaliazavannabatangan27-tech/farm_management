CREATE DATABASE IF NOT EXISTS farm_management;
USE farm_management;

-- ==========================
-- USERS TABLE
-- ==========================
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(150),
    name VARCHAR(150),
    role VARCHAR(50) DEFAULT 'Admin'
);

-- Default admin account
INSERT INTO users (username, password, email, name, role)
VALUES ('admin', '123', 'admin@gmail.com', 'Administrator', 'Admin');



-- ==========================
-- CROPS TABLE
-- ==========================
DROP TABLE IF EXISTS crops;
CREATE TABLE crops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    crop_name VARCHAR(150) NOT NULL,
    crop_type VARCHAR(100),
    quantity INT DEFAULT 0,
    date_planted DATE,
    date_harvest DATE
);



-- ==========================
-- TASKS TABLE
-- ==========================
DROP TABLE IF EXISTS tasks;
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_name VARCHAR(200) NOT NULL,
    description TEXT,
    assigned_to VARCHAR(150),
    due_date DATE,
    status VARCHAR(50) DEFAULT 'Pending'
);



-- ==========================
-- RESOURCES TABLE
-- ==========================
DROP TABLE IF EXISTS resources;
CREATE TABLE resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    resource_name VARCHAR(150) NOT NULL,
    category VARCHAR(100),
    quantity INT DEFAULT 0
);
