<?php
/**
 * reset_db.php — Limpia y repuebla la BD con datos correctos y fechas futuras.
 */
require_once 'db_config.php';

$errores = [];

function ejecutar($pdo, $sql, &$errores) {
    try {
        $pdo->exec($sql);
        return true;
    } catch (Exception $e) {
        $errores[] = $e->getMessage() . " | SQL: " . substr($sql, 0, 80);
        return false;
    }
}

// Deshabilitar FK checks
ejecutar($pdo, "SET FOREIGN_KEY_CHECKS = 0", $errores);

// Limpiar tablas
ejecutar($pdo, "TRUNCATE TABLE ventas", $errores);
ejecutar($pdo, "TRUNCATE TABLE horarios", $errores);
ejecutar($pdo, "TRUNCATE TABLE rutas", $errores);
ejecutar($pdo, "TRUNCATE TABLE vehiculos", $errores);
ejecutar($pdo, "TRUNCATE TABLE empresas", $errores);
ejecutar($pdo, "TRUNCATE TABLE terminal_info", $errores);

ejecutar($pdo, "SET FOREIGN_KEY_CHECKS = 1", $errores);

// Terminal info
ejecutar($pdo, "INSERT INTO terminal_info (nombre, ubicacion, telefono, email, horario) VALUES (
    'Terminal de Transporte TermiHub','Centro de la Ciudad','(1) 555-1234','info@termihub.com','Abierto 5:00 AM - 10:00 PM'
)", $errores);

// Empresas
ejecutar($pdo, "INSERT INTO empresas (id, nombre, telefono, email) VALUES
    (1,'TravelMax','555-1001','info@travelmax.com'),
    (2,'TransExpress','555-1002','info@transexpress.com'),
    (3,'BusViajes','555-1003','info@busviajes.com'),
    (4,'TransGlobal','555-1004','info@transglobal.com')", $errores);

// Vehículos
ejecutar($pdo, "INSERT INTO vehiculos (id, placa, tipo, capacidad, empresa_id) VALUES
    (1,'ABC-123','Bus',45,1),
    (2,'XYZ-789','Buseta',30,2),
    (3,'MMM-456','Gacela',15,1),
    (4,'OPQ-321','Bus',45,3),
    (5,'RST-654','Van',15,4)", $errores);

// Rutas (correctas)
ejecutar($pdo, "INSERT INTO rutas (id, nombre, origen, destino, distancia, tarifa) VALUES
    (1,'Bogotá - Medellín','Bogotá','Medellín',430,75000),
    (2,'Medellín - Bogotá','Medellín','Bogotá',430,75000),
    (3,'Bogotá - Cali','Bogotá','Cali',460,85000),
    (4,'Cali - Bogotá','Cali','Bogotá',460,85000),
    (5,'Bogotá - Armenia','Bogotá','Armenia',280,55000),
    (6,'Armenia - Bogotá','Armenia','Bogotá',280,55000),
    (7,'Bogotá - Pereira','Bogotá','Pereira',320,60000),
    (8,'Pereira - Bogotá','Pereira','Bogotá',320,60000),
    (9,'Medellín - Cali','Medellín','Cali',420,70000),
    (10,'Cali - Medellín','Cali','Medellín',420,70000),
    (11,'Medellín - Pereira','Medellín','Pereira',210,45000),
    (12,'Pereira - Medellín','Pereira','Medellín',210,45000),
    (13,'Cali - Armenia','Cali','Armenia',180,35000),
    (14,'Armenia - Cali','Armenia','Cali',180,35000),
    (15,'Cartagena - Barranquilla','Cartagena','Barranquilla',120,25000),
    (16,'Barranquilla - Cartagena','Barranquilla','Cartagena',120,25000),
    (17,'Bogotá - Cartagena','Bogotá','Cartagena',1000,150000),
    (18,'Armenia - Medellín','Armenia','Medellín',290,50000),
    (19,'Medellín - Armenia','Medellín','Armenia',290,50000),
    (20,'Bogotá - Barranquilla','Bogotá','Barranquilla',980,145000)", $errores);

// Fechas futuras dinámicas
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
foreach ($horarios as $h) {
    try {
        $stmt->execute($h);
    } catch (Exception $e) {
        $errores[] = "Horario error: " . $e->getMessage();
    }
}

// Resultado
if (empty($errores)) {
    echo "<h2 style='color:green'>✅ Base de datos reseteada correctamente</h2>";
    echo "<p>Fechas: <b>$d1 · $d2 · $d3 · $d4 · $d5 · $d6</b></p>";
    echo "<p>4 empresas · 5 vehículos · 20 rutas · " . count($horarios) . " horarios</p>";
    echo "<p><a href='/'>→ Ir a la página principal</a></p>";
} else {
    echo "<h2 style='color:orange'>⚠️ Completado con errores:</h2><ul>";
    foreach ($errores as $err) echo "<li>" . htmlspecialchars($err) . "</li>";
    echo "</ul>";
}
?>
