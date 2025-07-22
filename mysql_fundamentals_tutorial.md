# MySQL Fundamentals Tutorial for Complete Beginners

## Introduction: What is MySQL?

MySQL is a relational database management system (RDBMS) that helps you store, organize, and retrieve data efficiently. Think of it as a sophisticated filing cabinet where you can create multiple folders (databases), and within each folder, you can have organized tables to store related information.

MariaDB, which comes with XAMPP, is a fork of MySQL that maintains full compatibility while offering some additional features. Everything you learn here applies to both systems.

## Getting Started with MySQL in XAMPP

Before we dive into database operations, let's understand how to access MySQL through XAMPP. When you start XAMPP, you're actually starting both Apache (web server) and MySQL (database server). You can access MySQL through phpMyAdmin (web interface) or command line.

### Connecting to MySQL

When working with MySQL, you need to establish a connection first. This is like opening the door to your database system.

```sql
-- Basic connection parameters (typically handled by your application)
-- Host: localhost (since we're using XAMPP locally)
-- Port: 3306 (MySQL's default port)
-- Username: root (default in XAMPP)
-- Password: (empty by default in XAMPP)
```

## Creating Your First Database

A database is like a container that holds related tables. Before you can store any data, you need to create this container.

```sql
-- Create a new database
CREATE DATABASE school_management;

-- Switch to use this database
USE school_management;

-- Check which database you're currently using
SELECT DATABASE();
```

The `CREATE DATABASE` command tells MySQL to create a new database. The `USE` command is crucial because it tells MySQL which database you want to work with for subsequent operations.

## Understanding Tables and Their Structure

Tables are the heart of any database. Think of a table like a spreadsheet with rows and columns. Each column represents a specific type of information (like name, age, email), and each row represents a complete record.

### Creating Your First Table

```sql
-- Create a students table
CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE,
    age INT,
    enrollment_date DATE DEFAULT CURRENT_DATE
);
```

Let's break down what each part means:

- `student_id INT AUTO_INCREMENT PRIMARY KEY`: This creates a unique identifier that automatically increases for each new student
- `VARCHAR(50)`: Variable character field that can hold up to 50 characters
- `NOT NULL`: This field cannot be empty
- `UNIQUE`: No two students can have the same email
- `DEFAULT CURRENT_DATE`: If no date is provided, it uses today's date

## Primary Keys: The Unique Identifier

A primary key is like a unique fingerprint for each record in your table. It ensures that every row can be uniquely identified and prevents duplicate entries.

```sql
-- Example showing different ways to define primary keys
CREATE TABLE courses (
    course_id INT AUTO_INCREMENT,
    course_code VARCHAR(10) NOT NULL,
    course_name VARCHAR(100) NOT NULL,
    credits INT DEFAULT 3,
    PRIMARY KEY (course_id)
);

-- Alternative: Composite primary key (using multiple columns)
CREATE TABLE class_schedule (
    course_id INT,
    day_of_week VARCHAR(10),
    time_slot VARCHAR(20),
    room_number VARCHAR(10),
    PRIMARY KEY (course_id, day_of_week, time_slot)
);
```

The composite primary key in the second example means that the combination of course_id, day_of_week, and time_slot must be unique. This prevents scheduling conflicts.

## Inserting Data: Adding Records

Now that we have tables, let's add some data. Think of this as filling out forms and filing them in your organized system.

```sql
-- Insert a single student
INSERT INTO students (first_name, last_name, email, age)
VALUES ('John', 'Doe', 'john.doe@email.com', 20);

-- Insert multiple students at once
INSERT INTO students (first_name, last_name, email, age) VALUES
('Jane', 'Smith', 'jane.smith@email.com', 19),
('Mike', 'Johnson', 'mike.johnson@email.com', 21),
('Sarah', 'Williams', 'sarah.williams@email.com', 18);

-- Insert with some default values
INSERT INTO students (first_name, last_name, email)
VALUES ('Tom', 'Brown', 'tom.brown@email.com');
-- Note: age will be NULL, enrollment_date will be today's date
```

