<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$host = config('database.connections.mysql.host');
$port = config('database.connections.mysql.port');
$db = config('database.connections.mysql.database');
$user = config('database.connections.mysql.username');
$pass = config('database.connections.mysql.password');

echo "Testing DB connection...\n";
echo "Host: $host\n";
echo "Port: $port\n";
echo "Database: $db\n";
echo "Username: $user\n\n";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass, [
        PDO::ATTR_TIMEOUT => 10,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "Connection successful!\n";
    echo "Server version: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
    $stmt = $pdo->query('SHOW TABLES');
    $tables = $stmt->fetchAll();
    echo "Number of tables: " . count($tables) . "\n";
} catch (Exception $e) {
    echo "Connection FAILED!\n";
    echo "Error: " . $e->getMessage() . "\n";
}
