// ===== CONFIGURACIÓN API =====
const API_URL = 'api.php';

// Normaliza texto: quita tildes y pasa a minúsculas para comparaciones flexibles
function normalizeText(str) {
    return (str || '').toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '');
}

// ===== DATOS INICIALES =====
let dataStorage = {
    terminal: {},
    empresas: [],
    vehiculos: [],
    rutas: [],
    horarios: []
};

let carrito = [];

// Cargar datos desde la API
async function cargarDatos() {
    try {
        const response = await fetch(`${API_URL}?action=get_all_data`);
        if (!response.ok) throw new Error('Error en la respuesta del servidor');
        const data = await response.json();
        
        dataStorage.terminal = data.terminal || {};

        // Normalizar IDs a número para que las comparaciones === funcionen
        // (PHP retorna todos los campos como strings)
        dataStorage.empresas = (data.empresas || []).map(e => ({
            ...e,
            id: Number(e.id)
        }));
        dataStorage.vehiculos = (data.vehiculos || []).map(v => ({
            ...v,
            id: Number(v.id),
            empresa_id: Number(v.empresa_id),
            capacidad: Number(v.capacidad)
        }));
        dataStorage.rutas = (data.rutas || []).map(r => ({
            ...r,
            id: Number(r.id),
            distancia: Number(r.distancia),
            tarifa: Number(r.tarifa)
        }));
        dataStorage.horarios = (data.horarios || []).map(h => ({
            ...h,
            id: Number(h.id),
            ruta_id: Number(h.ruta_id),
            vehiculo_id: Number(h.vehiculo_id),
            cupos: Number(h.cupos)
        }));
        
        // El carrito sigue siendo local para la sesión actual
        const carritoGuardado = localStorage.getItem('carrito');
        if (carritoGuardado) {
            carrito = JSON.parse(carritoGuardado);
        }
        
        // Actualizar la sección visible actualmente
        const activeSection = document.querySelector('.section.active');
        if (activeSection) {
            const sectionId = activeSection.id;
            switch(sectionId) {
                case 'inicio': cargarInicio(); break;
                case 'empresas': cargarEmpresas(); break;
                case 'carrito': actualizarCarrito(); break;
                case 'admin': cargarAdmin(); break;
            }
        }
        
        actualizarContadorCarrito();
        cargarCiudades(); // Poblar listas de ciudades
    } catch (error) {
        console.error('Error al cargar datos:', error);
    }
}

// Guardar carrito en localStorage (persistencia de sesión)
function guardarCarrito() {
    localStorage.setItem('carrito', JSON.stringify(carrito));
}

// ===== NAVEGACIÓN =====
function showSection(sectionId) {
    // Ocultar todas las secciones
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => section.classList.remove('active'));

    // Mostrar sección activa
    document.getElementById(sectionId).classList.add('active');

    // Cargar contenido según sección
    switch(sectionId) {
        case 'inicio':
            cargarInicio();
            break;
        case 'empresas':
            cargarEmpresas();
            break;
        case 'carrito':
            actualizarCarrito();
            break;
        case 'admin':
            cargarAdmin();
            break;
    }

    // Scroll hacia arriba
    window.scrollTo(0, 0);
}

// ===== SECCIÓN: INICIO =====
function cargarInicio() {
    // Terminal info
    const infoTerminal = document.getElementById('infoTerminal');
    infoTerminal.innerHTML = `
        <strong>${dataStorage.terminal.nombre}</strong><br>
        📍 ${dataStorage.terminal.ubicacion}<br>
        📞 ${dataStorage.terminal.telefono}
    `;

    // Conteos
    document.getElementById('empresasCount').textContent = `${dataStorage.empresas.length} empresas`;
    document.getElementById('vehiculosCount').textContent = `${dataStorage.vehiculos.length} vehículos`;
    document.getElementById('rutasCount').textContent = `${dataStorage.rutas.length} rutas activas`;

    // Listener para filtrar destinos dinámicamente
    const inputOrigen = document.getElementById('origen');
    if (inputOrigen) {
        inputOrigen.addEventListener('input', function() {
            actualizarDestinos(this.value);
        });
    }
}

