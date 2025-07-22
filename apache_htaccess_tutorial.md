# Complete Apache & .htaccess Tutorial for PHP Beginners

## Table of Contents
1. [HTTP Fundamentals](#http-fundamentals)
2. [Apache Web Server Basics](#apache-web-server-basics)
3. [Introduction to .htaccess](#introduction-to-htaccess)
4. [Core .htaccess Directives](#core-htaccess-directives)
5. [URL Rewriting with mod_rewrite](#url-rewriting-with-mod_rewrite)
6. [Security with .htaccess](#security-with-htaccess)
7. [Performance Optimization](#performance-optimization)
8. [PHP-Specific Configurations](#php-specific-configurations)
9. [Troubleshooting Common Issues](#troubleshooting-common-issues)
10. [Best Practices](#best-practices)

---

## HTTP Fundamentals

### What is HTTP?
HTTP (HyperText Transfer Protocol) is the foundation of web communication. Think of it as the language that web browsers and servers use to talk to each other. When you type a URL in your browser, you're initiating an HTTP conversation.

### HTTP Request-Response Cycle
The web works on a simple principle: **request and response**. Here's how it works:

1. **Client Request**: Your browser sends a request to the server
2. **Server Processing**: The server processes the request
3. **Server Response**: The server sends back a response
4. **Client Display**: Your browser displays the response

### HTTP Methods
HTTP defines several methods (verbs) that indicate the desired action:

- **GET**: Retrieve data (most common)
- **POST**: Submit data to be processed
- **PUT**: Update existing data
- **DELETE**: Remove data
- **HEAD**: Get headers only (no body)

### HTTP Status Codes
Every HTTP response includes a status code that tells you what happened:

- **200 OK**: Everything worked perfectly
- **301 Moved Permanently**: Resource has moved to a new URL
- **404 Not Found**: The requested resource doesn't exist
- **500 Internal Server Error**: Something went wrong on the server

### HTTP Headers
Headers are like metadata that provide additional information about requests and responses. Common headers include:

- **Content-Type**: Tells the browser what type of content is being sent
- **Cache-Control**: Controls how content should be cached
- **Location**: Used for redirects to specify the new URL

---

## Apache Web Server Basics

### What is Apache?
Apache HTTP Server is like a digital waiter in a restaurant. When customers (browsers) place orders (requests), Apache takes those orders, goes to the kitchen (your PHP scripts), gets the food (web pages), and serves it back to the customers.

### How Apache Works
Apache operates on a modular architecture, meaning it can load different modules to handle different tasks:

- **mod_rewrite**: Handles URL rewriting
- **mod_php**: Processes PHP scripts
- **mod_ssl**: Handles HTTPS encryption
- **mod_deflate**: Compresses content for faster delivery

### Apache Configuration Hierarchy
Apache uses a hierarchical configuration system:

1. **Main Configuration** (httpd.conf): Global server settings
2. **Virtual Host Configuration**: Settings for specific websites
3. **.htaccess Files**: Directory-specific settings (our focus)

The beauty of .htaccess is that it allows you to modify Apache's behavior without touching the main server configuration.

---

## Introduction to .htaccess

### What is .htaccess?
The .htaccess file is Apache's "per-directory configuration file." The name stands for "hypertext access," and the dot prefix makes it a hidden file on Unix-like systems.

Think of .htaccess as a set of instructions you leave for Apache in specific directories. When Apache encounters a request for a file in that directory, it reads your .htaccess file first and follows your instructions.

### How .htaccess Works
Apache processes .htaccess files in a specific order:

1. It starts from the document root
2. Works its way down through each directory
3. Applies rules from each .htaccess file it encounters
4. Later rules can override earlier ones

### Creating Your First .htaccess File
Create a file named exactly `.htaccess` (with the dot) in your website's root directory. Here's a simple example:

```apache
# This is a comment in .htaccess
# Enable URL rewriting
RewriteEngine On

# Redirect all traffic to HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### File Placement Strategy
Where you place your .htaccess file determines its scope:

- **Root directory**: Affects entire website
- **Subdirectory**: Affects only that directory and its subdirectories
- **Multiple files**: You can have different .htaccess files in different directories

---

## Core .htaccess Directives

### DirectoryIndex Directive
This directive tells Apache which file to serve when someone visits a directory without specifying a filename.

```apache
# Set the default file to serve
DirectoryIndex index.php index.html home.php

# This means Apache will look for files in this order:
# 1. index.php
# 2. index.html  
# 3. home.php
```

**Real-world example**: When someone visits `yoursite.com/blog/`, Apache automatically serves `yoursite.com/blog/index.php` if it exists.

### Options Directive
The Options directive controls various server features for a directory:

```apache
# Disable directory browsing (security best practice)
Options -Indexes

# Allow following symbolic links
Options +FollowSymLinks

# Combine multiple options
Options +FollowSymLinks -Indexes
```

**Why disable Indexes?** Without an index file, Apache would show a list of all files in the directory. This is a security risk because visitors could see files you don't want them to access.

### ErrorDocument Directive
Create custom error pages for a better user experience:

```apache
# Custom 404 error page
ErrorDocument 404 /errors/404.php

# Custom 500 error page  
ErrorDocument 500 /errors/500.php

# You can also redirect to external URLs
ErrorDocument 403 https://yoursite.com/access-denied
```

### Deny and Allow Directives
Control access to your directories:

```apache
# Block specific IP addresses
<RequireAll>
    Require all granted
    Require not ip 192.168.1.100
    Require not ip 10.0.0.0/8
</RequireAll>

# Allow only specific IP addresses
<RequireAll>
    Require ip 192.168.1.50
    Require ip 203.0.113.0/24
</RequireAll>
```

---

## URL Rewriting with mod_rewrite

### Understanding URL Rewriting
URL rewriting is like having a receptionist who redirects visitors to the right office. Instead of exposing your actual file structure, you can create clean, user-friendly URLs.

**Before rewriting**: `yoursite.com/product.php?id=123&category=electronics`
**After rewriting**: `yoursite.com/product/123/electronics`

### RewriteEngine Directive
Always start your rewriting rules by enabling the rewrite engine:

```apache
RewriteEngine On
```

### RewriteRule Syntax
The basic syntax is: `RewriteRule Pattern Substitution [Flags]`

```apache
# Basic example: Redirect all .html requests to .php
RewriteRule ^(.*)\.html$ $1.php [L]

# This breaks down as:
# ^ = start of string
# (.*) = capture any characters (stored in $1)
# \.html$ = literal ".html" at end of string
# $1.php = replace with captured content + ".php"
# [L] = Last rule, stop processing
```

### RewriteCond Directive
RewriteCond adds conditions that must be met before a RewriteRule is applied:

```apache
# Only apply rule if HTTPS is off
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Only apply rule if file doesn't exist
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [L]
```

### Common Rewrite Patterns

#### Clean URLs for Dynamic Content
```apache
# Convert: /product/123 to /product.php?id=123
RewriteRule ^product/([0-9]+)/?$ product.php?id=$1 [L,QSA]

# Convert: /user/john/profile to /user.php?name=john&page=profile
RewriteRule ^user/([^/]+)/([^/]+)/?$ user.php?name=$1&page=$2 [L,QSA]
```

#### Removing File Extensions
```apache
# Remove .php extension from URLs
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^([^.]+)$ $1.php [L]

# Redirect .php URLs to clean URLs
RewriteCond %{THE_REQUEST} /([^.]+)\.php [NC]
RewriteRule ^ /%1 [R=301,L]
```

### Understanding Flags
Flags modify how RewriteRule behaves:

- **[L]**: Last rule - stop processing additional rules
- **[R=301]**: Redirect with 301 status (permanent redirect)
- **[QSA]**: Query String Append - preserve existing query parameters
- **[NC]**: No Case - case-insensitive matching
- **[E=var:val]**: Set environment variable

---

## Security with .htaccess

### Protecting Sensitive Files
```apache
# Protect .htaccess itself
<Files ".htaccess">
    Require all denied
</Files>

# Protect configuration files
<FilesMatch "\.(inc|conf|config)$">
    Require all denied
</FilesMatch>

# Protect backup files
<FilesMatch "\.(bak|backup|old|tmp)$">
    Require all denied
</FilesMatch>
```

### Blocking Malicious Requests
```apache
# Block requests with suspicious query strings
RewriteCond %{QUERY_STRING} (<|%3C).*script.*(>|%3E) [NC]
RewriteRule .* - [F,L]

# Block requests trying to access system files
RewriteCond %{REQUEST_URI} \.(htaccess|htpasswd|ini|log|sh|inc|bak)$ [NC]
RewriteRule .* - [F,L]
```

### Hotlinking Protection
Prevent other sites from directly linking to your images:

```apache
# Allow hotlinking only from your domain
RewriteCond %{HTTP_REFERER} !^$
RewriteCond %{HTTP_REFERER} !^https?://(www\.)?yoursite\.com [NC]
RewriteRule \.(jpg|jpeg|png|gif)$ /images/blocked.jpg [L]
```

### Basic Authentication
```apache
# Protect admin directory with password
AuthType Basic
AuthName "Admin Area"
AuthUserFile /path/to/.htpasswd
Require valid-user
```

---

## Performance Optimization

### Compression with mod_deflate
```apache
# Enable compression for text-based files
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
```

### Browser Caching
```apache
# Set cache headers for static files
<IfModule mod_expires.c>
    ExpiresActive on
    
    # Images
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    
    # CSS and JavaScript
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    
    # HTML
    ExpiresByType text/html "access plus 1 week"
</IfModule>
```

### ETags for Better Caching
```apache
# Configure ETags for better caching
<IfModule mod_headers.c>
    # Remove ETags for better caching
    Header unset ETag
    FileETag None
</IfModule>
```

---

## PHP-Specific Configurations

### PHP Settings via .htaccess
```apache
# Increase memory limit
php_value memory_limit 256M

# Set error reporting
php_value error_reporting "E_ALL & ~E_NOTICE"
php_flag display_errors Off
php_flag log_errors On
php_value error_log /path/to/error.log

# Set timezone
php_value date.timezone "America/New_York"

# File upload settings
php_value upload_max_filesize 10M
php_value post_max_size 10M
```

### Handling PHP Errors
```apache
# Hide PHP errors from visitors
php_flag display_errors Off
php_flag display_startup_errors Off

# Log errors instead
php_flag log_errors On
php_value error_log /path/to/php_errors.log

# Custom error pages for PHP errors
ErrorDocument 500 /errors/500.php
```

### PHP Version Selection
```apache
# Force specific PHP version (hosting-dependent)
AddHandler application/x-httpd-php74 .php

# Or using different syntax
<FilesMatch "\.php$">
    SetHandler application/x-httpd-php74
</FilesMatch>
```

---

## Troubleshooting Common Issues

### 500 Internal Server Error
This is the most common .htaccess error. Common causes:

1. **Syntax errors**: Check for typos in directives
2. **Unsupported modules**: Using directives for disabled modules
3. **Incorrect file permissions**: .htaccess should be readable (644)

**Debugging approach**:
```apache
# Start with minimal .htaccess
RewriteEngine On

# Add rules one by one to identify the problem
# Check error logs: tail -f /path/to/error.log
```

### Redirect Loops
Happens when rewrite rules create infinite redirects:

```apache
# WRONG - creates infinite loop
RewriteRule ^(.*)$ /$1 [L,R=301]

# CORRECT - add condition to prevent loop
RewriteCond %{REQUEST_URI} !^/newpath/
RewriteRule ^(.*)$ /newpath/$1 [L,R=301]
```

### Rules Not Working
Common issues and solutions:

1. **RewriteEngine not enabled**: Always start with `RewriteEngine On`
2. **Wrong rule order**: More specific rules should come first
3. **Module not enabled**: Check if mod_rewrite is enabled
4. **Caching issues**: Clear browser cache and test

---

## Best Practices

### Organization and Comments
```apache
# =====================================
# SECURITY SETTINGS
# =====================================

# Protect sensitive files
<Files ".htaccess">
    Require all denied
</Files>

# =====================================
# URL REWRITING
# =====================================

RewriteEngine On

# Remove trailing slashes
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)/$ /$1 [R=301,L]
```

### Performance Considerations
- **Minimize .htaccess files**: Each directory traversal reads .htaccess
- **Use main config when possible**: Main Apache config is faster
- **Avoid complex regex**: Simple patterns perform better
- **Test thoroughly**: Always test changes on staging first

### Security Guidelines
- **Principle of least privilege**: Only allow what's necessary
- **Regular updates**: Keep rules current with threats
- **Monitor logs**: Watch for suspicious activity
- **Backup configurations**: Save working .htaccess files

### Testing Your Configuration
```apache
# Enable rewrite logging for debugging
RewriteEngine On
RewriteLog /path/to/rewrite.log
RewriteLogLevel 3

# Test with curl
# curl -I http://yoursite.com/test-url
```

---

## Conclusion

The .htaccess file is a powerful tool that gives you granular control over your website's behavior. Start with simple rules and gradually build complexity as you become more comfortable with the syntax.

Remember that .htaccess is processed for every request, so use it judiciously. When possible, implement rules in your main Apache configuration for better performance.

The key to mastering .htaccess is practice and understanding the underlying HTTP concepts. Start with basic redirects and security rules, then gradually explore more advanced features like complex URL rewriting and performance optimization.

Keep this tutorial handy as a reference, and don't hesitate to experiment with small changes to see how they affect your website's behavior. Happy coding!