<?php
/**
 * Script to test the database connection
 */

require_once 'db_config.php';

echo "<h2>Validación de Conexión a Base de Datos</h2>";

try {
    if (isset($pdo)) {
        echo "<p style='color: green;'>✅ Conexión exitosa a la base de datos: <strong>termihub_db</strong></p>";
        
        // Test query
        $stmt = $pdo->query("SELECT VERSION() as version");
        $row = $stmt->fetch();
        echo "<p>Versión del Servidor MySQL: " . $row['version'] . "</p>";
        
        // List tables if any
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($tables) > 0) {
            echo "<h3>Tablas encontradas:</h3><ul>";
            foreach ($tables as $table) {
                echo "<li>$table</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>⚠️ No se encontraron tablas en la base de datos.</p>";
        }

    } else {
        echo "<p style='color: red;'>❌ La variable de conexión \$pdo no está definida.</p>";
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error de conexión: " . $e->getMessage() . "</p>";
}
?>