function cargarCiudades() {
    const datalistOrigen = document.getElementById('ciudades-origen');
    const datalistDestino = document.getElementById('ciudades-destino');
    
    if (!datalistOrigen || !datalistDestino) return;

    // Obtener ciudades únicas de las rutas
    const ciudades = [...new Set([
        ...dataStorage.rutas.map(r => r.origen),
        ...dataStorage.rutas.map(r => r.destino)
    ])].sort();

    const optionsHTML = ciudades.map(c => `<option value="${c}">`).join('');
    
    datalistOrigen.innerHTML = optionsHTML;
    datalistDestino.innerHTML = optionsHTML;
}

function actualizarDestinos(valorOrigen) {
    const datalistDestino = document.getElementById('ciudades-destino');
    if (!datalistDestino) return;

    // Obtener todas las ciudades
    const ciudades = [...new Set([
        ...dataStorage.rutas.map(r => r.origen),
        ...dataStorage.rutas.map(r => r.destino)
    ])].sort();

    // Filtrar la ciudad que está en el origen
    const ciudadesFiltradas = ciudades.filter(c => normalizeText(c) !== normalizeText(valorOrigen));

    datalistDestino.innerHTML = ciudadesFiltradas.map(c => `<option value="${c}">`).join('');
}

// ===== SECCIÓN: EMPRESAS =====
function cargarEmpresas() {
    const contenedor = document.getElementById('empresasContainer');
    contenedor.innerHTML = dataStorage.empresas.map(empresa => {
        // Lógica para obtener la inicial del "segundo nombre"
        let inicial = empresa.nombre.charAt(0).toUpperCase();
        
        // Si tiene espacio, tomar la inicial de la segunda palabra
        const partes = empresa.nombre.split(' ');
        if (partes.length > 1 && partes[1].length > 0) {
            inicial = partes[1].charAt(0).toUpperCase();
        } else {
            // Si no hay espacio, buscar la primera mayúscula después del inicio (CamelCase)
            for (let i = 1; i < empresa.nombre.length; i++) {
                const char = empresa.nombre[i];
                if (char === char.toUpperCase() && char !== char.toLowerCase()) {
                    inicial = char;
                    break;
                }
            }
        }

        return `
            <div class="empresa-card">
                <div class="empresa-icon">
                    <div class="empresa-logo-placeholder">
                        ${inicial}
                    </div>
                </div>
                <h3>${empresa.nombre}</h3>
                <div class="empresa-info">
                    <p>📞 ${empresa.telefono}</p>
                    <p>✉️ ${empresa.email}</p>
                </div>
            </div>
        `;
    }).join('');
}

// ===== BÚSQUEDA DE VIAJES =====
function buscarViajes(event) {
    event.preventDefault();

    const origen = document.getElementById('origen').value.trim();
    const destino = document.getElementById('destino').value.trim();
    const fecha = document.getElementById('fecha').value;
    const pasajeros = parseInt(document.getElementById('pasajeros').value);
    
    if (normalizeText(origen) === normalizeText(destino)) {
        mostrarErrorBusqueda(`❌ El origen y el destino no pueden ser la misma ciudad.`);
        return;
    }

    // 1. Verificar si existe la ruta (trayecto)
    const rutasEncontradas = dataStorage.rutas.filter(r => 
        normalizeText(r.origen) === normalizeText(origen) && 
        normalizeText(r.destino) === normalizeText(destino)
    );

    if (rutasEncontradas.length === 0) {
        mostrarErrorBusqueda(`❌ No hay ruta disponible para el trayecto entre <strong>${origen}</strong> y <strong>${destino}</strong>.`);
        return;
    }

    // 2. Verificar si hay horarios para esa FECHA
    const horariosFecha = dataStorage.horarios.filter(h => 
        rutasEncontradas.some(r => r.id === h.ruta_id) && h.fecha === fecha
    );

    if (horariosFecha.length === 0) {
        mostrarErrorBusqueda(`❌ No hay horarios disponibles para el día <strong>${fecha}</strong>. Intente en otra fecha.`);
        return;
    }

    // 3. Verificar si hay CUPOS suficientes
    const horariosConCupo = horariosFecha.filter(h => h.cupos >= pasajeros);

    if (horariosConCupo.length === 0) {
        // Buscar el máximo de cupos disponibles para dar mejor información
        const maxCupos = Math.max(...horariosFecha.map(h => h.cupos));
        mostrarErrorBusqueda(`❌ No hay suficientes cupos. Solo quedan un máximo de <strong>${maxCupos}</strong> cupos para esta fecha.`);
        return;
    }

    // Si todo salió bien, mostrar resultados
    dibujarResultadosBusqueda(horariosConCupo, pasajeros);
}

