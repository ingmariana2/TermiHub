<?php
/**
 * force_reset.php 
 * Fuerza el reset completo de la BD desde PHP mismo (sin transacciones).
 */
require_once 'db_config.php';

$log = [];

function q($pdo, $sql, &$log) {
    try {
        $result = $pdo->exec($sql);
        $log[] = "OK: " . substr($sql, 0, 60) . " | affected=$result";
        return true;
    } catch (Exception $e) {
        $log[] = "ERR: " . $e->getMessage() . " | " . substr($sql, 0, 60);
        return false;
    }
}

// PASO 1: Contar filas antes
$antes = [
    'rutas' => $pdo->query("SELECT COUNT(*) FROM rutas")->fetchColumn(),
    'horarios' => $pdo->query("SELECT COUNT(*) FROM horarios")->fetchColumn(),
];
$log[] = "ANTES: rutas={$antes['rutas']}, horarios={$antes['horarios']}";

// PASO 2: Limpiar con DELETE (no TRUNCATE para evitar commits implícitos de DDL)
q($pdo, "SET FOREIGN_KEY_CHECKS=0", $log);
q($pdo, "DELETE FROM ventas", $log);
q($pdo, "DELETE FROM horarios", $log);
q($pdo, "DELETE FROM rutas", $log);
q($pdo, "DELETE FROM vehiculos", $log);
q($pdo, "DELETE FROM empresas", $log);
q($pdo, "DELETE FROM terminal_info", $log);
q($pdo, "ALTER TABLE ventas AUTO_INCREMENT=1", $log);
q($pdo, "ALTER TABLE horarios AUTO_INCREMENT=1", $log);
q($pdo, "ALTER TABLE rutas AUTO_INCREMENT=1", $log);
q($pdo, "ALTER TABLE vehiculos AUTO_INCREMENT=1", $log);
q($pdo, "ALTER TABLE empresas AUTO_INCREMENT=1", $log);
q($pdo, "ALTER TABLE terminal_info AUTO_INCREMENT=1", $log);
q($pdo, "SET FOREIGN_KEY_CHECKS=1", $log);

// Contar después de limpiar
$medio = [
    'rutas' => $pdo->query("SELECT COUNT(*) FROM rutas")->fetchColumn(),
    'horarios' => $pdo->query("SELECT COUNT(*) FROM horarios")->fetchColumn(),
];
$log[] = "POST-DELETE: rutas={$medio['rutas']}, horarios={$medio['horarios']}";

// PASO 3: Poblar
q($pdo, "INSERT INTO terminal_info (nombre, ubicacion, telefono, email, horario) VALUES ('Terminal de Transporte TermiHub','Centro de la Ciudad','(1) 555-1234','info@termihub.com','Abierto 5:00 AM - 10:00 PM')", $log);
q($pdo, "INSERT INTO empresas (id, nombre, telefono, email) VALUES (1,'TravelMax','555-1001','info@travelmax.com'),(2,'TransExpress','555-1002','info@transexpress.com'),(3,'BusViajes','555-1003','info@busviajes.com'),(4,'TransGlobal','555-1004','info@transglobal.com')", $log);
q($pdo, "INSERT INTO vehiculos (id, placa, tipo, capacidad, empresa_id) VALUES (1,'ABC-123','Bus',45,1),(2,'XYZ-789','Buseta',30,2),(3,'MMM-456','Gacela',15,1),(4,'OPQ-321','Bus',45,3),(5,'RST-654','Van',15,4)", $log);

