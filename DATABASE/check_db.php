<?php
// check_db.php — Verificar qué BD real está usando PHP
require_once 'db_config.php';
$host = $pdo->query("SELECT @@hostname, @@port, DATABASE()")->fetch();
echo "<pre>";
print_r($host);
echo "</pre>";
$empresas = $pdo->query("SELECT COUNT(*) as total FROM empresas")->fetch();
echo "Empresas en BD usada por PHP: " . $empresas['total'] . "<br>";
$rutas = $pdo->query("SELECT id, nombre, origen, destino FROM rutas LIMIT 5")->fetchAll();
echo "<pre>Rutas (primeras 5):\n";
print_r($rutas);
echo "</pre>";
$horarios = $pdo->query("SELECT id, ruta_id, fecha FROM horarios ORDER BY fecha LIMIT 5")->fetchAll();
echo "<pre>Horarios (más cercanos):\n";
print_r($horarios);
echo "</pre>";
?>