function mostrarErrorBusqueda(mensaje) {
    document.getElementById('resultados').innerHTML = `
        <div class="carrito-vacio">
            <p>${mensaje}</p>
        </div>
    `;
    document.getElementById('resultados').scrollIntoView({ behavior: 'smooth' });
}

function dibujarResultadosBusqueda(horarios, cantidadPasajeros) {
    const contenedor = document.getElementById('resultados');
    contenedor.innerHTML = '';

    horarios.forEach(horario => {
        const ruta = dataStorage.rutas.find(r => r.id === horario.ruta_id);
        const vehiculo = dataStorage.vehiculos.find(v => v.id === horario.vehiculo_id);
        const total = ruta.tarifa * cantidadPasajeros;

        contenedor.innerHTML += `
            <div class="viaje-card">
                <div class="viaje-info">
                    <div class="viaje-header">
                        <span class="badget badget-primary">${vehiculo.tipo}</span>
                        <span class="viaje-price">$${Number(ruta.tarifa).toLocaleString('es-CO')} c/u</span>
                    </div>
                    <h4>${ruta.origen} → ${ruta.destino}</h4>
                    <p>🕒 Sale: ${horario.hora_salida} | Llega: ${horario.hora_llegada}</p>
                    <p>📅 Fecha: ${horario.fecha}</p>
                    <p>🚌 Placa: ${vehiculo.placa} | Disponibles: ${horario.cupos} cupos</p>
                </div>
                <div class="viaje-footer">
                    <div class="total-info">
                        <strong>Total (${cantidadPasajeros} pas):</strong>
                        <span class="total-precio">$${total.toLocaleString('es-CO')}</span>
                    </div>
                    <button class="btn btn-secondary" onclick="agregarAlCarrito(${horario.id}, ${cantidadPasajeros})">
                        Comprar Tiquetes
                    </button>
                </div>
            </div>
        `;
    });
    
    document.getElementById('resultados').scrollIntoView({ behavior: 'smooth' });
}

// ===== CARRITO =====
function agregarAlCarrito(horarioId, cantidad) {
    const horario = dataStorage.horarios.find(h => h.id === horarioId);
    if (!horario) return;

    const ruta = dataStorage.rutas.find(r => r.id === horario.ruta_id);
    const precioUnitario = Number(ruta.tarifa);

    const itemExistente = carrito.find(item => item.horarioId === horarioId);

    if (itemExistente) {
        // Verificar si hay cupo suficiente para agregar más
        if (horario.cupos < itemExistente.cantidad + cantidad) {
            alert(`❌ Solo quedan ${horario.cupos} cupos totales para este viaje. Ya tienes ${itemExistente.cantidad} en el carrito.`);
            return;
        }
        itemExistente.cantidad += cantidad;
        itemExistente.totalPrecio = precioUnitario * itemExistente.cantidad;
    } else {
        carrito.push({
            id: Date.now(), // ID único para el item del carrito
            horarioId: horarioId,
            origen: ruta.origen,
            destino: ruta.destino,
            salida: horario.hora_salida,
            llegada: horario.hora_llegada,
            precioUnitario: precioUnitario,
            cantidad: cantidad,
            totalPrecio: precioUnitario * cantidad
        });
    }

    guardarCarrito();
    actualizarContadorCarrito();
    alert(`✓ Se agregaron ${cantidad} tiquete(s) al carrito exitosamente.`);
}

function actualizarContadorCarrito() {
    const cantidad = carrito.reduce((suma, item) => suma + item.cantidad, 0);
    document.getElementById('cartCount').textContent = cantidad;
}