## Retrieving Data: SELECT Statements

The SELECT statement is how you ask the database to show you information. It's like asking "show me all students" or "show me students older than 20."

```sql
-- Select all students
SELECT * FROM students;

-- Select specific columns
SELECT first_name, last_name, email FROM students;

-- Select with conditions
SELECT * FROM students WHERE age > 19;

-- Select with multiple conditions
SELECT * FROM students 
WHERE age > 18 AND enrollment_date > '2024-01-01';

-- Select with pattern matching
SELECT * FROM students 
WHERE first_name LIKE 'J%';  -- Names starting with 'J'
```

## Filtering and Sorting Data

### WHERE Clause: Filtering Records

The WHERE clause is like setting up filters to find exactly what you're looking for.

```sql
-- Various WHERE examples
SELECT * FROM students WHERE age BETWEEN 18 AND 21;
SELECT * FROM students WHERE email IS NOT NULL;
SELECT * FROM students WHERE first_name IN ('John', 'Jane', 'Mike');
SELECT * FROM students WHERE last_name LIKE '%son';  -- Ends with 'son'
```

### ORDER BY: Sorting Results

```sql
-- Sort by age (ascending by default)
SELECT * FROM students ORDER BY age;

-- Sort by age descending
SELECT * FROM students ORDER BY age DESC;

-- Sort by multiple columns
SELECT * FROM students ORDER BY last_name, first_name;
```

### LIMIT: Restricting Results

```sql
-- Get only the first 5 students
SELECT * FROM students LIMIT 5;

-- Get students 6-10 (skip 5, take 5)
SELECT * FROM students LIMIT 5, 5;

-- Get the oldest 3 students
SELECT * FROM students ORDER BY age DESC LIMIT 3;
```

## Updating and Deleting Data

### UPDATE: Modifying Existing Records

```sql
-- Update a specific student's email
UPDATE students 
SET email = 'john.newemail@email.com' 
WHERE student_id = 1;

-- Update multiple fields
UPDATE students 
SET age = 21, email = 'jane.updated@email.com' 
WHERE first_name = 'Jane' AND last_name = 'Smith';

-- Update with calculations
UPDATE students 
SET age = age + 1 
WHERE enrollment_date < '2024-01-01';
```

### DELETE: Removing Records

```sql
-- Delete a specific student
DELETE FROM students WHERE student_id = 5;

-- Delete students meeting certain criteria
DELETE FROM students WHERE age < 18;

-- Delete all records (be very careful!)
DELETE FROM students;  -- This removes all data but keeps the table structure
```

## Understanding Foreign Keys

Foreign keys are like references that connect tables together. They ensure data integrity by making sure that references point to valid records in another table.

```sql
-- Create an enrollments table that references students and courses
CREATE TABLE enrollments (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    enrollment_date DATE DEFAULT CURRENT_DATE,
    grade VARCHAR(2),
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id)
);
```

This means you cannot enroll a student who doesn't exist in the students table, and you cannot enroll them in a course that doesn't exist in the courses table.

## Database Relationships

### One-to-One Relationships

In a one-to-one relationship, each record in one table corresponds to exactly one record in another table. This is like having a student and their passport - each student has exactly one passport.

```sql
-- Create a student_profiles table for additional information
CREATE TABLE student_profiles (
    profile_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT UNIQUE,  -- UNIQUE ensures one-to-one relationship
    phone_number VARCHAR(15),
    address TEXT,
    emergency_contact VARCHAR(100),
    FOREIGN KEY (student_id) REFERENCES students(student_id)
);
```

### One-to-Many Relationships

This is the most common relationship type. One record in the first table can relate to many records in the second table, but each record in the second table relates to only one record in the first table.

```sql
-- A department can have many students, but each student belongs to one department
CREATE TABLE departments (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL,
    head_of_department VARCHAR(100)
);

-- Add department_id to students table
ALTER TABLE students 
ADD COLUMN department_id INT,
ADD FOREIGN KEY (department_id) REFERENCES departments(department_id);
```

