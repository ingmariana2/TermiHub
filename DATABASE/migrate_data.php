<?php
/**
 * Script to migrate initial mock data to the database
 */

require_once 'db_config.php';

// Mock data from script.js
$terminal = [
    'nombre' => "Terminal de Transporte TermiHub",
    'ubicacion' => "Centro de la Ciudad",
    'telefono' => "(1) 555-1234",
    'email' => "info@termihub.com",
    'horario' => "Abierto 5:00 AM - 10:00 PM"
];

$empresas = [
    ['id' => 1, 'nombre' => "TravelMax", 'telefono' => "555-1001", 'email' => "info@travelmax.com"],
    ['id' => 2, 'nombre' => "TransExpress", 'telefono' => "555-1002", 'email' => "info@transexpress.com"],
    ['id' => 3, 'nombre' => "BusViajes", 'telefono' => "555-1003", 'email' => "info@busviajes.com"],
    ['id' => 4, 'nombre' => "TransGlobal", 'telefono' => "555-1004", 'email' => "info@transglobal.com"]
];

$vehiculos = [
    ['id' => 1, 'placa' => "ABC-123", 'tipo' => "Bus", 'capacidad' => 45, 'empresaId' => 1],
    ['id' => 2, 'placa' => "XYZ-789", 'tipo' => "Buseta", 'capacidad' => 30, 'empresaId' => 2],
    ['id' => 3, 'placa' => "MMM-456", 'tipo' => "Gacela", 'capacidad' => 15, 'empresaId' => 1],
    ['id' => 4, 'placa' => "OPQ-321", 'tipo' => "Bus", 'capacidad' => 45, 'empresaId' => 3],
    ['id' => 5, 'placa' => "RST-654", 'tipo' => "Taxi", 'capacidad' => 4, 'empresaId' => 4]
];

$rutas = [
    ['id' => 1, 'nombre' => "Bogotá - Medellín", 'origen' => "Bogotá", 'destino' => "Medellín", 'distancia' => 430, 'tarifa' => 80000],
    ['id' => 2, 'nombre' => "Medellín - Bogotá", 'origen' => "Medellín", 'destino' => "Bogotá", 'distancia' => 430, 'tarifa' => 80000],
    ['id' => 3, 'nombre' => "Bogotá - Cali", 'origen' => "Bogotá", 'destino' => "Cali", 'distancia' => 460, 'tarifa' => 85000],
    ['id' => 4, 'nombre' => "Cali - Bogotá", 'origen' => "Cali", 'destino' => "Bogotá", 'distancia' => 460, 'tarifa' => 85000],
    ['id' => 5, 'nombre' => "Bogotá - Armenia", 'origen' => "Bogotá", 'destino' => "Armenia", 'distancia' => 280, 'tarifa' => 55000],
    ['id' => 6, 'nombre' => "Armenia - Bogotá", 'origen' => "Armenia", 'destino' => "Bogotá", 'distancia' => 280, 'tarifa' => 55000],
    ['id' => 7, 'nombre' => "Bogotá - Pereira", 'origen' => "Bogotá", 'destino' => "Pereira", 'distancia' => 320, 'tarifa' => 60000],
    ['id' => 8, 'nombre' => "Pereira - Bogotá", 'origen' => "Pereira", 'destino' => "Bogotá", 'distancia' => 320, 'tarifa' => 60000],
    ['id' => 9, 'nombre' => "Medellín - Cali", 'origen' => "Medellín", 'destino' => "Cali", 'distancia' => 420, 'tarifa' => 75000],
    ['id' => 10, 'nombre' => "Cali - Medellín", 'origen' => "Cali", 'destino' => "Medellín", 'distancia' => 420, 'tarifa' => 75000],
    ['id' => 11, 'nombre' => "Medellín - Pereira", 'origen' => "Medellín", 'destino' => "Pereira", 'distancia' => 210, 'tarifa' => 45000],
    ['id' => 12, 'nombre' => "Pereira - Medellín", 'origen' => "Pereira", 'destino' => "Medellín", 'distancia' => 210, 'tarifa' => 45000],
    ['id' => 13, 'nombre' => "Cali - Armenia", 'origen' => "Cali", 'destino' => "Armenia", 'distancia' => 180, 'tarifa' => 35000],
    ['id' => 14, 'nombre' => "Armenia - Cali", 'origen' => "Armenia", 'destino' => "Cali", 'distancia' => 180, 'tarifa' => 35000],
    ['id' => 15, 'nombre' => "Cartagena - Barranquilla", 'origen' => "Cartagena", 'destino' => "Barranquilla", 'distancia' => 120, 'tarifa' => 25000],
    ['id' => 16, 'nombre' => "Barranquilla - Cartagena", 'origen' => "Barranquilla", 'destino' => "Cartagena", 'distancia' => 120, 'tarifa' => 25000],
    ['id' => 17, 'nombre' => "Bogotá - Cartagena", 'origen' => "Bogotá", 'destino' => "Cartagena", 'distancia' => 1000, 'tarifa' => 150000]
];