function actualizarCarrito() {
    const contenedor = document.getElementById('carritoItems');

    if (carrito.length === 0) {
        contenedor.innerHTML = '<div class="carrito-vacio"><p>🛒 Tu carrito está vacío</p></div>';
        actualizarResumen();
        return;
    }

    contenedor.innerHTML = carrito.map(item => `
        <div class="carrito-item">
            <div class="item-info">
                <h4>${item.origen} → ${item.destino}</h4>
                <div class="item-detalle">Salida: ${item.salida} | Llegada: ${item.llegada}</div>
                <div class="item-detalle">Precio por tiquete: $${Number(item.precioUnitario).toLocaleString('es-CO')}</div>
            </div>
            <div class="item-contador">
                <button onclick="cambiarCantidad(${item.id}, -1)">-</button>
                <span>${item.cantidad} tiquetes</span>
                <button onclick="cambiarCantidad(${item.id}, 1)">+</button>
            </div>
            <div class="item-precio">
                $${Number(item.totalPrecio).toLocaleString('es-CO')}
            </div>
            <button class="btn btn-danger btn-sm" onclick="eliminarDelCarrito(${item.id})">Eliminar</button>
        </div>
    `).join('');

    actualizarResumen();
}

function cambiarCantidad(itemId, cambio) {
    const item = carrito.find(i => i.id === itemId);
    if (item) {
        item.cantidad += cambio;
        if (item.cantidad <= 0) {
            eliminarDelCarrito(itemId);
        } else {
            item.totalPrecio = item.precioUnitario * item.cantidad;
            guardarCarrito();
            actualizarCarrito();
        }
    }
}

function eliminarDelCarrito(itemId) {
    carrito = carrito.filter(item => item.id !== itemId);
    guardarCarrito();
    actualizarCarrito();
}

function vaciarCarrito() {
    carrito = [];
    guardarCarrito();
    actualizarCarrito();
}

function actualizarResumen() {
    const subtotal = carrito.reduce((suma, item) => suma + item.totalPrecio, 0);
    const impuestos = subtotal * 0.08;
    const total = subtotal + impuestos;

    document.getElementById('subtotal').textContent = `$${Number(subtotal).toLocaleString('es-CO')}`;
    document.getElementById('impuestos').textContent = `$${Number(impuestos).toLocaleString('es-CO')}`;
    document.getElementById('total').textContent = `$${Number(total).toLocaleString('es-CO')}`;
}

async function procesarCompra() {
    if (carrito.length === 0) {
        alert('❌ El carrito está vacío');
        return;
    }

    try {
        const response = await fetch(`${API_URL}?action=procesar_compra`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ items: carrito })
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(`✓ Compra realizada exitosamente!`);
            carrito = [];
            guardarCarrito();
            actualizarCarrito();
            await cargarDatos(); // Recargar datos para ver cupos actualizados
            showSection('inicio');
        } else {
            alert('❌ Error: ' + result.error);
        }
    } catch (error) {
        console.error('Error al procesar compra:', error);
        alert('❌ Error de conexión al servidor');
    }
}

// ===== PANEL ADMINISTRACIÓN =====
function cargarAdmin() {
    actualizarListaEmpresas();
    actualizarListaVehiculos();
    actualizarListaRutas();
    actualizarListaHorarios();
    cargarSelectsAdmin();
}

function cambiarAdminTab(tabId) {
    // Ocultar todos los tabs
    const tabs = document.querySelectorAll('.admin-tab');
    tabs.forEach(tab => tab.classList.remove('active'));

    // Ocultar botones activos
    const botones = document.querySelectorAll('.tab-btn');
    botones.forEach(btn => btn.classList.remove('active'));

    // Mostrar tab activo y botón
    document.getElementById(tabId).classList.add('active');
    event.target.classList.add('active');
}