### Many-to-Many Relationships

In many-to-many relationships, records in both tables can relate to multiple records in the other table. Students can enroll in many courses, and courses can have many students.

```sql
-- We already created this as our enrollments table
-- This is called a "junction table" or "bridge table"
CREATE TABLE enrollments (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    enrollment_date DATE DEFAULT CURRENT_DATE,
    grade VARCHAR(2),
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    UNIQUE KEY unique_enrollment (student_id, course_id)  -- Prevent duplicate enrollments
);
```

## Working with Prepared Statements

Prepared statements are a secure way to execute SQL queries, especially when dealing with user input. They prevent SQL injection attacks and can improve performance.

```sql
-- Example of how prepared statements work conceptually
-- (Note: Actual syntax depends on your programming language)

-- Instead of this vulnerable approach:
-- SELECT * FROM students WHERE student_id = [user_input]

-- Use this secure approach:
-- PREPARE stmt FROM 'SELECT * FROM students WHERE student_id = ?';
-- SET @student_id = 1;
-- EXECUTE stmt USING @student_id;
-- DEALLOCATE PREPARE stmt;
```

## Built-in Functions

MySQL provides many built-in functions to manipulate and analyze data.

### String Functions

```sql
-- String manipulation examples
SELECT 
    CONCAT(first_name, ' ', last_name) AS full_name,
    UPPER(email) AS email_upper,
    LENGTH(first_name) AS name_length,
    SUBSTRING(email, 1, LOCATE('@', email) - 1) AS username
FROM students;

-- More string functions
SELECT 
    TRIM(first_name) AS trimmed_name,
    REPLACE(email, '@email.com', '@newdomain.com') AS new_email
FROM students;
```

### Date and Time Functions

```sql
-- Date manipulation examples
SELECT 
    enrollment_date,
    YEAR(enrollment_date) AS enrollment_year,
    MONTH(enrollment_date) AS enrollment_month,
    DATEDIFF(CURRENT_DATE, enrollment_date) AS days_enrolled,
    DATE_ADD(enrollment_date, INTERVAL 4 YEAR) AS graduation_date
FROM students;

-- Current date/time functions
SELECT 
    NOW() AS current_datetime,
    CURRENT_DATE AS today,
    CURRENT_TIME AS current_time;
```

### Aggregate Functions

```sql
-- Counting and summarizing data
SELECT 
    COUNT(*) AS total_students,
    COUNT(age) AS students_with_age,  -- NULL values are not counted
    AVG(age) AS average_age,
    MIN(age) AS youngest_age,
    MAX(age) AS oldest_age
FROM students;

-- Grouping data
SELECT 
    department_id,
    COUNT(*) AS students_per_department,
    AVG(age) AS average_age_per_department
FROM students 
GROUP BY department_id
HAVING COUNT(*) > 2;  -- HAVING is like WHERE but for grouped results
```

## User Management and Privileges

Database security is crucial. You need to control who can access what data and what operations they can perform.

### Creating Users

```sql
-- Create a new user
CREATE USER 'teacher'@'localhost' IDENTIFIED BY 'secure_password';

-- Create a user that can connect from any host
CREATE USER 'student_app'@'%' IDENTIFIED BY 'app_password';
```

### Granting Privileges

```sql
-- Grant specific privileges
GRANT SELECT, INSERT, UPDATE ON school_management.students TO 'teacher'@'localhost';

-- Grant all privileges on a specific database
GRANT ALL PRIVILEGES ON school_management.* TO 'admin'@'localhost';

-- Grant only read access
GRANT SELECT ON school_management.* TO 'readonly_user'@'localhost';

-- Grant specific privileges on specific columns
GRANT SELECT (first_name, last_name, email) ON school_management.students TO 'limited_user'@'localhost';
```

### Common Privilege Types

Understanding different privilege levels helps you implement proper security:

