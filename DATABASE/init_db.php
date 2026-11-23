<?php
/**
 * Script to initialize the database schema
 */

require_once 'db_config.php';

try {
    $sql = file_get_contents('setup_db.sql');
    
    // Using PDO to execute the multi-query SQL
    $pdo->exec($sql);
    
    echo "✅ Esquema de base de datos inicializado exitosamente.<br>";
} catch (PDOException $e) {
    echo "❌ Error al inicializar la base de datos: " . $e->getMessage() . "<br>";
}
?>
