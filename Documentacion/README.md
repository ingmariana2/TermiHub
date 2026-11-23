# 🚌 TermiHub - Terminal de Transporte

Una página web moderna, minimalista y completamente funcional para la gestión de servicios de una terminal de transporte.

## Características

### Para Usuarios
- **Información Institucional**: Datos de la terminal, ubicación, contacto
- **Listado de Empresas**: Directorio completo de empresas vinculadas
- **Búsqueda de Viajes**: Busca por origen, destino, fecha y cantidad de pasajeros
- **Carrito de Compra**: Añade tiquetes, modifica cantidades, calcula totales
- **Cálculo de Costos**: Precios automáticos incluyendo impuestos (8%)
- **Compra de Tiquetes**: Proceso simple y seguro

### Para Administración
- **Gestión de Empresas**: Agregar, ver y eliminar empresas
- **Gestión de Vehículos**: Registrar camiones, gacelas, buses, busetas, etc.
- **Gestión de Rutas**: Crear rutas con destinos, distancia y tarifas
- **Gestión de Horarios**: Programar salidas, llegadas y disponibilidad

## Diseño
- Interfaz **minimalista** y profesional
- **100% Responsive**: Funciona perfecto en desktop, tablet y móvil
- Colores modernos con gradientes y sombras sutiles
- Navegación intuitiva
- Espacios diseñados para imágenes

## Requisitos
- Navegador moderno (Chrome, Firefox, Edge, Safari)
- No requiere servidor ni instalación

## Estructura de Archivos
```
Proyecto_Terminal_Transportes_Estatica/
├── index.html              # Estructura HTML principal
├── css/
│   └── styles.css          # Estilos CSS minimalistas
├── js/
│   └── script.js           # Funcionalidad JavaScript
├── documentacion/
│   └── README.md           # Este archivo
└── img/                    # Carpeta de recursos (imágenes usadas en la página)
```

## Cómo Usar

### 1. Abrir la Página
- Simplemente abre `index.html` en tu navegador favorito
- O haz doble clic en el archivo

### 2. Navegación Principal
- **Inicio**: Panel principal con información resumida
- **Empresas**: Lista de empresas de transporte disponibles
- **Buscar Viajes**: Búsqueda avanzada de viajes
- **Carrito**: Ver y comprar tiquetes
- **Admin**: Panel de administración

### 3. Buscar un Viaje
1. Haz clic en "Buscar Viajes"
2. Ingresa ciudad de origen
3. Ingresa ciudad de destino
4. Selecciona la fecha
5. Indica cantidad de pasajeros
6. Presiona "Buscar"
7. Selecciona un viaje y agrega al carrito

### 4. Comprar Tiquetes
1. Haz clic en el carrito (número arriba a la derecha)
2. Revisa los tiquetes y precios
3. Modifica cantidades si lo deseas
4. Presiona "Comprar Tiquetes"
5. ¡Listo! Recibirás confirmación

### 5. Administración
1. Haz clic en "Admin" (botón naranja)
2. Elige la pestaña deseada:
   - **Empresas**: Agrega nuevas empresas de transporte
   - **Vehículos**: Registra buses, taxis, etc.
   - **Rutas**: Define rutas y tarifas base
   - **Horarios**: Programa viajes específicos

## Almacenamiento de Datos
Todos los datos se guardan automáticamente en el **localStorage** del navegador:
- Los cambios persisten aunque cierres el navegador
- Los datos se guardan en cada dispositivo de forma independiente
- Para limpiar datos: Abre la consola (F12) y ejecuta `localStorage.clear()`

## Datos de Ejemplo Iniciales

### Empresas Vinculadas
- TravelMax
- TransExpress
- BusViajes
- TransGlobal

### Rutas Disponibles
- Bogotá ↔ Medellín
- Bogotá ↔ Cali
- Bogotá ↔ Cartagena
- Medellín ↔ Cali
- Bogotá ↔ Bucaramanga

### Tipos de Vehículos Soportados
- Bus (45 pasajeros)
- Buseta (30 pasajeros)
- Gacela (15 pasajeros)
- Camión
- Aerovan
- Taxi (4 pasajeros)

## Personalización

### Cambiar Datos de la Terminal
En `script.js`, línea ~7, modifica:
```javascript
terminal: {
    nombre: "Tu nombre de terminal",
    ubicacion: "Tu ubicación",
    telefono: "Tu teléfono",
    email: "Tu email",
    horario: "Tu horario"
}
```

### Cambiar Colores
En `styles.css`, línea ~1, modifica las variables:
```css
--color-primary: #2563eb;      /* Azul principal */
--color-secondary: #1e40af;    /* Azul oscuro */
--color-accent: #f59e0b;       /* Naranja/Amarillo */
--color-success: #10b981;      /* Verde */
--color-danger: #ef4444;       /* Rojo */
```

### Agregar Imágenes y Logos
Las imágenes y logos que se usan en la página (logotipo de la terminal, logos de empresas, fotos de rutas, etc.) deben guardarse en la carpeta `img/`.

El proyecto ya incluye ejemplos de archivos SVG:
- `img/logo.svg` – logotipo principal de TermiHub
- `img/logo2.svg` – segundo logo que aparece junto al principal y también en la parte inferior de la sección de inicio
- `img/empresa-1.svg`, `empresa-2.svg`, `empresa-3.svg`, `empresa-4.svg` – logos de las empresas de transporte.
- `img/icon-terminal.svg`, `img/icon-empresas.svg`, `img/icon-vehiculos.svg`, `img/icon-rutas.svg` – pequeños iconos usados en los cards de la página de inicio

Para añadir tus propias imágenes:
1. Copia el archivo (`.png`, `.jpg`, `.svg`, etc.) dentro de `img/`.
2. Actualiza en `index.html`, el CSS o el JavaScript las rutas `src="img/tu-archivo.ext"`.
3. En el caso de las tarjetas de empresas, el código JavaScript busca automáticamente `img/empresa-<ID>.svg`; si la imagen no existe, la tarjeta queda vacía (se puede editar el template para usar un ícono de respaldo).

Ejemplo de inserción manual:
```html
<!-- Antes -->
<div class="placeholder-image">🚌</div>

<!-- Después -->
<img src="img/mi-imagen.jpg" alt="Descripción">
```

## Seguridad
- ✓ Validación de formularios
- ✓ Almacenamiento seguro en localStorage
- ✓ Protección básica de datos
- ✓ Confirmación de transacciones
- ✓ Interfaz de administración integrada

## Responsividad
- ✓ Desktop (1200px+)
- ✓ Tablet (768px - 1199px)
- ✓ Móvil (menos de 768px)

## Tecnologías Utilizadas
- **HTML5**: Estructura semántica
- **CSS3**: Flexbox, Grid, Media Queries, Gradientes
- **JavaScript (Vanilla)**: Sin dependencias externas

## Soporte
Para preguntas o mejoras, puedes:
- Revisar el código fuente (bien comentado)
- Modificar directamente los archivos
- Agregar nuevas secciones según sea necesario

## Licencia
Proyecto educativo. Usado libremente.

## ¡Listo para usar!
Tu plataforma de gestión de transporte está lista. Personalízala según tus necesidades y ¡disfruta!

---
**Creado para la Terminal de Transporte**
