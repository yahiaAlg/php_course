```php
// Common server variables:

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? '';
$serverProtocol = $_SERVER['SERVER_PROTOCOL'] ?? '';
$serverName = $_SERVER['SERVER_NAME'] ?? '';
$serverPort = $_SERVER['SERVER_PORT'] ?? '';
$serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? '';
$serverAdmin = $_SERVER['SERVER_ADMIN'] ?? '';
$documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
$scriptFilename = $_SERVER['SCRIPT_FILENAME'] ?? '';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$phpSelf = $_SERVER['PHP_SELF'] ?? '';
$remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '';
$connection = $_SERVER['HTTP_CONNECTION'] ?? '';
$host = $_SERVER['HTTP_HOST'] ?? '';
$referer = $_SERVER['HTTP_REFERER'] ?? '';
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$queryString = $_SERVER['QUERY_STRING'] ?? '';
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
```
