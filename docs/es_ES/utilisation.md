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

### Puente del sistema Daikin2MQTT Bridge

Siempre verá un equipo llamado **Daikin - Daikin2MQTT Bridge**. Es normal: se trata del **puente del sistema** del plugin, no de un dispositivo Daikin físico.

| Rol | Ejemplos de comandos |
|------|----------------------|
| Autenticación cloud | Authorization URL, Authorization Request |
| Supervisión | Rate Limit, Modules List, WebSocket Connected |
| Acciones globales | Refresh All Devices |

Sus **verdaderos dispositivos Daikin** (climatizador, bomba de calor, etc.) aparecen por separado como **Daikin - {nombre}**, tal como se define en la aplicación Onecta.

### Personalizar un equipo

Haga clic en la tarjeta del equipo para acceder a su configuración:

| Ajuste | Descripción |
|---------|-------------|
| **Nombre** | Renombre el equipo (p. ej. «Clim salón») |
| **Objeto padre** | Asócielo a una habitación o planta de su domótica |
| **Categoría** | Calefacción, confort, etc. |
| **Activar** | Activa o desactiva el equipo |
| **Visible** | Muestra u oculta el equipo en el dashboard |
| **Widget del plugin** | Activa el widget HVAC dedicado (ver más abajo) |
| **Enlace panel móvil** | Muestra el botón hacia el panel en el widget móvil |

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
|---------------------|-------------------|
| Encender / apagar | **State** (o Power) |
| Cambiar el modo (frío, calor, auto, seco, ventilador) | **Operation Mode** |
| Ajustar la temperatura deseada | **Temperature Control** |

### Ventilación

| Lo que desea hacer | Comando en Jeedom |
|---------------------|-------------------|
| Elegir el modo de ventilación | **Fan Current Mode** |
| Ajustar la velocidad del ventilador | **Fan Fixed** |
| Orientar el flujo de aire horizontalmente | **Fan Horizontal** (según modelo) |
| Orientar el flujo de aire verticalmente | **Fan Vertical** (según modelo) |

### Modos especiales

| Lo que desea hacer | Comando en Jeedom |
|---------------------|-------------------|
| Activar el modo económico | **Eco Mode** |
| Activar el modo potente | **Powerful Mode** |
| Activar el modo streamer (purificación) | **Streamer Mode** |

### Información (solo lectura)

| Lo que desea consultar | Comando en Jeedom |
|------------------------|-------------------|
| Temperatura ambiente | **Room Temperature** |
| Temperatura exterior | **Outdoor Temperature** |
| Humedad ambiente | **Room Humidity** |

### Consumo energético

| Lo que desea consultar | Comando en Jeedom |
|------------------------|-------------------|
| Consumo calefacción (día / semana / mes) | **Heating Consumption D/W/M** |
| Consumo refrigeración (día / semana / mes) | **Cooling Consumption D/W/M** |

Los contadores se actualizan automáticamente cada día (hacia las 23h58 por defecto).

### Programaciones y modos avanzados

| Lo que desea hacer | Comando en Jeedom |
|---------------------|-------------------|
| Activar / desactivar una programación Onecta | **Schedule** (según modelo) |
| Activar el modo vacaciones | **Preset Away** (según modelo) |
| Lanzar una actualización de firmware | **Firmware Update** (si Daikin la propone) |

---

## Dispositivos multi-zona

Algunos modelos, especialmente las bombas de calor **Altherma**, disponen de varias zonas independientes. En ese caso, verá comandos separados para cada zona:

- **Zona 1:** calefacción principal
- **Zona 2:** agua caliente sanitaria, o segunda zona de calefacción

Cada zona tiene sus propios comandos de marcha, modo y consigna.

---

## Mostrar en el dashboard

### Widget HVAC del plugin (opcional)

El plugin ofrece un **widget dedicado** (inspirado en DaikinOnlineCtrl) que agrupa marcha/parada, modo, consigna, ventilación y sondas en una sola baldosa (dashboard, móvil y panel).

Estas opciones están en la pestaña **Equipo** de cada dispositivo Daikin (no aparecen en el puente del sistema):

| Opción | Predeterminado | Descripción |
|--------|----------------|-------------|
| **Widget del plugin** — *Usar el widget HVAC (estilo DaikinOnlineCtrl)* | Desactivado | Sustituye la baldosa estándar de Jeedom por el widget HVAC del plugin |
| **Enlace panel móvil** — *Mostrar el botón hacia el panel en el widget móvil* | Activado | En la vista móvil, muestra un botón para abrir el **panel** (vista tipo aplicación) |

1. Abra el equipo (no el puente del sistema).
2. Active **Usar el widget HVAC (estilo DaikinOnlineCtrl)**.
3. (Opcional) Active o desactive **Mostrar el botón hacia el panel en el widget móvil**.
4. Guarde y coloque el equipo en el dashboard / diseño.

En dispositivos multi-zona (p. ej. Altherma), aparece un **selector de zona** en la parte superior de la baldosa. Las pestañas ausentes se ocultan automáticamente.

> El widget HVAC está **desactivado por defecto** para no cambiar el comportamiento de las instalaciones existentes.

### Baldosas Jeedom estándar

Sin el widget del plugin:

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
