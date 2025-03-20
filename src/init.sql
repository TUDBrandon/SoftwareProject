-- Drop tables if they exist to avoid errors
DROP TABLE IF EXISTS report;
DROP TABLE IF EXISTS submission;
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS employees;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS admins;

-- Create users table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(45) NOT NULL,
    email VARCHAR(45) NOT NULL,
    first_name VARCHAR(45) NOT NULL,
    age INT,
    phone_number VARCHAR(45),
    password VARCHAR(255) NOT NULL
);

-- Create employees table
CREATE TABLE employees (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(45) NOT NULL,
    last_name VARCHAR(45) NOT NULL,
    email VARCHAR(45) NOT NULL,
    phone_number VARCHAR(45),
    password VARCHAR(255) NOT NULL
);

-- Create admins table
CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(45) NOT NULL,
    email VARCHAR(45) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Create products table
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    category VARCHAR(45) NOT NULL,
    link VARCHAR(255) NOT NULL
);

-- Create submission table
CREATE TABLE submission (
    submission_id INT AUTO_INCREMENT PRIMARY KEY,
    timestamps DATETIME DEFAULT CURRENT_TIMESTAMP,
    username VARCHAR(45) NOT NULL,
    email VARCHAR(45) NOT NULL,
    title VARCHAR(45) NOT NULL,
    category VARCHAR(45) NOT NULL,
    description VARCHAR(200) NOT NULL,
    images BLOB,
    status_update VARCHAR(45) DEFAULT 'Pending'
);

-- Create report table
CREATE TABLE report (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    timestamps DATETIME DEFAULT CURRENT_TIMESTAMP,
    username VARCHAR(45) NOT NULL,
    title VARCHAR(45) NOT NULL,
    date DATETIME,
    expectation VARCHAR(200),
    description VARCHAR(200) NOT NULL,
    technical VARCHAR(200),
    item_image BLOB,
    receipt_image BLOB,
    status_update VARCHAR(45) DEFAULT 'Pending',
    employee_id INT,
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id)
);

-- Create transactions table
CREATE TABLE transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    transaction_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    amount DECIMAL(10, 2) NOT NULL,
    status VARCHAR(45) DEFAULT 'Completed',
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- Insert sample products from the hardcoded data
-- Hardware products
INSERT INTO products (name, price, image, category, link) VALUES
('MSI RX570 8GB', 120.00, 'images/msi370.jpg', 'Hardware', 'msi-rx570.php'),
('Intel Core i7 13th Gen', 399.99, 'images/CPUi713th.jpg', 'Hardware', 'CPUi713th.php'),
('NVIDIA RTX 4090', 1599.00, 'images/rtx4090.jpg', 'Hardware', 'rtx4090.php'),
('NVIDIA RTX 5090', 1999.00, 'images/rtx5090.jpg', 'Hardware', 'rtx5090.php'),
('Intel Core i9', 549.99, 'images/inteli9.jpg', 'Hardware', 'intel-i9.php');

-- Console products
INSERT INTO products (name, price, image, category, link) VALUES
('PlayStation 5', 499.00, 'images/ps5.jpg', 'Consoles', 'ps5.php'),
('PlayStation 4', 299.99, 'images/ps4.jpg', 'Consoles', 'ps4.php'),
('Nintendo Switch', 299.00, 'images/switch.jpg', 'Consoles', 'nintendo-switch.php'),
('Xbox Series X', 499.99, 'images/xboxX.png', 'Consoles', 'xbox-series-x.php'),
('Meta Quest 2', 299.99, 'images/oculus2.jpg', 'Consoles', 'oculus-quest2.php');

-- Phone products
INSERT INTO products (name, price, image, category, link) VALUES
('iPhone 14', 799.00, 'images/iphone14.jpg', 'Phones', 'iphone14.php'),
('iPhone 14 - Black', 799.00, 'images/black_iphone14.jpg', 'Phones', 'black-iphone14.php'),
('iPhone 15 - Green', 899.00, 'images/green_iphone15.jpg', 'Phones', 'green-iphone15.php'),
('iPhone 15 Pro Max', 1199.00, 'images/iphone15proMax.jpg', 'Phones', 'iphone15-promax.php'),
('Samsung Galaxy S23 Ultra', 1199.00, 'images/samsungGalazyS23Ultra.jpg', 'Phones', 'samsung-galaxy-s23-ultra.php');

-- Game products
INSERT INTO products (name, price, image, category, link) VALUES
('Call of Duty: Black Ops 6', 69.99, 'images/CodBO6.jpg', 'Games', 'codbo6.php'),
('NBA 2K25 - PS5', 69.99, 'images/2k25PS5.jpg', 'Games', '2k25PS5.php'),
('Grand Theft Auto V - Xbox', 29.99, 'images/GTAVXbox.jpg', 'Games', 'GTAVXbox.php'),
('Hogwarts Legacy - Xbox', 59.99, 'images/hogwartslegacyXbox.jpg', 'Games', 'hogwarts-legacy-xbox.php'),
('Spider-Man 2 - PS5', 69.99, 'images/spiderman2PS5.png', 'Games', 'spiderman2-ps5.php'),
('Tekken 8 - PS5', 69.99, 'images/tekken8PS5.jpg', 'Games', 'tekken8-ps5.php'),
('Dogman - Switch', 39.99, 'images/dogmanSwitch.jpg', 'Games', 'dogman-switch.php'),
('Instant Sports - Switch', 29.99, 'images/instantsportsSwitch.jpg', 'Games', 'instant-sports-switch.php'),
('It Takes Two - Switch', 39.99, 'images/ittakes2Switch.jpg', 'Games', 'it-takes-two-switch.php'),
('Steam Gift Card', 50.00, 'images/gitcardSteam.jpeg', 'Games', 'steam-giftcard.php');

-- Insert a test admin user
INSERT INTO admins (username, email, password) VALUES
('admin', 'admin@techtrade.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password is 'password'

-- Insert a test employee
INSERT INTO employees (first_name, last_name, email, phone_number, password) VALUES
('John', 'Doe', 'john.doe@techtrade.com', '123-456-7890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password is 'password'

-- Insert a test user
INSERT INTO users (username, email, first_name, age, phone_number, password) VALUES
('testuser', 'test@example.com', 'Test', 25, '987-654-3210', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password is 'password'