- **SELECT**: Read data from tables
- **INSERT**: Add new records
- **UPDATE**: Modify existing records
- **DELETE**: Remove records
- **CREATE**: Create new databases and tables
- **DROP**: Delete databases and tables
- **ALTER**: Modify table structure
- **INDEX**: Create and drop indexes
- **ALL PRIVILEGES**: Grant all available privileges

### Revoking Privileges

```sql
-- Remove specific privileges
REVOKE INSERT, UPDATE ON school_management.students FROM 'teacher'@'localhost';

-- Remove all privileges
REVOKE ALL PRIVILEGES ON school_management.* FROM 'limited_user'@'localhost';
```

### Checking Privileges

```sql
-- See privileges for a specific user
SHOW GRANTS FOR 'teacher'@'localhost';

-- See privileges for current user
SHOW GRANTS FOR CURRENT_USER();

-- List all users
SELECT User, Host FROM mysql.user;
```

## Practical Example: Building a Complete System

Let's put it all together with a practical example that demonstrates relationships and proper database design.

```sql
-- Step 1: Create the database and use it
CREATE DATABASE school_management;
USE school_management;

-- Step 2: Create departments table (parent table)
CREATE TABLE departments (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL,
    head_of_department VARCHAR(100)
);

-- Step 3: Create students table with foreign key reference
CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE,
    age INT,
    enrollment_date DATE DEFAULT CURRENT_DATE,
    department_id INT,
    FOREIGN KEY (department_id) REFERENCES departments(department_id)
);

-- Step 4: Create courses table
CREATE TABLE courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(10) NOT NULL,
    course_name VARCHAR(100) NOT NULL,
    credits INT DEFAULT 3,
    department_id INT,
    FOREIGN KEY (department_id) REFERENCES departments(department_id)
);

-- Step 5: Create many-to-many relationship table
CREATE TABLE enrollments (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    enrollment_date DATE DEFAULT CURRENT_DATE,
    grade VARCHAR(2),
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    UNIQUE KEY unique_enrollment (student_id, course_id)
);

-- Step 6: Insert sample data
INSERT INTO departments (department_name, head_of_department) VALUES
('Computer Science', 'Dr. Smith'),
('Mathematics', 'Dr. Johnson'),
('Physics', 'Dr. Williams');

INSERT INTO students (first_name, last_name, email, age, department_id) VALUES
('John', 'Doe', 'john.doe@email.com', 20, 1),
('Jane', 'Smith', 'jane.smith@email.com', 19, 1),
('Mike', 'Johnson', 'mike.johnson@email.com', 21, 2);

INSERT INTO courses (course_code, course_name, credits, department_id) VALUES
('CS101', 'Introduction to Programming', 4, 1),
('MATH201', 'Calculus I', 3, 2),
('CS201', 'Data Structures', 4, 1);

INSERT INTO enrollments (student_id, course_id, grade) VALUES
(1, 1, 'A'),
(1, 3, 'B+'),
(2, 1, 'A-'),
(3, 2, 'B');
```

## Best Practices and Tips

As you continue learning MySQL, keep these important practices in mind:

**Data Integrity**: Always use appropriate constraints (NOT NULL, UNIQUE, FOREIGN KEY) to maintain data quality. Think of constraints as rules that help prevent mistakes.

**Naming Conventions**: Use clear, consistent names for tables and columns. Many developers use lowercase with underscores (like `student_id` rather than `StudentID`).

**Backup Strategy**: Regular backups are essential. You can create backups using `mysqldump` or through phpMyAdmin's export feature.

**Index Usage**: As your tables grow, consider adding indexes to columns you frequently search on. This is like creating an index in a book to find information faster.

**Security**: Never use the root account for applications. Create specific users with only the privileges they need.

This tutorial provides a solid foundation for working with MySQL. As you practice these concepts, you'll develop the intuition for designing efficient databases and writing effective queries. Remember that database design is often more art than science, requiring you to balance performance, maintainability, and business requirements.