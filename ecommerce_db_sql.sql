-- E-commerce Database Creation Script for MariaDB
-- ================================================
-- Optimized for MariaDB 10.x with enhanced conventions
SET
    sql_mode = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';

SET
    default_storage_engine = InnoDB;

SET
    NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Drop tables if they exist (in reverse order due to foreign keys)
DROP TABLE IF EXISTS category_product_bridge;

DROP TABLE IF EXISTS order_item;

DROP TABLE IF EXISTS `order`;

DROP TABLE IF EXISTS product;

DROP TABLE IF EXISTS customer;

DROP TABLE IF EXISTS category;

-- Create Category Table
CREATE TABLE
    category (
        category_code INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_category_name (name)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Create Customer Table
CREATE TABLE
    customer (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        email VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uk_customer_email (email),
        UNIQUE KEY uk_customer_phone (phone),
        INDEX idx_customer_name (name)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Create Product Table
CREATE TABLE
    product (
        serial_number VARCHAR(20) PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        reference VARCHAR(50) NOT NULL,
        price DECIMAL(10, 2) NOT NULL CHECK (price >= 0),
        discount BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uk_product_reference (reference),
        INDEX idx_product_name (name),
        INDEX idx_product_price (price),
        INDEX idx_product_discount (discount)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Create Order Table (using backticks since 'order' is a reserved word)
CREATE TABLE
    `order` (
        order_number VARCHAR(20) PRIMARY KEY,
        customer_id INT UNSIGNED NOT NULL,
        address TEXT NOT NULL,
        ship_date DATE NOT NULL,
        status ENUM (
            'Pending',
            'Processing',
            'Shipped',
            'Delivered',
            'Cancelled'
        ) DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_order_customer (customer_id),
        INDEX idx_order_status (status),
        INDEX idx_order_ship_date (ship_date),
        INDEX idx_order_created_at (created_at)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Create Order Item Table
CREATE TABLE
    order_item (
        item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        order_id VARCHAR(20) NOT NULL,
        product_id VARCHAR(20) NOT NULL,
        quantity INT UNSIGNED NOT NULL DEFAULT 1 CHECK (quantity > 0),
        add_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_order_item_order (order_id),
        INDEX idx_order_item_product (product_id),
        INDEX idx_order_item_add_date (add_date)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Create Category Product Bridge Table (Many-to-Many relationship)
CREATE TABLE
    category_product_bridge (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        category_id INT UNSIGNED NOT NULL,
        product_id VARCHAR(20) NOT NULL,
        association_date DATE NOT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uk_category_product (category_id, product_id),
        INDEX idx_bridge_category (category_id),
        INDEX idx_bridge_product (product_id),
        INDEX idx_bridge_date (association_date)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Add Foreign Key Constraints
ALTER TABLE `order` ADD CONSTRAINT fk_order_customer FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE order_item ADD CONSTRAINT fk_order_item_order FOREIGN KEY (order_id) REFERENCES `order` (order_number) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE order_item ADD CONSTRAINT fk_order_item_product FOREIGN KEY (product_id) REFERENCES product (serial_number) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE category_product_bridge ADD CONSTRAINT fk_bridge_category FOREIGN KEY (category_id) REFERENCES category (category_code) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE category_product_bridge ADD CONSTRAINT fk_bridge_product FOREIGN KEY (product_id) REFERENCES product (serial_number) ON DELETE CASCADE ON UPDATE CASCADE;

-- Insert Data into Category Table
INSERT INTO
    category (name)
VALUES
    ('Electronics'),
    ('Audio Equipment'),
    ('Computing'),
    ('Gaming Accessories'),
    ('Mobile Devices'),
    ('Storage Solutions'),
    ('Wearables'),
    ('Accessories');

-- Insert Data into Customer Table
INSERT INTO
    customer (name, phone, email)
VALUES
    (
        'John Smith',
        '+1-555-0101',
        'john.smith@email.com'
    ),
    (
        'Sarah Johnson',
        '+1-555-0102',
        'sarah.j@email.com'
    ),
    (
        'Mike Davis',
        '+1-555-0103',
        'mike.davis@email.com'
    ),
    (
        'Emily Chen',
        '+1-555-0104',
        'emily.chen@email.com'
    ),
    (
        'Robert Wilson',
        '+1-555-0105',
        'r.wilson@email.com'
    ),
    (
        'Lisa Anderson',
        '+1-555-0106',
        'lisa.anderson@email.com'
    ),
    ('David Brown', '+1-555-0107', 'd.brown@email.com'),
    (
        'Maria Garcia',
        '+1-555-0108',
        'maria.garcia@email.com'
    ),
    (
        'James Taylor',
        '+1-555-0109',
        'james.taylor@email.com'
    ),
    (
        'Anna Martinez',
        '+1-555-0110',
        'anna.martinez@email.com'
    ),
    (
        'Thomas Lee',
        '+1-555-0111',
        'thomas.lee@email.com'
    ),
    (
        'Jennifer White',
        '+1-555-0112',
        'jen.white@email.com'
    );

-- Insert Data into Product Table
INSERT INTO
    product (serial_number, name, reference, price, discount)
VALUES
    (
        'SN001',
        'Wireless Headphones',
        'WH-2024',
        199.99,
        TRUE
    ),
    ('SN002', 'Smartphone', 'SP-PRO', 799.99, FALSE),
    (
        'SN003',
        'Laptop Computer',
        'LP-15',
        1299.99,
        TRUE
    ),
    ('SN004', 'Gaming Mouse', 'GM-RGB', 89.99, FALSE),
    (
        'SN005',
        'Bluetooth Speaker',
        'BS-360',
        149.99,
        TRUE
    ),
    ('SN006', 'Tablet', 'TB-10', 399.99, FALSE),
    ('SN007', 'Smart Watch', 'SW-FIT', 299.99, TRUE),
    ('SN008', 'USB Cable', 'UC-C', 19.99, FALSE),
    (
        'SN009',
        'Wireless Charger',
        'WC-FAST',
        59.99,
        TRUE
    ),
    (
        'SN010',
        'External Hard Drive',
        'HD-1TB',
        129.99,
        FALSE
    ),
    (
        'SN011',
        'Mechanical Keyboard',
        'MK-RGB',
        159.99,
        TRUE
    ),
    ('SN012', 'Monitor', 'MON-27', 349.99, FALSE);

-- Insert Data into Order Table
INSERT INTO
    `order` (
        order_number,
        customer_id,
        address,
        ship_date,
        status
    )
VALUES
    (
        'ORD-001',
        1,
        '123 Oak St, NYC',
        '2024-08-15',
        'Delivered'
    ),
    (
        'ORD-002',
        2,
        '456 Pine Ave, LA',
        '2024-08-16',
        'Delivered'
    ),
    (
        'ORD-003',
        3,
        '789 Elm Rd, Chicago',
        '2024-08-17',
        'Delivered'
    ),
    (
        'ORD-004',
        1,
        '123 Oak St, NYC',
        '2024-08-18',
        'Delivered'
    ),
    (
        'ORD-005',
        4,
        '321 Maple Dr, Miami',
        '2024-08-18',
        'Shipped'
    ),
    (
        'ORD-006',
        5,
        '654 Cedar Ln, Dallas',
        '2024-08-19',
        'Shipped'
    ),
    (
        'ORD-007',
        6,
        '987 Birch St, Seattle',
        '2024-08-19',
        'Shipped'
    ),
    (
        'ORD-008',
        2,
        '456 Pine Ave, LA',
        '2024-08-20',
        'Processing'
    ),
    (
        'ORD-009',
        7,
        '147 Walnut Ave, Boston',
        '2024-08-20',
        'Processing'
    ),
    (
        'ORD-010',
        8,
        '258 Cherry St, Denver',
        '2024-08-21',
        'Processing'
    ),
    (
        'ORD-011',
        9,
        '369 Ash Blvd, Phoenix',
        '2024-08-21',
        'Pending'
    ),
    (
        'ORD-012',
        10,
        '741 Spruce Way, Portland',
        '2024-08-22',
        'Pending'
    ),
    (
        'ORD-013',
        1,
        '123 Oak St, NYC',
        '2024-08-25',
        'Pending'
    ),
    (
        'ORD-014',
        2,
        '456 Pine Ave, LA',
        '2024-08-26',
        'Pending'
    ),
    (
        'ORD-015',
        3,
        '789 Elm Rd, Chicago',
        '2024-08-23',
        'Pending'
    ),
    (
        'ORD-016',
        4,
        '321 Maple Dr, Miami',
        '2024-08-24',
        'Pending'
    ),
    (
        'ORD-017',
        5,
        '654 Cedar Ln, Dallas',
        '2024-08-27',
        'Pending'
    ),
    (
        'ORD-018',
        6,
        '987 Birch St, Seattle',
        '2024-08-28',
        'Pending'
    ),
    (
        'ORD-019',
        7,
        '147 Walnut Ave, Boston',
        '2024-08-29',
        'Pending'
    ),
    (
        'ORD-020',
        8,
        '258 Cherry St, Denver',
        '2024-08-30',
        'Pending'
    ),
    (
        'ORD-021',
        9,
        '369 Ash Blvd, Phoenix',
        '2024-08-31',
        'Pending'
    ),
    (
        'ORD-022',
        10,
        '741 Spruce Way, Portland',
        '2024-09-01',
        'Pending'
    );

-- Insert Data into Order Item Table
INSERT INTO
    order_item (order_id, product_id, quantity, add_date)
VALUES
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
INSERT INTO
    category_product_bridge (category_id, product_id, association_date)
VALUES
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
-- SEARCH AND ANALYTICAL QUERIES (MariaDB Optimized)
-- =======================================================
-- 1. Text Search with MariaDB Features
-- =======================================================
-- Case-insensitive search with COLLATE
SELECT
    *
FROM
    customer
WHERE
    name COLLATE utf8mb4_general_ci LIKE '%smith%'
    OR name COLLATE utf8mb4_general_ci LIKE '%john%';

-- Full-text search preparation (if needed)
-- ALTER TABLE product ADD FULLTEXT(name, reference);
-- SELECT * FROM product WHERE MATCH(name, reference) AGAINST('wireless smart' IN BOOLEAN MODE);
-- MariaDB string functions
SELECT
    name,
    CHAR_LENGTH(name) as name_length,
    LEFT (name, 10) as first_10_chars,
    RIGHT (phone, 4) as last_4_digits,
    SUBSTRING_INDEX (email, '@', 1) as username,
    REPLACE (phone, '+1-', '') as clean_phone,
    SOUNDEX (name) as name_soundex
FROM
    customer;

-- 2. Optimized Range and IN Queries
-- =======================================================
-- Using prepared statement syntax for better performance
SELECT
    *
FROM
    `order`
WHERE
    status IN ('Pending', 'Processing')
ORDER BY
    ship_date DESC;

-- Subquery with EXISTS for better performance
SELECT
    p.name,
    c.name as category
FROM
    product p
WHERE
    EXISTS (
        SELECT
            1
        FROM
            category_product_bridge cpb
            JOIN category c ON cpb.category_id = c.category_code
        WHERE
            cpb.product_id = p.serial_number
            AND c.name IN ('Electronics', 'Computing', 'Gaming Accessories')
    );

-- 3. Date Functions - MariaDB Specific
-- =======================================================
-- Enhanced date analysis with MariaDB functions
SELECT
    order_number,
    ship_date,
    DAYOFWEEK (ship_date) as day_of_week,
    DAYNAME (ship_date) as day_name,
    MONTHNAME (ship_date) as month_name,
    QUARTER (ship_date) as quarter,
    YEARWEEK (ship_date) as year_week,
    DATE_FORMAT (ship_date, '%W, %M %d, %Y') as formatted_date,
    TIMESTAMPDIFF (DAY, ship_date, CURDATE ()) as days_since_shipped,
    DATE_ADD (ship_date, INTERVAL 30 DAY) as warranty_expires,
    LAST_DAY (ship_date) as month_end
FROM
    `order`;

-- Time series analysis with window functions
SELECT
    DATE (ship_date) as ship_date,
    COUNT(*) as daily_orders,
    SUM(COUNT(*)) OVER (
        ORDER BY
            DATE (ship_date)
    ) as cumulative_orders,
    AVG(COUNT(*)) OVER (
        ORDER BY
            DATE (ship_date) ROWS 6 PRECEDING
    ) as weekly_avg
FROM
    `order`
GROUP BY
    DATE (ship_date)
ORDER BY
    ship_date;

-- 4. Advanced Analytics with Window Functions
-- =======================================================
-- Customer lifetime value with ranking
WITH
    customer_metrics AS (
        SELECT
            c.id,
            c.name,
            COUNT(DISTINCT o.order_number) as total_orders,
            SUM(oi.quantity * p.price) as total_spent,
            AVG(oi.quantity * p.price) as avg_order_value,
            MAX(o.created_at) as last_order_date,
            TIMESTAMPDIFF (DAY, MAX(o.created_at), CURDATE ()) as days_since_last_order
        FROM
            customer c
            LEFT JOIN `order` o ON c.id = o.customer_id
            LEFT JOIN order_item oi ON o.order_number = oi.order_id
            LEFT JOIN product p ON oi.product_id = p.serial_number
        GROUP BY
            c.id,
            c.name
    )
SELECT
    *,
    NTILE (4) OVER (
        ORDER BY
            total_spent
    ) as spending_quartile,
    CASE
        WHEN days_since_last_order IS NULL THEN 'Never Ordered'
        WHEN days_since_last_order <= 30 THEN 'Active'
        WHEN days_since_last_order <= 90 THEN 'At Risk'
        ELSE 'Inactive'
    END as customer_status
FROM
    customer_metrics
ORDER BY
    total_spent DESC;

-- Product performance with trend analysis
SELECT
    p.name,
    p.price,
    COUNT(oi.item_id) as order_frequency,
    SUM(oi.quantity) as total_sold,
    SUM(oi.quantity * p.price) as revenue,
    LAG (SUM(oi.quantity), 1) OVER (
        PARTITION BY
            MONTH (oi.add_date)
        ORDER BY
            p.serial_number
    ) as prev_month_sold,
    ROW_NUMBER() OVER (
        ORDER BY
            SUM(oi.quantity * p.price) DESC
    ) as revenue_rank
FROM
    product p
    LEFT JOIN order_item oi ON p.serial_number = oi.product_id
GROUP BY
    p.serial_number,
    p.name,
    p.price
HAVING
    revenue > 0
ORDER BY
    revenue DESC;

-- 5. MariaDB Specific Optimizations
-- =======================================================
-- Using MariaDB's LIMIT with OFFSET for pagination
SELECT
    c.name,
    COUNT(o.order_number) as order_count
FROM
    customer c
    LEFT JOIN `order` o ON c.id = o.customer_id
GROUP BY
    c.id,
    c.name
ORDER BY
    order_count DESC
LIMIT
    5
OFFSET
    0;

-- JSON functions (MariaDB 10.2+)
SELECT
    name,
    JSON_OBJECT (
        'price',
        price,
        'discount',
        discount,
        'category_count',
        (
            SELECT
                COUNT(*)
            FROM
                category_product_bridge cpb
            WHERE
                cpb.product_id = p.serial_number
        )
    ) as product_details
FROM
    product p;

-- Common Table Expression for complex analysis
WITH RECURSIVE
    date_series AS (
        SELECT
            DATE ('2024-08-01') as date_val
        UNION ALL
        SELECT
            DATE_ADD (date_val, INTERVAL 1 DAY)
        FROM
            date_series
        WHERE
            date_val < DATE ('2024-09-01')
    ),
    daily_sales AS (
        SELECT
            DATE (o.ship_date) as sale_date,
            COUNT(DISTINCT o.order_number) as orders,
            SUM(oi.quantity * p.price) as revenue
        FROM
            `order` o
            JOIN order_item oi ON o.order_number = oi.order_id
            JOIN product p ON oi.product_id = p.serial_number
        GROUP BY
            DATE (o.ship_date)
    )
SELECT
    ds.date_val,
    COALESCE(s.orders, 0) as orders,
    COALESCE(s.revenue, 0) as revenue
FROM
    date_series ds
    LEFT JOIN daily_sales s ON ds.date_val = s.sale_date
ORDER BY
    ds.date_val;

-- Performance monitoring queries
SHOW TABLE STATUS LIKE 'product';

EXPLAIN
SELECT
    *
FROM
    product
WHERE
    price BETWEEN 100 AND 500;

-- Index usage analysis
SELECT
    TABLE_NAME,
    INDEX_NAME,
    CARDINALITY,
    NULLABLE
FROM
    INFORMATION_SCHEMA.STATISTICS
WHERE
    TABLE_SCHEMA = DATABASE ()
ORDER BY
    TABLE_NAME,
    INDEX_NAME;