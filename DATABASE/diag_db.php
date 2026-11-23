<?php
// Diagnose DB issue — show exact error
require_once 'db_config.php';

try {
    // Test 1: Check tables exist and count rows
    $tables = ['terminal_info', 'empresas', 'vehiculos', 'rutas', 'horarios', 'ventas'];
    foreach ($tables as $t) {
        $count = $pdo->query("SELECT COUNT(*) FROM $t")->fetchColumn();
        echo "Table <b>$t</b>: $count rows<br>";
    }

    // Test 2: Try a simple insert into empresas after truncate
    echo "<br>Testing TRUNCATE on ventas...<br>";
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("TRUNCATE TABLE ventas");
    echo "ventas truncated OK<br>";
    $pdo->exec("TRUNCATE TABLE horarios");
    echo "horarios truncated OK<br>";
    $pdo->exec("TRUNCATE TABLE rutas");
    echo "rutas truncated OK<br>";
    $pdo->exec("TRUNCATE TABLE vehiculos");
    echo "vehiculos truncated OK<br>";
    $pdo->exec("TRUNCATE TABLE empresas");
    echo "empresas truncated OK<br>";
    $pdo->exec("TRUNCATE TABLE terminal_info");
    echo "terminal_info truncated OK<br>";
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo "<br><b>All truncates succeeded!</b><br>";
} catch (Exception $e) {
    echo "<b>Error:</b> " . $e->getMessage() . "<br>";
    echo "<b>Code:</b> " . $e->getCode() . "<br>";
}
?>
