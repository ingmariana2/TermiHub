<?php
// Set the response content type to JSON for API responses
header('Content-Type: application/json');

// Include the database configuration file to establish the PDO connection
require_once __DIR__ . '/../DATABASE/db_config.php';

// Get the HTTP request method (GET, POST, etc.) and the action parameter from the query string
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// Use a try-catch block to handle any exceptions that might occur during database operations
try {
    // Switch statement to handle different API actions based on the 'action' parameter
    switch ($action) {
        // Action to retrieve all data from the database (terminal info, companies, vehicles, routes, schedules)
        case 'get_all_data':
            // Query the database for terminal information (limited to 1 record)
            // Then fetch all companies, vehicles with their company names, routes, and schedules with route and vehicle details
            $data = [
                'terminal' => $pdo->query("SELECT * FROM terminal_info LIMIT 1")->fetch(),
                'empresas' => $pdo->query("SELECT * FROM empresas")->fetchAll(),
                'vehiculos' => $pdo->query("SELECT v.*, e.nombre as empresa_nombre FROM vehiculos v LEFT JOIN empresas e ON v.empresa_id = e.id")->fetchAll(),
                'rutas' => $pdo->query("SELECT * FROM rutas")->fetchAll(),
                'horarios' => $pdo->query("SELECT h.*, r.nombre as ruta_nombre, v.placa as vehiculo_placa FROM horarios h JOIN rutas r ON h.ruta_id = r.id JOIN vehiculos v ON h.vehiculo_id = v.id")->fetchAll()
            ];
            // Encode the data array as JSON and output it
            echo json_encode($data);
            break;

        // Action to retrieve all companies
        case 'get_empresas':
            // Query all records from the empresas table and return as JSON
            echo json_encode($pdo->query("SELECT * FROM empresas")->fetchAll());
            break;

        // Action to add a new company
        case 'add_empresa':
            // Only allow POST requests for adding data
            if ($method === 'POST') {
                // Decode the JSON input from the request body
                $input = json_decode(file_get_contents('php://input'), true);
                // Check if a company with the same name already exists
                $stmt = $pdo->prepare("SELECT id FROM empresas WHERE nombre = ?");
                $stmt->execute([$input['nombre']]);
                if ($stmt->fetch()) {
                    // If exists, return error message
                    echo json_encode(['success' => false, 'error' => 'La empresa ya existe']);
                    break;
                }
                // Insert the new company into the database
                $stmt = $pdo->prepare("INSERT INTO empresas (nombre, telefono, email) VALUES (?, ?, ?)");
                $stmt->execute([$input['nombre'], $input['telefono'], $input['email']]);
                // Return success with the new company's ID
                echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            }
            break;

        // Action to delete a company
        case 'delete_empresa':
            // Only allow POST requests for deletion
            if ($method === 'POST') {
                // Decode the JSON input
                $input = json_decode(file_get_contents('php://input'), true);
                // Delete the company by ID
                $stmt = $pdo->prepare("DELETE FROM empresas WHERE id = ?");
                $stmt->execute([$input['id']]);
                if ($stmt->rowCount() > 0) {
                    // If deletion successful, return success
                    echo json_encode(['success' => true]);
                } else {
                    // If no rows affected, return error (company not found or has associated records)
                    echo json_encode(['success' => false, 'error' => 'No se encontró la empresa o tiene registros asociados']);
                }
            }
            break;

        // Action to add a new vehicle
        case 'add_vehiculo':
            if ($method === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                // Check if vehicle with same plate already exists
                $stmt = $pdo->prepare("SELECT id FROM vehiculos WHERE placa = ?");
                $stmt->execute([$input['placa']]);
                if ($stmt->fetch()) {
                    echo json_encode(['success' => false, 'error' => 'El vehículo ya existe']);
                    break;
                }
                // Insert new vehicle
                $stmt = $pdo->prepare("INSERT INTO vehiculos (placa, tipo, capacidad, empresa_id) VALUES (?, ?, ?, ?)");
                $stmt->execute([$input['placa'], $input['tipo'], $input['capacidad'], $input['empresa_id']]);
                echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            }
            break;

        // Action to delete a vehicle
        case 'delete_vehiculo':
            if ($method === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                // Delete vehicle by ID
                $stmt = $pdo->prepare("DELETE FROM vehiculos WHERE id = ?");
                $stmt->execute([$input['id']]);
                if ($stmt->rowCount() > 0) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'No se encontró el vehículo o tiene horarios asociados']);
                }
            }
            break;

        // Action to add a new route
        case 'add_ruta':
            if ($method === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                // Check if route with same name already exists
                $stmt = $pdo->prepare("SELECT id FROM rutas WHERE nombre = ?");
                $stmt->execute([$input['nombre']]);
                if ($stmt->fetch()) {
                    echo json_encode(['success' => false, 'error' => 'La ruta ya existe']);
                    break;
                }
                // Insert new route
                $stmt = $pdo->prepare("INSERT INTO rutas (nombre, origen, destino, distancia, tarifa) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$input['nombre'], $input['origen'], $input['destino'], $input['distancia'], $input['tarifa']]);
                echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            }
            break;

        // Action to delete a route
        case 'delete_ruta':
            if ($method === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                // Delete route by ID
                $stmt = $pdo->prepare("DELETE FROM rutas WHERE id = ?");
                $stmt->execute([$input['id']]);
                if ($stmt->rowCount() > 0) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'No se encontró la ruta o tiene horarios asociados']);
                }
            }
            break;

        // Action to add a new schedule
        case 'add_horario':
            if ($method === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                // Check if schedule already exists for the same route, vehicle, date, and departure time
                $stmt = $pdo->prepare("SELECT id FROM horarios WHERE ruta_id = ? AND vehiculo_id = ? AND fecha = ? AND hora_salida = ?");
                $stmt->execute([$input['ruta_id'], $input['vehiculo_id'], $input['fecha'], $input['hora_salida']]);
                if ($stmt->fetch()) {
                    echo json_encode(['success' => false, 'error' => 'El horario ya existe']);
                    break;
                }
                // Insert new schedule
                $stmt = $pdo->prepare("INSERT INTO horarios (ruta_id, vehiculo_id, fecha, hora_salida, hora_llegada, cupos) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$input['ruta_id'], $input['vehiculo_id'], $input['fecha'], $input['hora_salida'], $input['hora_llegada'], $input['cupos']]);
                echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            }
            break;

        // Action to delete a schedule
        case 'delete_horario':
            if ($method === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                // Delete schedule by ID
                $stmt = $pdo->prepare("DELETE FROM horarios WHERE id = ?");
                $stmt->execute([$input['id']]);
                if ($stmt->rowCount() > 0) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'No se encontró el horario o tiene ventas asociadas']);
                }
            }
            break;

        // Action to process a purchase (buy tickets)
        case 'procesar_compra':
            if ($method === 'POST') {
                // Decode the array of purchase items
                $input = json_decode(file_get_contents('php://input'), true); // Array of items
                // Start a database transaction to ensure atomicity
                $pdo->beginTransaction();
                // Loop through each item in the purchase
                foreach ($input['items'] as $item) {
                    // Update the available seats by subtracting the quantity purchased
                    $stmt = $pdo->prepare("UPDATE horarios SET cupos = cupos - ? WHERE id = ? AND cupos >= ?");
                    $stmt->execute([$item['cantidad'], $item['horarioId'], $item['cantidad']]);
                    if ($stmt->rowCount() === 0) {
                        // If no rows updated, means not enough seats available
                        throw new Exception("No hay suficientes cupos para el viaje: " . $item['origen'] . " -> " . $item['destino']);
                    }
                    // Record the sale in the ventas table
                    $stmt = $pdo->prepare("INSERT INTO ventas (horario_id, cantidad, total) VALUES (?, ?, ?)");
                    $stmt->execute([$item['horarioId'], $item['cantidad'], $item['totalPrecio']]);
                }
                // Commit the transaction if all operations successful
                $pdo->commit();
                echo json_encode(['success' => true]);
            }
            break;

        // Default case for invalid actions
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} catch (Exception $e) {
    // If an exception occurs, rollback any pending transaction
    if ($pdo->inTransaction()) $pdo->rollBack();
    // Set HTTP response code to 500 (Internal Server Error)
    http_response_code(500);
    // Return the error message as JSON
    echo json_encode(['error' => $e->getMessage()]);
}
?>
