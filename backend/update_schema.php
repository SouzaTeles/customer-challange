<?php

require __DIR__ . '/vendor/autoload.php';

use App\Database\Database;

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    $sql = file_get_contents(__DIR__ . '/database/schema.sql');
    
    if (!$sql) {
        die("Could not read schema.sql\n");
    }

    // Split by semicolon to execute individually if needed, 
    // but PDO usually handles multiple statements if emulation is on. 
    // Let's try executing directly first.
    
    $conn->exec($sql);
    
    echo "Schema updated successfully.\n";

} catch (Exception $e) {
    echo "Error updating schema: " . $e->getMessage() . "\n";
    exit(1);
}
