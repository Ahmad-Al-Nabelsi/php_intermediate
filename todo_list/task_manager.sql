CREATE DATABASE task_manager;

USE task_manager;

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task VARCHAR(255) NOT NULL,
    due_date DATETIME NOT NULL,
    status VARCHAR(20) DEFAULT 'pending'
);
