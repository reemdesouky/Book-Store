CREATE DATABASE Order_Processing_System;
USE Order_Processing_System;

CREATE TABLE Publisher(
    publisher_id INT PRIMARY KEY AUTO_INCREMENT,
    publisher_name VARCHAR(25) NOT NULL,
    publisher_address VARCHAR(100),
    phone_number VARCHAR(11)
);

CREATE TABLE Category (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name ENUM('Science','Art','Religion','History','Geography') UNIQUE NOT NULL
);

CREATE TABLE Author(
    author_id INT PRIMARY KEY AUTO_INCREMENT,
    author_name VARCHAR(50) NOT NULL
);

CREATE TABLE Book (
    ISBN VARCHAR(13) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    modify_date DATETIME,
    Threshold INT CHECK (Threshold >= 0),
    quantity INT CHECK (quantity >= 0),
    publish_year YEAR,
    publisher_id INT,
    category_id INT,
    FOREIGN KEY (publisher_id) REFERENCES Publisher(publisher_id),
    FOREIGN KEY (category_id) REFERENCES Category(category_id)
);

CREATE TABLE Written_By(
    ISBN VARCHAR(13),
    author_id INT,
    PRIMARY KEY (ISBN , author_id),
    FOREIGN KEY (ISBN) REFERENCES Book(ISBN),
    FOREIGN KEY (author_id) REFERENCES Author(author_id)
);

CREATE TABLE Admins(
    admin_id INT PRIMARY KEY AUTO_INCREMENT,
    admin_name VARCHAR(25),
    email VARCHAR(40) UNIQUE,
    admin_password VARCHAR(255) NOT NULL


);

CREATE TABLE Publisher_Order (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    admin_id INT,
    order_status ENUM('Pending','Confirmed') DEFAULT 'Pending',
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES Admins(admin_id)
);

CREATE TABLE Pub_Order_Contains(
    ISBN VARCHAR(13),
    quantity INT NOT NULL,
    order_id INT,
    PRIMARY KEY(ISBN, order_id),
    FOREIGN KEY (ISBN) REFERENCES Book(ISBN),
    FOREIGN KEY (order_id) REFERENCES Publisher_Order(order_id)
);

CREATE TABLE Customer(
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    Fname VARCHAR(20),
    Lname VARCHAR(20),
    user_name VARCHAR(40) UNIQUE,
    email VARCHAR(40),
    customer_password VARCHAR(255) NOT NULL,
    customer_address VARCHAR(100),
    phone_number VARCHAR(11)
);

CREATE TABLE Shopping_Cart(
    cart_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT,
    FOREIGN KEY (customer_id) REFERENCES Customer(customer_id)

);

CREATE TABLE Cart_Content (
    cart_id INT,
    ISBN VARCHAR(13),
    quantity INT CHECK (quantity > 0),
    PRIMARY KEY (cart_id, ISBN),
    FOREIGN KEY (cart_id) REFERENCES Shopping_Cart(cart_id),
    FOREIGN KEY (ISBN) REFERENCES Book(ISBN)
);


CREATE TABLE Customer_Order (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_price DECIMAL(10,2),
    FOREIGN KEY (customer_id) REFERENCES Customer(customer_id)
);

CREATE TABLE Order_Item (
    order_id INT,
    ISBN VARCHAR(13),
    quantity INT,
    PRIMARY KEY (order_id, ISBN),
    FOREIGN KEY (order_id) REFERENCES Customer_Order(order_id),
    FOREIGN KEY (ISBN) REFERENCES Book(ISBN)
);








