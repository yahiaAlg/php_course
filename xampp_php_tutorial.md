# Complete XAMPP & PHP Tutorial: Setup, PATH Configuration, and Script Execution

## Table of Contents
1. [Installing XAMPP](#installing-xampp)
2. [Adding PHP and MySQL to PATH](#adding-php-and-mysql-to-path)
3. [Launching PHP Scripts with XAMPP](#launching-php-scripts-with-xampp)
4. [Using PHP Built-in Server](#using-php-built-in-server)
5. [Troubleshooting](#troubleshooting)

---

## Installing XAMPP

### Windows
1. Download XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Run the installer as Administrator
3. Choose components (Apache, MySQL, PHP, phpMyAdmin are typically selected by default)
4. Select installation directory (default: `C:\xampp`)
5. Complete the installation

### Linux (Ubuntu/Debian)
```bash
# Download XAMPP installer
wget https://downloadsapacheorg/xampp/8.2.12/xampp-linux-x64-8.2.12-0-installer.run

# Make it executable
chmod +x xampp-linux-x64-8.2.12-0-installer.run

# Run installer with sudo
sudo ./xampp-linux-x64-8.2.12-0-installer.run

# Follow the graphical installer
```

Default installation path: `/opt/lampp`

---

## Adding PHP and MySQL to PATH

### Windows

#### Method 1: Using Environment Variables GUI
1. Right-click "This PC" → Properties → Advanced System Settings
2. Click "Environment Variables"
3. Under "System Variables", find and select "Path", then click "Edit"
4. Click "New" and add these paths:
   ```
   C:\xampp\php
   C:\xampp\mysql\bin
   ```
5. Click "OK" to save all dialogs
6. Restart Command Prompt/PowerShell

#### Method 2: Using Command Line (Run as Administrator)
```cmd
# Add PHP to PATH
setx PATH "%PATH%;C:\xampp\php" /M

# Add MySQL to PATH
setx PATH "%PATH%;C:\xampp\mysql\bin" /M
```

#### Method 3: Using PowerShell (Run as Administrator)
```powershell
# Get current PATH
$currentPath = [Environment]::GetEnvironmentVariable("PATH", "Machine")

# Add PHP and MySQL paths
$newPath = $currentPath + ";C:\xampp\php;C:\xampp\mysql\bin"

# Set new PATH
[Environment]::SetEnvironmentVariable("PATH", $newPath, "Machine")
```

### Linux

#### Method 1: Temporary (current session only)
```bash
export PATH=$PATH:/opt/lampp/bin
```

#### Method 2: Permanent for current user
```bash
# Edit .bashrc or .zshrc
nano ~/.bashrc

# Add this line at the end:
export PATH=$PATH:/opt/lampp/bin

# Reload the file
source ~/.bashrc
```

#### Method 3: System-wide (all users)
```bash
# Create a new file in /etc/profile.d/
sudo nano /etc/profile.d/xampp.sh

# Add this content:
export PATH=$PATH:/opt/lampp/bin

# Make it executable
sudo chmod +x /etc/profile.d/xampp.sh

# Reload profile
source /etc/profile
```

### Verify Installation
Test if PHP and MySQL are in PATH:
```bash
# Check PHP version
php -v

# Check MySQL version
mysql --version
```

---

## Launching PHP Scripts with XAMPP

### Starting XAMPP Services

#### Windows
1. **Using XAMPP Control Panel:**
   - Open XAMPP Control Panel
   - Click "Start" for Apache and MySQL services
   - Services should show "Running" status

2. **Using Command Line:**
   ```cmd
   # Navigate to XAMPP directory
   cd C:\xampp

   # Start Apache
   apache\bin\httpd.exe

   # Start MySQL (in another terminal)
   mysql\bin\mysqld.exe --console
   ```

#### Linux
```bash
# Start all XAMPP services
sudo /opt/lampp/lampp start

# Start specific services
sudo /opt/lampp/lampp startapache
sudo /opt/lampp/lampp startmysql

# Stop services
sudo /opt/lampp/lampp stop

# Check status
sudo /opt/lampp/lampp status
```

### Running PHP Scripts in XAMPP

1. **Place your PHP files in the web root:**
   - Windows: `C:\xampp\htdocs\`
   - Linux: `/opt/lampp/htdocs/`

2. **Create a simple test script:**
   ```php
   <?php
   // Save as test.php in htdocs folder
   echo "Hello from XAMPP!<br>";
   echo "PHP Version: " . phpversion() . "<br>";
   echo "Current Time: " . date('Y-m-d H:i:s');
   ?>
   ```

3. **Access via web browser:**
   ```
   http://localhost/test.php
   ```

### Project Structure Example
```
htdocs/
├── myproject/
│   ├── index.php
│   ├── config.php
│   └── includes/
│       └── functions.php
└── test.php
```

Access: `http://localhost/myproject/`

---

## Using PHP Built-in Server

The PHP built-in development server is perfect for testing without Apache.

### Basic Usage

#### Starting the Server
```bash
# Navigate to your project directory
cd /path/to/your/project

# Start server on default port 8000
php -S localhost:8000

# Start on custom port
php -S localhost:3000

# Start on specific IP (accessible from network)
php -S 0.0.0.0:8000
```

#### Examples

**Example 1: Simple Script**
```bash
# Create a simple PHP file
echo "<?php echo 'Hello World from PHP Server!'; ?>" > hello.php

# Start server in the same directory
php -S localhost:8000

# Access: http://localhost:8000/hello.php
```

**Example 2: With Document Root**
```bash
# Specify a different document root
php -S localhost:8000 -t /path/to/web/root

# Windows example
php -S localhost:8000 -t C:\xampp\htdocs\myproject

# Linux example
php -S localhost:8000 -t /var/www/html/myproject
```

**Example 3: With Router Script**
```php
<?php
// router.php - Custom routing for the built-in server
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Route to index.php for non-file requests
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false; // Serve the requested file
}

// Include your main application file
require_once 'index.php';
?>
```

```bash
# Start server with router
php -S localhost:8000 router.php
```

### Advanced Server Options

#### With Specific Configuration
```bash
# Start with custom php.ini
php -S localhost:8000 -c /path/to/custom/php.ini

# Start in background (Linux/Mac)
nohup php -S localhost:8000 > server.log 2>&1 &

# View server logs
tail -f server.log
```

#### Multiple Projects
```bash
# Project 1
php -S localhost:8001 -t /path/to/project1

# Project 2 (different terminal)
php -S localhost:8002 -t /path/to/project2
```

### Complete Example: Running a Specific PHP Application

Let's say you have a project structure like this:
```
myapp/
├── index.php
├── about.php
├── css/
│   └── style.css
├── js/
│   └── script.js
└── includes/
    └── config.php
```

**Step-by-step execution:**

1. **Navigate to project directory:**
   ```bash
   # Windows
   cd C:\xampp\htdocs\myapp

   # Linux
   cd /opt/lampp/htdocs/myapp
   ```

2. **Start the server:**
   ```bash
   php -S localhost:8000
   ```

3. **Access your application:**
   - Main page: `http://localhost:8000/`
   - About page: `http://localhost:8000/about.php`
   - Direct file access: `http://localhost:8000/index.php`

### Real-world Example with Database Connection

**config.php:**
```php
<?php
$host = 'localhost';
$dbname = 'test_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
```

**index.php:**
```php
<?php
require_once 'config.php';

// Simple database query example
$stmt = $pdo->query("SELECT NOW() as current_time");
$result = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My PHP App</title>
</head>
<body>
    <h1>Welcome to My PHP Application</h1>
    <p>Current server time: <?php echo $result['current_time']; ?></p>
</body>
</html>
```

**Launch command:**
```bash
php -S localhost:8000
```

---

## Troubleshooting

### Common Issues

#### Port Already in Use
```bash
# Check what's using the port
netstat -an | grep :8000

# Use a different port
php -S localhost:8001
```

#### Permission Denied (Linux)
```bash
# Make sure you have permissions to the directory
sudo chown -R $USER:$USER /opt/lampp/htdocs/myproject

# Or run with sudo (not recommended for development)
sudo php -S localhost:8000
```

#### PATH Not Working
**Windows:**
- Restart Command Prompt after setting PATH
- Check PATH: `echo %PATH%`
- Verify installation: `where php`

**Linux:**
- Reload shell: `source ~/.bashrc`
- Check PATH: `echo $PATH`
- Verify installation: `which php`

#### MySQL Connection Issues
```bash
# Start MySQL service first
# Windows: Start from XAMPP Control Panel
# Linux: sudo /opt/lampp/lampp startmysql

# Test MySQL connection
mysql -u root -p
```

### Performance Tips

1. **Disable modules you don't need in XAMPP**
2. **Use the built-in server for development only**
3. **For production, use proper web servers (Apache/Nginx)**
4. **Enable error reporting during development:**
   ```php
   <?php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ?>
   ```

### Security Notes

- The built-in PHP server is for development only
- Don't use it for production websites
- XAMPP default settings are not secure for production
- Always change default MySQL root password
- Disable unnecessary services in production

---

## Quick Reference Commands

### XAMPP Control
```bash
# Windows
C:\xampp\xampp-control.exe

# Linux - Start/Stop/Status
sudo /opt/lampp/lampp start
sudo /opt/lampp/lampp stop
sudo /opt/lampp/lampp restart
sudo /opt/lampp/lampp status
```

### PHP Server Commands
```bash
# Basic server
php -S localhost:8000

# With document root
php -S localhost:8000 -t /path/to/project

# With router script
php -S localhost:8000 router.php

# Check PHP version
php -v

# Check PHP configuration
php -i
```

### Testing Your Setup
```bash
# Test PHP installation
php -r "echo 'PHP is working!'; echo PHP_EOL;"

# Test MySQL connection (after starting MySQL)
mysql -u root -e "SELECT 'MySQL is working!' as message;"
```

This tutorial provides everything you need to get started with XAMPP, configure your development environment, and run PHP scripts both through Apache and the built-in PHP server.