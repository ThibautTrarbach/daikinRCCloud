---
layout: default
title: Uso - Daikin ONECTA
---

# Uso

Esta página explica cómo utilizar sus dispositivos Daikin a diario en Jeedom: encontrar sus equipos, comprender los comandos disponibles, mostrarlos en el dashboard e integrarlos en escenarios.

## Encontrar sus equipos

1. Abra **Plugins → Daikin ONECTA**.
2. Sus dispositivos aparecen en **Mis equipos**, en forma de tarjetas.

Se añaden **automáticamente** durante la primera sincronización. No hay botón «Añadir un equipo».

### Personalizar un equipo

Haga clic en la tarjeta del equipo para acceder a su configuración:

| Ajuste | Descripción |
|---------|-------------|
| **Nombre** | Renombre el equipo (p. ej. «Clim salón») |
| **Objeto padre** | Asócielo a una habitación o planta de su domótica |
| **Categoría** | Calefacción, confort, etc. |
| **Activar** | Activa o desactiva el equipo |
| **Visible** | Muestra u oculta el equipo en el dashboard |

### Información del dispositivo

En la pestaña **Equipo**, un recuadro muestra la información remontada por Daikin:

| Información | Utilidad |
|-------------|---------|
| **Modelo** | Referencia de su dispositivo |
| **Número de serie** | Identificador único |
| **Versión firmware** | Versión del software embebido |
| **Código de error** | Código de alerta eventual — consulte el manual de su dispositivo o el soporte Daikin si se muestra un código |

---

## Comandos disponibles

Los comandos se crean automáticamente según el modelo de su dispositivo. Se encuentran en la pestaña **Comandos** de cada equipo.

> **Nota:** no todos los comandos siguientes están necesariamente disponibles en su dispositivo. Depende del modelo.

### Control principal

| Lo que desea hacer | Comando en Jeedom |
|--------------------------|---------------------|
| Encender / apagar | **State** (o Marcha) |
| Cambiar el modo (frío, calor, auto, seco, ventilador) | **Operation Mode** |
| Ajustar la temperatura deseada | **Temperature Control** |

### Ventilación

| Lo que desea hacer | Comando en Jeedom |
|--------------------------|---------------------|
| Elegir el modo de ventilación | **Fan Current Mode** |
| Ajustar la velocidad del ventilador | **Fan Fixed** |
| Orientar el flujo de aire horizontalmente | **Fan Horizontal** (según modelo) |
| Orientar el flujo de aire verticalmente | **Fan Vertical** (según modelo) |

### Modos especiales

| Lo que desea hacer | Comando en Jeedom |
|--------------------------|---------------------|
| Activar el modo económico | **Eco Mode** |
| Activar el modo potente | **Powerful Mode** |
| Activar el modo streamer (purificación) | **Streamer Mode** |

### Información (solo lectura)

| Lo que desea consultar | Comando en Jeedom |
|------------------------------|---------------------|
| Temperatura ambiente | **Room Temperature** |
| Temperatura exterior | **Outdoor Temperature** |
| Humedad ambiente | **Room Humidity** |

### Consumo energético

| Lo que desea consultar | Comando en Jeedom |
|------------------------------|---------------------|
| Consumo calefacción (día / semana / mes) | **Heating Consumption D/W/M** |
| Consumo refrigeración (día / semana / mes) | **Cooling Consumption D/W/M** |

Los contadores se actualizan automáticamente cada día (hacia las 23h58 por defecto).

### Programaciones y modos avanzados

| Lo que desea hacer | Comando en Jeedom |
|--------------------------|---------------------|
| Activar / desactivar una programación Onecta | **Schedule** (según modelo) |
| Activar el modo vacaciones | **Preset Away** (según modelo) |
| Lanzar una actualización de firmware | **Firmware Update** (si Daikin la propone) |

---

## Dispositivos multi-zona

Algunos modelos, especialmente las bombas de calor **Altherma**, disponen de varias zonas independientes. En ese caso, verá comandos separados para cada zona:

- **Zona 1**: calefacción principal
- **Zona 2**: agua caliente sanitaria, o segunda zona de calefacción

Cada zona tiene sus propios comandos de marcha, modo y consigna.

---

## Mostrar en el dashboard

Para controlar sus dispositivos desde la pantalla de inicio de Jeedom:

1. Abra el equipo y vaya a la pestaña **Comandos**.
2. Haga **visibles** los comandos que desee mostrar (casilla en la columna Opciones).
3. Añádalos a su dashboard mediante el configurador de diseño de Jeedom.

Los comandos binarios (encendido/apagado) se muestran como botones. Las consignas de temperatura se muestran como cursor (slider).

**Consejo:** para un uso diario, haga visibles como mínimo **State**, **Operation Mode** y **Temperature Control**.

---

## Usar en escenarios

Sus dispositivos Daikin pueden integrarse en cualquier escenario de Jeedom, como los demás equipos.

### Ejemplos

**Confort verano:**
> Si temperatura salón > 26°C → Encender la clim en modo refrigeración, consigna 24°C

**Modo noche:**
> A las 22h00 → Activar el modo Eco en todas las climatizaciones

**Salida de vacaciones:**
> Cuando el modo «Ausencia» está activado → Activar Preset Away en la PAC

**Ahorro de energía:**
> Si nadie está en casa → Apagar la climatización

**Vuelta a casa:**
> Cuando la geolocalización detecta un regreso → Encender la clim, modo auto, consigna 22°C

Para crear un escenario, vaya a **Herramientas → Escenarios** y utilice los comandos de sus equipos Daikin como acciones o condiciones.

---

## Modificar los comandos

Los comandos son generados automáticamente por el plugin. Puede:

- Renombrar un comando
- Cambiar su visibilidad
- Modificar su unidad o icono

> **Atención:** evite eliminar comandos generados automáticamente. Pueden recrearse durante la próxima sincronización.

---

[Anterior: Autenticación]({{ site.baseurl }}/es_ES/authentification.html) — [Siguiente: Límites y buenas prácticas]({{ site.baseurl }}/es_ES/quota-api.html)
