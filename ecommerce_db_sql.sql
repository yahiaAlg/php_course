-- E-commerce Database Creation Script for MariaDB
-- ================================================

-- Drop tables if they exist (in reverse order due to foreign keys)
DROP TABLE IF EXISTS category_product_bridge;
DROP TABLE IF EXISTS order_item;
DROP TABLE IF EXISTS order;
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS customer;
DROP TABLE IF EXISTS category;

-- Create Category Table
CREATE TABLE category (
    category_code INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Create Customer Table
CREATE TABLE customer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Create Product Table
CREATE TABLE product (
    serial_number VARCHAR(20) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    reference VARCHAR(50) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    discount BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Create Order Table
CREATE TABLE order (
    order_number VARCHAR(20) PRIMARY KEY,
    customer_id INT NOT NULL,
    address TEXT NOT NULL,
    ship_date DATE NOT NULL,
    status ENUM('Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Create Order Item Table
CREATE TABLE order_item (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(20) NOT NULL,
    product_id VARCHAR(20) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    add_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Create Category Product Bridge Table (Many-to-Many relationship)
CREATE TABLE category_product_bridge (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    product_id VARCHAR(20) NOT NULL,
    association_date DATE NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Add Unique Constraints
ALTER TABLE customer ADD CONSTRAINT uk_customer_email UNIQUE (email);
ALTER TABLE customer ADD CONSTRAINT uk_customer_phone UNIQUE (phone);
ALTER TABLE product ADD CONSTRAINT uk_product_reference UNIQUE (reference);
ALTER TABLE category_product_bridge ADD CONSTRAINT uk_category_product UNIQUE (category_id, product_id);

-- Add Foreign Key Constraints
ALTER TABLE order ADD CONSTRAINT fk_order_customer 
    FOREIGN KEY (customer_id) REFERENCES customer(id) 
    ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE order_item ADD CONSTRAINT fk_order_item_order 
    FOREIGN KEY (order_id) REFERENCES order(order_number) 
    ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE order_item ADD CONSTRAINT fk_order_item_product 
    FOREIGN KEY (product_id) REFERENCES product(serial_number) 
    ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE category_product_bridge ADD CONSTRAINT fk_bridge_category 
    FOREIGN KEY (category_id) REFERENCES category(category_code) 
    ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE category_product_bridge ADD CONSTRAINT fk_bridge_product 
    FOREIGN KEY (product_id) REFERENCES product(serial_number) 
    ON DELETE CASCADE ON UPDATE CASCADE;

-- Insert Data into Category Table
INSERT INTO category (name) VALUES
('Electronics'),
('Audio Equipment'),
('Computing'),
('Gaming Accessories'),
('Mobile Devices'),
('Storage Solutions'),
('Wearables'),
('Accessories');

-- Insert Data into Customer Table
INSERT INTO customer (name, phone, email) VALUES
('John Smith', '+1-555-0101', 'john.smith@email.com'),
('Sarah Johnson', '+1-555-0102', 'sarah.j@email.com'),
('Mike Davis', '+1-555-0103', 'mike.davis@email.com'),
('Emily Chen', '+1-555-0104', 'emily.chen@email.com'),
('Robert Wilson', '+1-555-0105', 'r.wilson@email.com'),
('Lisa Anderson', '+1-555-0106', 'lisa.anderson@email.com'),
('David Brown', '+1-555-0107', 'd.brown@email.com'),
('Maria Garcia', '+1-555-0108', 'maria.garcia@email.com'),
('James Taylor', '+1-555-0109', 'james.taylor@email.com'),
('Anna Martinez', '+1-555-0110', 'anna.martinez@email.com'),
('Thomas Lee', '+1-555-0111', 'thomas.lee@email.com'),
('Jennifer White', '+1-555-0112', 'jen.white@email.com');

-- Insert Data into Product Table
INSERT INTO product (serial_number, name, reference, price, discount) VALUES
('SN001', 'Wireless Headphones', 'WH-2024', 199.99, TRUE),
('SN002', 'Smartphone', 'SP-PRO', 799.99, FALSE),
('SN003', 'Laptop Computer', 'LP-15', 1299.99, TRUE),
('SN004', 'Gaming Mouse', 'GM-RGB', 89.99, FALSE),
('SN005', 'Bluetooth Speaker', 'BS-360', 149.99, TRUE),
('SN006', 'Tablet', 'TB-10', 399.99, FALSE),
('SN007', 'Smart Watch', 'SW-FIT', 299.99, TRUE),
('SN008', 'USB Cable', 'UC-C', 19.99, FALSE),
('SN009', 'Wireless Charger', 'WC-FAST', 59.99, TRUE),
('SN010', 'External Hard Drive', 'HD-1TB', 129.99, FALSE),
('SN011', 'Mechanical Keyboard', 'MK-RGB', 159.99, TRUE),
('SN012', 'Monitor', 'MON-27', 349.99, FALSE);

-- Insert Data into Order Table
INSERT INTO order (order_number, customer_id, address, ship_date, status) VALUES
('ORD-001', 1, '123 Oak St, NYC', '2024-08-15', 'Delivered'),
('ORD-002', 2, '456 Pine Ave, LA', '2024-08-16', 'Delivered'),
('ORD-003', 3, '789 Elm Rd, Chicago', '2024-08-17', 'Delivered'),
('ORD-004', 1, '123 Oak St, NYC', '2024-08-18', 'Delivered'),
('ORD-005', 4, '321 Maple Dr, Miami', '2024-08-18', 'Shipped'),
('ORD-006', 5, '654 Cedar Ln, Dallas', '2024-08-19', 'Shipped'),
('ORD-007', 6, '987 Birch St, Seattle', '2024-08-19', 'Shipped'),
('ORD-008', 2, '456 Pine Ave, LA', '2024-08-20', 'Processing'),
('ORD-009', 7, '147 Walnut Ave, Boston', '2024-08-20', 'Processing'),
('ORD-010', 8, '258 Cherry St, Denver', '2024-08-21', 'Processing'),
('ORD-011', 9, '369 Ash Blvd, Phoenix', '2024-08-21', 'Pending'),
('ORD-012', 10, '741 Spruce Way, Portland', '2024-08-22', 'Pending'),
('ORD-013', 1, '123 Oak St, NYC', '2024-08-25', 'Pending'),
('ORD-014', 2, '456 Pine Ave, LA', '2024-08-26', 'Pending'),
('ORD-015', 3, '789 Elm Rd, Chicago', '2024-08-23', 'Pending'),
('ORD-016', 4, '321 Maple Dr, Miami', '2024-08-24', 'Pending'),
('ORD-017', 5, '654 Cedar Ln, Dallas', '2024-08-27', 'Pending'),
('ORD-018', 6, '987 Birch St, Seattle', '2024-08-28', 'Pending'),
('ORD-019', 7, '147 Walnut Ave, Boston', '2024-08-29', 'Pending'),
('ORD-020', 8, '258 Cherry St, Denver', '2024-08-30', 'Pending'),
('ORD-021', 9, '369 Ash Blvd, Phoenix', '2024-08-31', 'Pending'),
('ORD-022', 10, '741 Spruce Way, Portland', '2024-09-01', 'Pending');

-- Insert Data into Order Item Table
INSERT INTO order_item (order_id, product_id, quantity, add_date) VALUES
('ORD-001', 'SN001', 2, '2024-08-15 10:30:00'),
('ORD-001', 'SN008', 1, '2024-08-15 10:32:00'),
('ORD-002', 'SN002', 1, '2024-08-16 14:15:00'),
('ORD-002', 'SN009', 1, '2024-08-16 14:18:00'),
('ORD-003', 'SN003', 1, '2024-08-17 09:45:00'),
('ORD-003', 'SN004', 1, '2024-08-17 09:47:00'),
('ORD-004', 'SN005', 2, '2024-08-18 16:20:00'),
('ORD-005', 'SN006', 1, '2024-08-18 11:10:00'),
('ORD-005', 'SN007', 1, '2024-08-18 11:15:00'),
('ORD-006', 'SN010', 1, '2024-08-19 13:30:00'),
('ORD-007', 'SN011', 1, '2024-08-19 15:45:00'),
('ORD-007', 'SN012', 1, '2024-08-19 15:50:00'),
('ORD-008', 'SN001', 1, '2024-08-20 12:00:00'),
('ORD-009', 'SN005', 3, '2024-08-20 17:15:00'),
('ORD-010', 'SN002', 1, '2024-08-21 10:45:00'),
('ORD-011', 'SN010', 1, '2024-08-21 14:20:00'),
('ORD-011', 'SN011', 2, '2024-08-21 14:45:00'),
('ORD-012', 'SN012', 1, '2024-08-22 16:30:00'),
('ORD-013', 'SN003', 1, '2024-08-25 10:15:00'),
('ORD-013', 'SN006', 2, '2024-08-25 11:45:00'),
('ORD-014', 'SN004', 2, '2024-08-26 14:30:00'),
('ORD-015', 'SN007', 1, '2024-08-23 13:20:00'),
('ORD-016', 'SN008', 2, '2024-08-24 11:15:00'),
('ORD-017', 'SN009', 1, '2024-08-27 13:30:00'),
('ORD-017', 'SN006', 1, '2024-08-27 15:20:00'),
('ORD-018', 'SN002', 2, '2024-08-28 16:20:00'),
('ORD-019', 'SN004', 1, '2024-08-29 16:45:00'),
('ORD-019', 'SN001', 3, '2024-08-29 15:30:00'),
('ORD-020', 'SN005', 1, '2024-08-30 12:30:00'),
('ORD-021', 'SN008', 1, '2024-08-31 09:45:00'),
('ORD-021', 'SN003', 2, '2024-08-31 11:00:00'),
('ORD-022', 'SN009', 3, '2024-09-01 14:45:00'),
('ORD-022', 'SN007', 2, '2024-09-01 16:10:00');

-- Insert Data into Category Product Bridge Table
INSERT INTO category_product_bridge (category_id, product_id, association_date) VALUES
-- Electronics Category
(1, 'SN002', '2024-01-15'),
(1, 'SN006', '2024-01-20'),
(1, 'SN008', '2024-02-01'),
(1, 'SN009', '2024-02-05'),
-- Audio Equipment Category
(2, 'SN001', '2024-01-10'),
(2, 'SN005', '2024-01-25'),
-- Computing Category
(3, 'SN003', '2024-01-12'),
(3, 'SN004', '2024-01-18'),
(3, 'SN011', '2024-02-10'),
(3, 'SN012', '2024-02-15'),
-- Gaming Accessories Category
(4, 'SN004', '2024-02-20'),
(4, 'SN011', '2024-02-25'),
-- Mobile Devices Category
(5, 'SN002', '2024-01-22'),
(5, 'SN006', '2024-02-08'),
(5, 'SN007', '2024-02-12'),
-- Storage Solutions Category
(6, 'SN010', '2024-01-30'),
-- Wearables Category
(7, 'SN001', '2024-03-01'),
(7, 'SN007', '2024-03-05'),
-- Accessories Category
(8, 'SN008', '2024-02-28'),
(8, 'SN009', '2024-03-02');

-- =======================================================
-- SEARCH AND ANALYTICAL QUERIES
-- =======================================================

-- 1. LIKE and Text Search Functions
-- =======================================================

-- Search customers by partial name
SELECT * FROM customer 
WHERE name LIKE '%Smith%' 
   OR name LIKE '%John%';

-- Case-insensitive product search
SELECT * FROM product 
WHERE UPPER(name) LIKE '%WIRELESS%'
   OR LOWER(reference) LIKE '%rgb%';

-- Advanced text functions
SELECT 
    name,
    LENGTH(name) as name_length,
    LEFT(name, 10) as first_10_chars,
    RIGHT(phone, 4) as last_4_digits,
    SUBSTRING(email, 1, LOCATE('@', email) - 1) as username,
    REPLACE(phone, '+1-', '') as clean_phone
FROM customer;

-- Text pattern matching with REGEXP
SELECT * FROM product 
WHERE name REGEXP '^(Wireless|Smart)';

-- 2. IN and Multiple Value Filtering
-- =======================================================

-- Orders with specific statuses
SELECT * FROM order 
WHERE status IN ('Pending', 'Processing');

-- Products in specific categories
SELECT p.name, c.name as category 
FROM product p
JOIN category_product_bridge cpb ON p.serial_number = cpb.product_id
JOIN category c ON cpb.category_id = c.category_code
WHERE c.name IN ('Electronics', 'Computing', 'Gaming Accessories');

-- Customers from specific cities
SELECT * FROM customer 
WHERE email IN (
    SELECT DISTINCT customer.email 
    FROM customer 
    JOIN order ON customer.id = order.customer_id 
    WHERE order.address LIKE '%NYC%' 
       OR order.address LIKE '%LA%'
);

-- 3. BETWEEN and Range Queries
-- =======================================================

-- Products in price range
SELECT name, price, 
       IF(discount, 'On Sale', 'Regular Price') as discount_status
FROM product 
WHERE price BETWEEN 100 AND 500;

-- Orders within date range
SELECT * FROM order 
WHERE ship_date BETWEEN '2024-08-20' AND '2024-08-30';

-- Quantities in range
SELECT oi.*, p.name, p.price
FROM order_item oi
JOIN product p ON oi.product_id = p.serial_number
WHERE quantity BETWEEN 2 AND 3;

-- 4. Date Functions
-- =======================================================

-- Date calculations and formatting
SELECT 
    order_number,
    ship_date,
    DAYOFWEEK(ship_date) as day_of_week,
    DAYNAME(ship_date) as day_name,
    MONTHNAME(ship_date) as month_name,
    YEAR(ship_date) as year,
    WEEK(ship_date) as week_number,
    DATE_FORMAT(ship_date, '%W, %M %d, %Y') as formatted_date,
    DATEDIFF(CURDATE(), ship_date) as days_since_shipped,
    DATE_ADD(ship_date, INTERVAL 30 DAY) as warranty_expires
FROM order;

-- Orders by month
SELECT 
    MONTHNAME(ship_date) as month,
    COUNT(*) as order_count
FROM order
GROUP BY MONTH(ship_date), MONTHNAME(ship_date)
ORDER BY MONTH(ship_date);

-- Time-based analysis
SELECT 
    HOUR(add_date) as hour_of_day,
    COUNT(*) as items_added
FROM order_item
GROUP BY HOUR(add_date)
ORDER BY hour_of_day;

-- 5. Numeric Functions and Calculations
-- =======================================================

-- Price calculations and statistics
SELECT 
    name,
    price,
    ROUND(price * 0.9, 2) as discounted_price,
    CEILING(price) as price_ceiling,
    FLOOR(price) as price_floor,
    MOD(CAST(price AS UNSIGNED), 100) as price_mod_100,
    POWER(price, 0.5) as price_sqrt,
    LOG(price) as price_log
FROM product;

-- Numeric aggregations
SELECT 
    AVG(price) as avg_price,
    MIN(price) as min_price,
    MAX(price) as max_price,
    STD(price) as price_std_dev,
    VARIANCE(price) as price_variance,
    SUM(price) as total_inventory_value
FROM product;

-- 6. Boolean and Enum Functions
-- =======================================================

-- Boolean operations
SELECT 
    name,
    price,
    discount,
    CASE 
        WHEN discount = TRUE AND price > 200 THEN 'High-Value Sale'
        WHEN discount = TRUE THEN 'On Sale'
        ELSE 'Regular Price'
    END as price_category
FROM product;

-- Enum analysis
SELECT 
    status,
    COUNT(*) as count,
    CONCAT(ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM order), 2), '%') as percentage
FROM order
GROUP BY status;

-- 7. Advanced Aggregations and Analytics
-- =======================================================

-- Customer purchase analysis
SELECT 
    c.name,
    c.email,
    COUNT(DISTINCT o.order_number) as total_orders,
    COUNT(oi.item_id) as total_items,
    SUM(oi.quantity) as total_quantity,
    SUM(oi.quantity * p.price) as total_spent,
    AVG(oi.quantity * p.price) as avg_order_value,
    MAX(o.ship_date) as last_order_date
FROM customer c
LEFT JOIN order o ON c.id = o.customer_id
LEFT JOIN order_item oi ON o.order_number = oi.order_id
LEFT JOIN product p ON oi.product_id = p.serial_number
GROUP BY c.id, c.name, c.email
ORDER BY total_spent DESC;

-- Product performance analysis
SELECT 
    p.name,
    p.price,
    COUNT(oi.item_id) as times_ordered,
    SUM(oi.quantity) as total_quantity_sold,
    SUM(oi.quantity * p.price) as total_revenue,
    AVG(oi.quantity) as avg_quantity_per_order,
    MIN(oi.add_date) as first_sale_date,
    MAX(oi.add_date) as last_sale_date
FROM product p
LEFT JOIN order_item oi ON p.serial_number = oi.product_id
GROUP BY p.serial_number, p.name, p.price
ORDER BY total_revenue DESC;

-- Monthly sales trends with rolling averages
SELECT 
    DATE_FORMAT(o.ship_date, '%Y-%m') as month,
    COUNT(DISTINCT o.order_number) as orders,
    SUM(oi.quantity) as items_sold,
    SUM(oi.quantity * p.price) as revenue,
    AVG(SUM(oi.quantity * p.price)) OVER (ORDER BY DATE_FORMAT(o.ship_date, '%Y-%m') ROWS 2 PRECEDING) as rolling_avg_revenue
FROM order o
JOIN order_item oi ON o.order_number = oi.order_id
JOIN product p ON oi.product_id = p.serial_number
GROUP BY DATE_FORMAT(o.ship_date, '%Y-%m')
ORDER BY month;

-- Category performance with rankings
SELECT 
    c.name as category,
    COUNT(DISTINCT cpb.product_id) as product_count,
    COUNT(oi.item_id) as times_ordered,
    SUM(oi.quantity * p.price) as category_revenue,
    RANK() OVER (ORDER BY SUM(oi.quantity * p.price) DESC) as revenue_rank,
    DENSE_RANK() OVER (ORDER BY COUNT(oi.item_id) DESC) as popularity_rank
FROM category c
JOIN category_product_bridge cpb ON c.category_code = cpb.category_id
JOIN product p ON cpb.product_id = p.serial_number
LEFT JOIN order_item oi ON p.serial_number = oi.product_id
GROUP BY c.category_code, c.name
ORDER BY category_revenue DESC;

-- Customer segmentation using CASE and percentiles
WITH customer_stats AS (
    SELECT 
        c.id,
        c.name,
        SUM(oi.quantity * p.price) as total_spent,
        COUNT(DISTINCT o.order_number) as order_count
    FROM customer c
    LEFT JOIN order o ON c.id = o.customer_id
    LEFT JOIN order_item oi ON o.order_number = oi.order_id
    LEFT JOIN product p ON oi.product_id = p.serial_number
    GROUP BY c.id, c.name
)
SELECT 
    name,
    total_spent,
    order_count,
    CASE 
        WHEN total_spent >= 1000 THEN 'VIP Customer'
        WHEN total_spent >= 500 THEN 'Premium Customer'
        WHEN total_spent >= 100 THEN 'Regular Customer'
        ELSE 'New Customer'
    END as customer_segment,
    PERCENT_RANK() OVER (ORDER BY total_spent) as spending_percentile
FROM customer_stats
ORDER BY total_spent DESC;

-- Complex search with multiple criteria
SELECT 
    p.name as product_name,
    p.price,
    c.name as category,
    COUNT(oi.item_id) as popularity_score,
    IF(p.discount, 'YES', 'NO') as on_discount
FROM product p
JOIN category_product_bridge cpb ON p.serial_number = cpb.product_id
JOIN category c ON cpb.category_id = c.category_code
LEFT JOIN order_item oi ON p.serial_number = oi.product_id
WHERE (p.name LIKE '%Wireless%' OR p.name LIKE '%Smart%')
   AND p.price BETWEEN 50 AND 800
   AND c.name IN ('Electronics', 'Mobile Devices', 'Audio Equipment')
GROUP BY p.serial_number, p.name, p.price, c.name, p.discount
HAVING popularity_score > 0
ORDER BY popularity_score DESC, p.price ASC;