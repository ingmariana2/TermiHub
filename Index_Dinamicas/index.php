<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal de Transporte - Gestión de Servicios</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- HEADER / NAVEGACIÓN -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <div class="logo-icon">
                    <img src="img/logo.svg" alt="TermiHub logo">
                </div>
                <h1>TermiHub</h1>
            </div>
            <nav class="navigation">
                <button class="nav-btn" onclick="showSection('inicio')">Inicio</button>
                <button class="nav-btn" onclick="showSection('empresas')">Empresas</button>
                <button class="nav-btn" onclick="showSection('busqueda')">Buscar Viajes</button>
                <button class="nav-btn" onclick="showSection('carrito')">
                    Carrito <span class="cart-count" id="cartCount">0</span>
                </button>
                <button class="nav-btn admin-btn" onclick="showSection('admin')">Admin</button>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <!-- SECCIÓN: INICIO -->
        <section id="inicio" class="section active">
            <div class="container">
                <div class="hero">
                    <div class="hero-content">
                        <h2>Bienvenido a TermiHub</h2>
                        <p>Tu plataforma de confianza para gestionar tus viajes con facilidad y seguridad.</p>
                        <ul class="hero-features">
                            <li>Reservas en línea</li>
                            <li>Variedad de rutas y vehículos</li>
                            <li>Precios transparentes</li>
                        </ul>
                        <button class="btn btn-primary" onclick="showSection('busqueda')">Buscar Viajes</button>
                    </div>
                    <div class="hero-image">
                        <!-- icono de logo dentro de círculo azul -->
                        <svg class="hero-bus" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80" width="250" height="250">
                            <circle cx="40" cy="40" r="38" fill="var(--color-primary)" />
                            <path d="M28 22h24c2.21 0 4 1.79 4 4v20c0 2.21-1.79 4-4 4H28c-2.21 0-4-1.79-4-4V26c0-2.21 1.79-4 4-4zm0 4v2h24v-2H28zm24 24v2H28v-2h24z" fill="var(--color-light)" />
                            <circle cx="32" cy="50" r="3" fill="var(--color-light)" />
                            <circle cx="48" cy="50" r="3" fill="var(--color-light)" />
                        </svg>
                    </div>
                </div>

                <div class="info-cards">
                    <div class="card">
                        <div class="card-icon">
                            <img src="img/icon-terminal.svg" alt="Icono terminal">
                        </div>
                        <h3>Información Terminal</h3>
                        <p id="infoTerminal">Cargando información...</p>
                    </div>
                    <div class="card">
                        <div class="card-icon">
                            <img src="img/icon-empresas.svg" alt="Icono empresas">
                        </div>
                        <h3>Empresas Vinculadas</h3>
                        <p id="empresasCount">Cargando...</p>
                    </div>
                    <div class="card">
                        <div class="card-icon">
                            <img src="img/icon-vehiculos.svg" alt="Icono vehículos">
                        </div>
                        <h3>Vehículos Disponibles</h3>
                        <p id="vehiculosCount">Cargando...</p>
                    </div>
                    <div class="card">
                        <div class="card-icon">
                            <img src="img/icon-rutas.svg" alt="Icono rutas">
                        </div>
                        <h3>Rutas Activas</h3>
                        <p id="rutasCount">Cargando...</p>
                    </div>
                </div>
                <!-- logos adicionales eliminados por petición del usuario -->
            </div>
        </section>

        <!-- SECCIÓN: EMPRESAS -->
        <section id="empresas" class="section">
            <div class="container">
                <h2>Empresas de Transporte Vinculadas</h2>
                <div class="empresas-grid" id="empresasContainer">
                    <!-- Cargado por JavaScript -->
                </div>
            </div>
        </section>

        <!-- SECCIÓN: BÚSQUEDA DE VIAJES -->
        <section id="busqueda" class="section">
            <div class="container">
                <h2>Buscar Viajes</h2>
                <form class="search-form" onsubmit="buscarViajes(event)">
                    <div class="input-group">
                        <label for="origen">Origen</label>
                        <input type="text" id="origen" list="ciudades-origen" placeholder="¿De dónde sales?" required>
                    </div>
                    <div class="input-group">
                        <label for="destino">Destino</label>
                        <input type="text" id="destino" list="ciudades-destino" placeholder="¿A dónde vas?" required>
                    </div>
                    <datalist id="ciudades-origen">
                        <!-- Cargado por JS -->
                    </datalist>
                    <datalist id="ciudades-destino">
                        <!-- Cargado por JS -->
                    </datalist>
                    <div class="form-group">
                        <label for="fecha">Fecha:</label>
                        <input type="date" id="fecha" required>
                    </div>
                    <div class="form-group">
                        <label for="pasajeros">Pasajeros:</label>
                        <input type="number" id="pasajeros" min="1" max="10" value="1">
                    </div>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </form>

                <div id="resultados" class="resultados-container">
                    <!-- Cargado por JavaScript -->
                </div>
            </div>
        </section>

        <!-- SECCIÓN: CARRITO -->
        <section id="carrito" class="section">
            <div class="container">
                <h2>Mi Carrito de Compra</h2>
                <div class="carrito-content">
                    <div id="carritoItems" class="carrito-items">
                        <!-- Cargado por JavaScript -->
                    </div>
                    <div class="carrito-resumen">
                        <div class="resumen-box">
                            <h3>Resumen</h3>
                            <div class="resumen-item">
                                <span>Subtotal:</span>
                                <span id="subtotal">$0</span>
                            </div>
                            <div class="resumen-item">
                                <span>Impuestos (8%):</span>
                                <span id="impuestos">$0</span>
                            </div>
                            <div class="resumen-item total">
                                <span>Total:</span>
                                <span id="total">$0</span>
                            </div>
                            <button class="btn btn-primary btn-full" onclick="procesarCompra()">Comprar Tiquetes</button>
                            <button class="btn btn-secondary btn-full" onclick="vaciarCarrito()">Vaciar Carrito</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECCIÓN: ADMINISTRACIÓN -->
        <section id="admin" class="section">
            <div class="container">
                <h2>Panel de Administración</h2>
                
                <div class="admin-tabs">
                    <button class="tab-btn active" onclick="cambiarAdminTab('empresas-admin')">Empresas</button>
                    <button class="tab-btn" onclick="cambiarAdminTab('vehiculos-admin')">Vehículos</button>
                    <button class="tab-btn" onclick="cambiarAdminTab('rutas-admin')">Rutas</button>
                    <button class="tab-btn" onclick="cambiarAdminTab('horarios-admin')">Horarios</button>
                </div>

                <!-- TAB: GESTIONAR EMPRESAS -->
                <div id="empresas-admin" class="admin-tab active">
                    <h3>Gestionar Empresas</h3>
                    <form onsubmit="agregarEmpresa(event)">
                        <input type="text" id="nombreEmpresa" placeholder="Nombre de empresa" required>
                        <input type="text" id="telefonoEmpresa" placeholder="Teléfono">
                        <input type="email" id="emailEmpresa" placeholder="Email">
                        <button type="submit" class="btn btn-primary">Agregar Empresa</button>
                    </form>
                    <div id="listaEmpresas" class="admin-list">
                        <!-- Cargado por JavaScript -->
                    </div>
                </div>

                <!-- TAB: GESTIONAR VEHÍCULOS -->
                <div id="vehiculos-admin" class="admin-tab">
                    <h3>Gestionar Vehículos</h3>
                    <form onsubmit="agregarVehiculo(event)">
                        <input type="text" id="placaVehiculo" placeholder="Placa" required>
                        <select id="tipoVehiculo" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="Bus">Bus</option>
                            <option value="Buseta">Buseta</option>
                            <option value="Gacela">Gacela</option>
                            <option value="Camión">Camión</option>
                            <option value="Aerovan">Aerovan</option>
                            <option value="Taxi">Taxi</option>
                        </select>
                        <input type="number" id="capacidadVehiculo" placeholder="Capacidad de pasajeros" required>
                        <select id="empresaVehiculo" required>
                            <option value="">Seleccionar empresa</option>
                            <!-- Cargado por JavaScript -->
                        </select>
                        <button type="submit" class="btn btn-primary">Agregar Vehículo</button>
                    </form>
                    <div id="listaVehiculos" class="admin-list">
                        <!-- Cargado por JavaScript -->
                    </div>
                </div>

                <!-- TAB: GESTIONAR RUTAS -->
                <div id="rutas-admin" class="admin-tab">
                    <h3>Gestionar Rutas</h3>
                    <form onsubmit="agregarRuta(event)">
                        <input type="text" id="nombreRuta" placeholder="Nombre de ruta" required>
                        <input type="text" id="origenRuta" placeholder="Ciudad origen" required>
                        <input type="text" id="destinoRuta" placeholder="Ciudad destino" required>
                        <input type="number" id="distanciaRuta" placeholder="Distancia (km)" required>
                        <input type="number" id="tarifa" placeholder="Tarifa base ($)" required>
                        <button type="submit" class="btn btn-primary">Agregar Ruta</button>
                    </form>
                    <div id="listaRutas" class="admin-list">
                        <!-- Cargado por JavaScript -->
                    </div>
                </div>

                <!-- TAB: GESTIONAR HORARIOS -->
                <div id="horarios-admin" class="admin-tab">
                    <h3>Gestionar Horarios</h3>
                    <form onsubmit="agregarHorario(event)">
                        <select id="rutaHorario" required>
                            <option value="">Seleccionar ruta</option>
                            <!-- Cargado por JavaScript -->
                        </select>
                        <select id="vehiculoHorario" required>
                            <option value="">Seleccionar vehículo</option>
                            <!-- Cargado por JavaScript -->
                        </select>
                        <input type="date" id="fechaHorario" required>
                        <input type="time" id="horaSalida" required>
                        <input type="time" id="horaLlegada" required>
                        <input type="number" id="cuposDisponibles" placeholder="Cupos disponibles" required>
                        <button type="submit" class="btn btn-primary">Agregar Horario</button>
                    </form>
                    <div id="listaHorarios" class="admin-list">
                        <!-- Cargado por JavaScript -->
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Terminal de Transporte TermiHub. Todos los derechos reservados.</p>
            <p>Contacto: info@termihub.com | Teléfono: (1) 555-1234</p>
        </div>
    </footer>

    <script src="js/script.js?v=<?php echo time(); ?>"></script>
</body>
</html>