$horarios = [
    // Bogotá - Medellín
    ['id' => 1, 'rutaId' => 1, 'vehiculoId' => 1, 'fecha' => "2026-03-25", 'horaSalida' => "06:00", 'horaLlegada' => "14:00", 'cupos' => 40],
    ['id' => 2, 'rutaId' => 1, 'vehiculoId' => 2, 'fecha' => "2026-03-25", 'horaSalida' => "14:00", 'horaLlegada' => "22:00", 'cupos' => 30],
    // Medellín - Bogotá
    ['id' => 3, 'rutaId' => 2, 'vehiculoId' => 4, 'fecha' => "2026-03-25", 'horaSalida' => "08:00", 'horaLlegada' => "16:00", 'cupos' => 40],
    // Bogotá - Armenia
    ['id' => 4, 'rutaId' => 5, 'vehiculoId' => 3, 'fecha' => "2026-03-25", 'horaSalida' => "07:30", 'horaLlegada' => "13:30", 'cupos' => 15],
    // Armenia - Bogotá
    ['id' => 5, 'rutaId' => 6, 'vehiculoId' => 1, 'fecha' => "2026-03-26", 'horaSalida' => "09:00", 'horaLlegada' => "16:00", 'cupos' => 40],
    // Cali - Armenia
    ['id' => 6, 'rutaId' => 13, 'vehiculoId' => 5, 'fecha' => "2026-03-25", 'horaSalida' => "10:00", 'horaLlegada' => "13:00", 'cupos' => 4],
    // Cartagena - Barranquilla
    ['id' => 7, 'rutaId' => 15, 'vehiculoId' => 2, 'fecha' => "2026-03-25", 'horaSalida' => "05:00", 'horaLlegada' => "07:00", 'cupos' => 30],
    ['id' => 8, 'rutaId' => 15, 'vehiculoId' => 2, 'fecha' => "2026-03-25", 'horaSalida' => "08:00", 'horaLlegada' => "10:00", 'cupos' => 30],
    ['id' => 9, 'rutaId' => 15, 'vehiculoId' => 2, 'fecha' => "2026-03-25", 'horaSalida' => "12:00", 'horaLlegada' => "14:00", 'cupos' => 30]
];

try {
    $pdo->beginTransaction();

    // 1. Terminal Info
    $stmt = $pdo->prepare("INSERT INTO terminal_info (nombre, ubicacion, telefono, email, horario) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$terminal['nombre'], $terminal['ubicacion'], $terminal['telefono'], $terminal['email'], $terminal['horario']]);

    // 2. Empresas
    $stmt = $pdo->prepare("INSERT INTO empresas (id, nombre, telefono, email) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE nombre=VALUES(nombre)");
    foreach ($empresas as $e) {
        $stmt->execute([$e['id'], $e['nombre'], $e['telefono'], $e['email']]);
    }

    // 3. Vehículos
    $stmt = $pdo->prepare("INSERT INTO vehiculos (id, placa, tipo, capacidad, empresa_id) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE placa=VALUES(placa)");
    foreach ($vehiculos as $v) {
        $stmt->execute([$v['id'], $v['placa'], $v['tipo'], $v['capacidad'], $v['empresaId']]);
    }

    // 4. Rutas
    $stmt = $pdo->prepare("INSERT INTO rutas (id, nombre, origen, destino, distancia, tarifa) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE nombre=VALUES(nombre)");
    foreach ($rutas as $r) {
        $stmt->execute([$r['id'], $r['nombre'], $r['origen'], $r['destino'], $r['distancia'], $r['tarifa']]);
    }

    // 5. Horarios
    $stmt = $pdo->prepare("INSERT INTO horarios (id, ruta_id, vehiculo_id, fecha, hora_salida, hora_llegada, cupos) VALUES (?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE cupos=VALUES(cupos)");
    foreach ($horarios as $h) {
        $stmt->execute([$h['id'], $h['rutaId'], $h['vehiculoId'], $h['fecha'], $h['horaSalida'], $h['horaLlegada'], $h['cupos']]);
    }

    $pdo->commit();
    echo "✅ Datos migrados exitosamente.<br>";

} catch (PDOException $e) {
    $pdo->rollBack();
    echo "❌ Error al migrar datos: " . $e->getMessage() . "<br>";
}
?>
