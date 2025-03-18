DROP DATABASE IF EXISTS contacts_app;

CREATE DATABASE contacts_app;

USE contacts_app;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  email VARCHAR(255) UNIQUE,
  password VARCHAR(255)
);

CREATE TABLE contacts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  user_id INT NOT NULL,
  phone_number VARCHAR(255),
  favorite TINYINT(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (user_id) REFERENCES users(id) 
);

CREATE TABLE adress (
  id INT AUTO_INCREMENT PRIMARY KEY,
  adress VARCHAR(255),
  contact_id INT NOT NULL,
  FOREIGN KEY (contact_id) REFERENCES contacts(id)
);
