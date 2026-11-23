# 🚌 TermiHub - Sistema de Gestión de Terminal de Transporte

Plataforma web moderna, intuitiva y responsive para la gestión de servicios de una terminal de transporte terrestre de pasajeros. Desarrollada para la asignatura **Desarrollo de Aplicaciones para la Web** (UNAD).

---

## 📋 Descripción del Proyecto

**TermiHub** centraliza y dinamiza la operación de una terminal de transporte, permitiendo a los usuarios consultar y adquirir tiquetes de viaje de diversas empresas asociadas, y a los administradores gestionar toda la flota, empresas, rutas y horarios disponibles.

---

## 🚀 Funcionalidades Principales

### 👤 Para Usuarios
- **Portal Informativo**: Datos institucionales, ubicación y canales de contacto de la terminal.
- **Directorio de Empresas**: Listado completo de empresas de transporte asociadas y su información.
- **Búsqueda Avanzada de Viajes**: Filtro en tiempo real por origen, destino, fecha y número de pasajeros.
- **Carrito de Compras**: Selección de viajes, cálculo dinámico de subtotales, impuestos (8%) y costos totales.
- **Confirmación y Compra de Tiquetes**: Proceso guiado de adquisición de pasajes.

### 🛠️ Para Administración
- **Gestión de Empresas**: Registro, visualización y control de operadores de transporte.
- **Gestión de Flota Vehicular**: Registro por tipo (Bus, Buseta, Gacela, Camión, Aerovan, Taxi).
- **Gestión de Rutas y Tarifas**: Parametrización de orígenes, destinos, distancias y precios base.
- **Programación de Horarios**: Asignación de frecuencias, salidas y disponibilidad.

---

## 📂 Estructura del Repositorio

```text
TermiHub/
├── CSS/                  # Estilos visuales del proyecto
├── DATABASE/             # Scripts SQL y configuración de conexión MySQL/MariaDB
│   ├── db_config.php     # Configuración de acceso PDO
│   ├── setup_db.sql      # Estructura inicial de tablas y datos semilla
│   └── *.php             # Scripts de migración, diagnóstico y reseteo
├── Documentacion/        # Manuales y guías del proyecto
├── Imagenes/             # Recursos gráficos e isotipos vectoriales (SVG)
├── Index_Dinamicas/      # Versión dinámica en PHP
│   ├── index.php         # Interfaz principal dinámica
│   ├── api.php           # Endpoint de backend y persistencia
│   ├── css/              # Estilos específicos de la interfaz dinámica
│   ├── img/              # Recursos gráficos para la versión dinámica
│   └── js/               # Lógica del cliente interactiva
├── JavaScript/           # Scripts y lógica del frontend
├── documentacion.pdf     # Documento de entrega y sustentación académica
└── README.md             # Documentación principal del repositorio
```

---

## 💻 Tecnologías Utilizadas

- **Frontend**: HTML5 Semántico, CSS3 (Variables, Flexbox, Grid, diseño 100% Responsive), JavaScript (Vanilla ES6+).
- **Backend**: PHP.
- **Base de Datos**: MySQL / MariaDB (acceso vía PDO).
- **Herramientas de despliegue local**: Compatible con XAMPP / WampServer / LAMP.

---

## ⚙️ Puesta en Marcha Local

1. **Requisitos**:
   - Servidor web Apache con soporte PHP 7.4+ u 8.x (ej. XAMPP).
   - Servidor de base de datos MySQL / MariaDB.

2. **Configuración de Base de Datos**:
   - Inicia los servicios de Apache y MySQL en tu panel de XAMPP.
   - Crea una base de datos llamada `termihub_db` o importa el archivo `DATABASE/setup_db.sql` desde phpMyAdmin.
   - Verifica los parámetros de conexión en `DATABASE/db_config.php` (por defecto `host: localhost`, `user: root`, `pass: ""` y `port: 3306`).

3. **Ejecución**:
   - Coloca la carpeta del proyecto dentro del directorio `htdocs` de XAMPP.
   - Accede desde el navegador a: `http://localhost/TermiHub/Index_Dinamicas/`

---

## 👩‍💻 Autoría y Créditos
- **Proyecto Final**: Desarrollo de Aplicaciones para la Web
- **Universidad Nacional Abierta y a Distancia (UNAD)**
