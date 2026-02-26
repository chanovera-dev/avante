# Avante Project - Gestión de Propiedades

## Visión General
**Avante Project** es una solución integral de gestión inmobiliaria desarrollada sobre WordPress, diseñada para cerrar la brecha entre la gestión administrativa y la presencia digital de alto impacto.
A diferencia de las integraciones estándar, este sistema no solo visualiza datos externos, sino que los **asimila localmente** para ofrecer una experiencia de usuario ultra rápida, un SEO optimizado y un control total sobre la inteligencia de datos a través de un panel de métricas personalizado.

## Descripción
Este proyecto permite a una inmobiliaria gestionar su catálogo de propiedades de forma híbrida. El sistema se sincroniza con la API de EasyBroker para obtener las propiedades de manera automatizada, manteniendo la capacidad de almacenarlas, editarlas y expandirlas localmente. Incluye un panel de control avanzado para la visualización de estadísticas y métricas del inventario.

## Características Principales

### Sincronización Inteligente (EasyBroker API)
El sistema utiliza un motor de sincronización robusto que conecta la API de **EasyBroker** con un **Custom Post Type (CPT)** local denominado `property`.
- **Actualización Automatizada**: Sincronización periódica de precios, estados, descripciones y galerías multimedia, etc (todos los datos que entrega la API de EasyBroker).
- **Persistencia de Datos**: Las propiedades residen en la base de datos local, garantizando disponibilidad inmediata y eliminando la latencia de peticiones externas.
- **Control Total**: El usuario tiene la capacidad de gestionar las propiedades localmente, lo que le permite realizar cambios en tiempo real y mantener un control total sobre su contenido.
- **Mapeo de Atributos**: Conversión inteligente de metadatos (recámaras, baños, m², amenidades) para filtros de búsqueda avanzados.
- **Filtros Avanzados**: Implementación de filtros personalizados para búsqueda de propiedades por ubicación, precio, tipo de propiedad, etc.
- **SEO Optimizado**: Optimización de metadatos para mejorar el posicionamiento en motores de búsqueda.
- **Dashboard de Estadísticas:** Panel administrativo integrado en WordPress que ofrece una vista panorámica del inventario:
    - Métricas de estado (publicadas, borradores, privadas).
    - Desglose por tipo de operación (Venta/Renta).
    - Estadísticas por tipo de propiedad y ubicación.
    - Estado y log de la última sincronización.
- **Filtrado AJAX Avanzado:** Sistema de búsqueda en tiempo real que permite navegar por el catálogo mediante:
    - Ubicación (Estado y Ciudad).
    - Tipo de Operación y Tipo de Propiedad.
    - Rangos de precio y dimensiones (construcción/terreno).
- **Galería Inteligente:** Gestión unificada que combina imágenes de la API con archivos locales subidos mediante ACF/SCF.
- **Acciones Rápidas (CTA):** Conversión automática de datos de contacto (WhatsApp, Teléfono, Email) en botones de acción directa en las fichas.

### Dashboard de Análisis y Métricas
El proyecto incluye un panel administrativo exclusivo diseñado para la toma de decisiones basada en datos reales:
- **Monitor de Rendimiento**: Visualización de las propiedades más visitadas y con mayor tasa de interacción.
- **Estadísticas de Inventario**: Gráficos comparativos por tipo de operación (Venta/Renta) y zonas geográficas.
- **Auditoría de Sincronización**: Registro detallado de las últimas actualizaciones exitosas desde la API.

### Optimización y Rendimiento
- **SEO Local Avanzado**: Al ser contenido local, cada propiedad genera su propia URL amigable, metatags y esquema de datos para buscadores.
- **Arquitectura Escalable**: Capacidad para manejar catálogos de cientos de propiedades sin degradar la velocidad del sitio.
- **Interfaz Adaptable**: Diseño totalmente responsive enfocado en la conversión, permitiendo que los clientes contacten al agente desde cualquier dispositivo.

### Stack Tecnológico
- **Core**: WordPress (Arquitectura de CPT & Custom Taxonomies).
- **Integración**: EasyBroker REST API.
- **Frontend**: Desarrollado para alta velocidad de carga y experiencia de usuario (UX) intuitiva.
- **Data Engine**: Lógica de procesamiento de datos para el Dashboard de métricas.

## Funcionalidades del Tema (Frontend & UX)
Maquetación moderna enfocada en el rendimiento y la experiencia de usuario:

- **Optimización y Rendimiento:**
    - Carga Condicional de Recursos: Los assets (JS/CSS) se cargan solo en las vistas donde son necesarios.
    - Sistema de Iconos SVG: Integración nativa en PHP para evitar peticiones HTTP adicionales.
    - SEO Estructural: Estructura semántica HTML5 y soporte nativo para Google Tag Manager.
- **Experiencia de Usuario:**
    - Diseño "Mobile-First" con CSS moderno (Nesting, Variables) y `theme.json`.
    - Modo Noche Nativo: Activación automática basada en la preferencia del sistema operativo del usuario.
    - Navegación Refinada: Migas de pan (breadcrumbs) dinámicas y paginación numérica.
- **Soporte de Contenidos:**
    - Formatos de Post Personalizados: Estilos específicos para Galerías (con Slider y Lightbox), Video, Citas, Enlaces y más.
    - Bloques Gutenberg a Medida: Renderizado personalizado para galerías interactivas y formularios de búsqueda.

## Estructura del Proyecto
```text
avante-project/
├── assets/                     # Recursos estáticos (CSS modular, JS, Iconos)
├── inc/                        # Lógica modular
│   ├── core.php                # Configuración principal y encolado de assets
│   ├── real-estate-tools.php   # Lógica de filtros y panel de control de propiedades
│   ├── easybroker-sync.php     # Integración y sincronización con la API
├── template-parts/             # Fragmentos de plantilla reutilizables
│   ├── loop/                   # Plantillas para listados (content-property.php)
│   ├── page/                   # Plantillas para páginas estáticas
│   └── single/                 # Plantillas para entradas individuales
├── functions.php               # Punto de entrada de la lógica del tema
├── theme.json                  # Configuración global de estilos y bloques
└── README.md                   # Documentación técnica
```

### Requisitos del Sistema
- Claves de API activas de EasyBroker.
- Módulo de gestión de campos personalizados (ACF o SCF).

## Requisitos del Sistema
- WordPress 6.x o superior.
- PHP 8.x o superior.
- API Key de EasyBroker activa.

## Desarrollo y Personalización
- **Lógica:** Toda la funcionalidad crítica reside en `inc/` para mantener `functions.php` organizado. El resto de archivos dentro de `inc/` son modulares y contienen la lógica de la Rest API y del Real Estate Tools.
- **REST API:** El Dashboard consume datos locales mediante la REST API de WordPress para una representación gráfica fluida sin recargas.
- **Estilos:** Se utiliza `style.css` para definir tokens de diseño y variables globales.

## Créditos y Licencia
- **Autor:** ChanoDEV (https://chano.dev)
- **Licencia:** [GNU General Public License v2](https://choosealicense.com/licenses/gpl-2.0/).