q($pdo, "INSERT INTO rutas (id, nombre, origen, destino, distancia, tarifa) VALUES
(1,'Bogota - Medellin','Bogota','Medellin',430,75000),
(2,'Medellin - Bogota','Medellin','Bogota',430,75000),
(3,'Bogota - Cali','Bogota','Cali',460,85000),
(4,'Cali - Bogota','Cali','Bogota',460,85000),
(5,'Bogota - Armenia','Bogota','Armenia',280,55000),
(6,'Armenia - Bogota','Armenia','Bogota',280,55000),
(7,'Bogota - Pereira','Bogota','Pereira',320,60000),
(8,'Pereira - Bogota','Pereira','Bogota',320,60000),
(9,'Medellin - Cali','Medellin','Cali',420,70000),
(10,'Cali - Medellin','Cali','Medellin',420,70000),
(11,'Medellin - Pereira','Medellin','Pereira',210,45000),
(12,'Pereira - Medellin','Pereira','Medellin',210,45000),
(13,'Cali - Armenia','Cali','Armenia',180,35000),
(14,'Armenia - Cali','Armenia','Cali',180,35000),
(15,'Cartagena - Barranquilla','Cartagena','Barranquilla',120,25000),
(16,'Barranquilla - Cartagena','Barranquilla','Cartagena',120,25000),
(17,'Bogota - Cartagena','Bogota','Cartagena',1000,150000),
(18,'Armenia - Medellin','Armenia','Medellin',290,50000),
(19,'Medellin - Armenia','Medellin','Armenia',290,50000),
(20,'Bogota - Barranquilla','Bogota','Barranquilla',980,145000)", $log);

// Horarios con fechas futuras
$d1 = date('Y-m-d', strtotime('+3 days'));
$d2 = date('Y-m-d', strtotime('+4 days'));
$d3 = date('Y-m-d', strtotime('+7 days'));
$d4 = date('Y-m-d', strtotime('+9 days'));
$d5 = date('Y-m-d', strtotime('+14 days'));
$d6 = date('Y-m-d', strtotime('+15 days'));

$horarios = [
    [1,1,$d1,'06:00','14:00',40],[1,2,$d1,'14:00','22:00',30],[1,4,$d3,'07:00','15:00',40],[1,1,$d5,'06:00','14:00',40],
    [2,4,$d1,'08:00','16:00',40],[2,1,$d3,'09:00','17:00',30],[2,2,$d5,'08:00','16:00',30],
    [3,1,$d1,'05:00','17:00',40],[3,4,$d3,'06:00','18:00',40],[3,2,$d5,'07:00','19:00',30],
    [4,3,$d2,'07:00','19:00',15],[4,1,$d4,'06:00','18:00',40],[4,4,$d6,'08:00','20:00',40],
    [5,3,$d1,'07:30','13:30',15],[5,2,$d3,'08:00','14:00',30],[5,5,$d5,'09:00','15:00',15],
    [6,1,$d2,'09:00','15:00',40],[6,4,$d4,'08:00','14:00',40],[6,2,$d6,'10:00','16:00',30],
    [7,2,$d1,'08:00','14:30',30],[7,4,$d3,'09:00','15:30',40],
    [8,1,$d2,'07:00','13:30',40],[8,3,$d5,'06:00','12:30',15],
    [9,4,$d1,'06:00','14:00',40],[9,2,$d4,'07:00','15:00',30],
    [10,1,$d2,'06:00','14:00',40],[10,3,$d5,'08:00','16:00',15],
    [11,5,$d1,'10:00','14:00',15],[11,3,$d3,'11:00','15:00',15],
    [12,2,$d2,'09:00','13:00',30],
    [13,5,$d1,'10:00','13:00',15],[13,3,$d4,'11:00','14:00',15],
    [14,5,$d2,'09:00','12:00',15],
    [15,2,$d1,'05:00','07:00',30],[15,2,$d1,'12:00','14:00',30],[15,4,$d3,'08:00','10:00',40],[15,2,$d5,'06:00','08:00',30],
    [16,4,$d2,'07:00','09:00',40],[16,2,$d4,'10:00','12:00',30],
    [17,1,$d3,'06:00','20:00',40],[17,4,$d6,'07:00','21:00',40],
    [18,5,$d1,'08:00','13:00',15],[18,3,$d4,'09:00','14:00',15],
    [19,3,$d2,'07:00','12:00',15],
    [20,1,$d3,'05:00','19:00',40],[20,4,$d6,'06:00','20:00',40],
];
$stmt = $pdo->prepare("INSERT INTO horarios (ruta_id, vehiculo_id, fecha, hora_salida, hora_llegada, cupos) VALUES (?,?,?,?,?,?)");
$countH = 0;
foreach ($horarios as $h) {
    try { $stmt->execute($h); $countH++; } catch(Exception $e) { $log[] = "Horario ERR: " . $e->getMessage(); }
}
$log[] = "Horarios insertados: $countH";

// Verificar resultado final
$despues = [
    'rutas' => $pdo->query("SELECT COUNT(*) FROM rutas")->fetchColumn(),
    'horarios' => $pdo->query("SELECT COUNT(*) FROM horarios")->fetchColumn(),
    'muestra' => $pdo->query("SELECT id, origen, destino, fecha FROM rutas r JOIN horarios h ON r.id=h.ruta_id ORDER BY h.fecha LIMIT 3")->fetchAll()
];
$log[] = "FINAL: rutas={$despues['rutas']}, horarios={$despues['horarios']}";

echo "<h2>Force Reset Log</h2><pre>" . implode("\n", $log) . "</pre>";
echo "<h3>Muestra datos:</h3><pre>"; print_r($despues['muestra']); echo "</pre>";
echo "<p><a href='/'>-> Ver la página</a></p>";
?>
