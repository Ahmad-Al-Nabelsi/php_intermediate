CREATE DATABASE pao_beer;
USE pao_beer;

CREATE TABLE beers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    type VARCHAR(255),
    price DECIMAL(10, 2)
);

INSERT INTO beers (name, type, price) VALUES ('Beer1', 'Lager', 5.00);
INSERT INTO beers (name, type, price) VALUES ('Beer2', 'Ale', 6.50);
INSERT INTO beers (name, type, price) VALUES ('Beer3', 'Stout', 7.00);