// --- EMPRESAS ADMIN ---
async function agregarEmpresa(event) {
    event.preventDefault();

    const nombre = document.getElementById('nombreEmpresa').value;
    const telefono = document.getElementById('telefonoEmpresa').value;
    const email = document.getElementById('emailEmpresa').value;

    try {
        const response = await fetch(`${API_URL}?action=add_empresa`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nombre, telefono, email })
        });
        
        const result = await response.json();
        if (result.success) {
            await cargarDatos();
            document.querySelector('#empresas-admin form').reset();
            alert('✓ Empresa agregada exitosamente');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function eliminarEmpresa(id) {
    if (!confirm('¿Está seguro de eliminar esta empresa?')) return;
    try {
        const response = await fetch(`${API_URL}?action=delete_empresa`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        const result = await response.json();
        if (result.success) {
            await cargarDatos();
            alert('✓ Empresa eliminada');
        } else {
            alert('❌ Error: ' + (result.error || 'No se pudo eliminar'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Error al conectar con el servidor');
    }
}

function actualizarListaEmpresas() {
    const lista = document.getElementById('listaEmpresas');
    lista.innerHTML = dataStorage.empresas.map(empresa => `
        <div class="admin-item">
            <div class="admin-item-info">
                <p><strong>${empresa.nombre}</strong></p>
                <p>📞 ${empresa.telefono}</p>
                <p>✉️ ${empresa.email}</p>
            </div>
            <div class="admin-acciones">
                <button class="btn btn-danger btn-sm" onclick="eliminarEmpresa(${empresa.id})">Eliminar</button>
            </div>
        </div>
    `).join('');
}

// --- VEHÍCULOS ADMIN ---
async function agregarVehiculo(event) {
    event.preventDefault();

    const placa = document.getElementById('placaVehiculo').value;
    const tipo = document.getElementById('tipoVehiculo').value;
    const capacidad = parseInt(document.getElementById('capacidadVehiculo').value);
    const empresa_id = parseInt(document.getElementById('empresaVehiculo').value);

    try {
        const response = await fetch(`${API_URL}?action=add_vehiculo`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ placa, tipo, capacidad, empresa_id })
        });
        const result = await response.json();
        if (result.success) {
            await cargarDatos();
            document.querySelector('#vehiculos-admin form').reset();
            alert('✓ Vehículo agregado exitosamente');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function eliminarVehiculo(id) {
    if (!confirm('¿Está seguro de eliminar este vehículo?')) return;
    try {
        const response = await fetch(`${API_URL}?action=delete_vehiculo`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        const result = await response.json();
        if (result.success) {
            await cargarDatos();
            alert('✓ Vehículo eliminado');
        } else {
            alert('❌ Error: ' + (result.error || 'No se pudo eliminar'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Error al conectar con el servidor');
    }
}

function actualizarListaVehiculos() {
    const lista = document.getElementById('listaVehiculos');
    lista.innerHTML = dataStorage.vehiculos.map(vehiculo => {
        const empresa = dataStorage.empresas.find(e => e.id == vehiculo.empresa_id);
        const nombreEmpresa = empresa ? empresa.nombre : 'S/N';
        return `
            <div class="admin-item">
                <div class="admin-item-info">
                    <p><strong>${vehiculo.placa}</strong> - ${vehiculo.tipo}</p>
                    <p>Capacidad: ${vehiculo.capacidad} pasajeros</p>
                    <p>Empresa: ${nombreEmpresa}</p>
                </div>
                <div class="admin-acciones">
                    <button class="btn btn-danger btn-sm" onclick="eliminarVehiculo(${vehiculo.id})">Eliminar</button>
                </div>
            </div>
        `;
    }).join('');
}

// --- RUTAS ADMIN ---
async function agregarRuta(event) {
    event.preventDefault();

    const nombre = document.getElementById('nombreRuta').value;
    const origen = document.getElementById('origenRuta').value;
    const destino = document.getElementById('destinoRuta').value;
    const distancia = parseInt(document.getElementById('distanciaRuta').value);
    const tarifa = parseInt(document.getElementById('tarifa').value);

    try {
        const response = await fetch(`${API_URL}?action=add_ruta`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nombre, origen, destino, distancia, tarifa })
        });
        const result = await response.json();
        if (result.success) {
            await cargarDatos();
            document.querySelector('#rutas-admin form').reset();
            alert('✓ Ruta agregada exitosamente');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function eliminarRuta(id) {
    if (!confirm('¿Está seguro de eliminar esta ruta?')) return;
    try {
        const response = await fetch(`${API_URL}?action=delete_ruta`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        const result = await response.json();
        if (result.success) {
            await cargarDatos();
            alert('✓ Ruta eliminada');
        } else {
            alert('❌ Error: ' + (result.error || 'No se pudo eliminar'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Error al conectar con el servidor');
    }
}

function actualizarListaRutas() {
    const lista = document.getElementById('listaRutas');
    lista.innerHTML = dataStorage.rutas.map(ruta => `
        <div class="admin-item">
            <div class="admin-item-info">
                <p><strong>${ruta.nombre}</strong></p>
                <p>${ruta.origen} → ${ruta.destino}</p>
                <p>Distancia: ${ruta.distancia} km | Tarifa: $${Number(ruta.tarifa).toLocaleString('es-CO')}</p>
            </div>
            <div class="admin-acciones">
                <button class="btn btn-danger btn-sm" onclick="eliminarRuta(${ruta.id})">Eliminar</button>
            </div>
        </div>
    `).join('');
}

// --- HORARIOS ADMIN ---
async function agregarHorario(event) {
    event.preventDefault();

    const ruta_id = parseInt(document.getElementById('rutaHorario').value);
    const vehiculo_id = parseInt(document.getElementById('vehiculoHorario').value);
    const fecha = document.getElementById('fechaHorario').value;
    const hora_salida = document.getElementById('horaSalida').value;
    const hora_llegada = document.getElementById('horaLlegada').value;
    const cupos = parseInt(document.getElementById('cuposDisponibles').value);

    try {
        const response = await fetch(`${API_URL}?action=add_horario`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ruta_id, vehiculo_id, fecha, hora_salida, hora_llegada, cupos })
        });
        const result = await response.json();
        if (result.success) {
            await cargarDatos();
            document.querySelector('#horarios-admin form').reset();
            alert('✓ Horario agregado exitosamente');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function eliminarHorario(id) {
    if (!confirm('¿Está seguro de eliminar este horario?')) return;
    try {
        const response = await fetch(`${API_URL}?action=delete_horario`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        const result = await response.json();
        if (result.success) {
            await cargarDatos();
            alert('✓ Horario eliminado');
        } else {
            alert('❌ Error: ' + (result.error || 'No se pudo eliminar'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Error al conectar con el servidor');
    }
}

function actualizarListaHorarios() {
    const lista = document.getElementById('listaHorarios');
    lista.innerHTML = dataStorage.horarios.map(horario => {
        const ruta = dataStorage.rutas.find(r => r.id == horario.ruta_id);
        const vehiculo = dataStorage.vehiculos.find(v => v.id == horario.vehiculo_id);
        const nombreRuta = ruta ? ruta.nombre : 'S/N';
        const placaVehiculo = vehiculo ? vehiculo.placa : 'S/N';
        return `
            <div class="admin-item">
                <div class="admin-item-info">
                    <p><strong>${nombreRuta}</strong> - ${placaVehiculo}</p>
                    <p>Fecha: ${horario.fecha} | ${horario.hora_salida} - ${horario.hora_llegada}</p>
                    <p>Cupos disponibles: ${horario.cupos}</p>
                </div>
                <div class="admin-acciones">
                    <button class="btn btn-danger btn-sm" onclick="eliminarHorario(${horario.id})">Eliminar</button>
                </div>
            </div>
        `;
    }).join('');
}

// --- CARGAR SELECTS ADMIN ---
function cargarSelectsAdmin() {
    // Empresas en vehículos
    const selectEmpresa = document.getElementById('empresaVehiculo');
    selectEmpresa.innerHTML = '<option value="">Seleccionar empresa</option>' + 
        dataStorage.empresas.map(e => `<option value="${e.id}">${e.nombre}</option>`).join('');

    // Rutas en horarios
    const selectRuta = document.getElementById('rutaHorario');
    selectRuta.innerHTML = '<option value="">Seleccionar ruta</option>' +
        dataStorage.rutas.map(r => `<option value="${r.id}">${r.nombre} (${r.origen} → ${r.destino})</option>`).join('');

    // Vehículos en horarios
    const selectVehiculo = document.getElementById('vehiculoHorario');
    selectVehiculo.innerHTML = '<option value="">Seleccionar vehículo</option>' +
        dataStorage.vehiculos.map(v => `<option value="${v.id}">${v.placa} - ${v.tipo}</option>`).join('');
}

// ===== INICIALIZACIÓN =====
document.addEventListener('DOMContentLoaded', function() {
    cargarDatos();
    showSection('inicio');
    actualizarContadorCarrito();

    // Establecer fecha mínima en input
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fecha').setAttribute('min', hoy);
    document.getElementById('fechaHorario').setAttribute('min', hoy);
});
