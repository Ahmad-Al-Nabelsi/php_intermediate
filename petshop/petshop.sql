CREATE DATABASE petshop;

USE petshop;

CREATE TABLE pets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pet_name VARCHAR(255) NOT NULL,
    pet_type VARCHAR(255) NOT NULL,
    birth_date DATE NOT NULL,
    owner_name VARCHAR(255) NOT NULL
);
