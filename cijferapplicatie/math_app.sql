
-- Database aanmaken (indien deze nog niet bestaat)
CREATE DATABASE IF NOT EXISTS math_app;
USE math_app;

-- Een gebruikerstabel maken
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('teacher', 'student') NOT NULL
);

-- Maak een testtabel met een nieuwe kolom "beschrijving"
CREATE TABLE IF NOT EXISTS tests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,  -- Voeg hier een beschrijving toe
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Een resultatentabel maken
CREATE TABLE IF NOT EXISTS results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    test_id INT NOT NULL,
    score DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE CASCADE
);

-- Testgegevens toevoegen (optioneel)
INSERT INTO users (name, email, password, role) VALUES 
('Leraar Ahmed', 'teacher@example.com', 'hashed_password_here', 'teacher'),
('Student Mohammed', 'student@example.com', 'hashed_password_here', 'student');

INSERT INTO tests (title, description) VALUES 
('Algebra-test', 'Een toets met vragen over lineaire en kwadratische vergelijkingen'),
('Technische test', 'Vragen over driehoeken, hoeken en oppervlakten');

