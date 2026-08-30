---
layout: default
title: Configuración - Daikin ONECTA
---

# Configuración

La página de configuración se encuentra en **Plugins → Daikin ONECTA → Configuración**.

La mayoría de los usuarios solo necesitan modificar la sección **Conexión Daikin**. Los demás ajustes son accesibles mediante la casilla **Configuración avanzada**.

---

## Conexión Daikin

Es la sección más importante. Permite vincular Jeedom a su cuenta Daikin.

### Modo de autenticación

Hay dos modos disponibles:

| Modo | Descripción | Cuota diaria |
|------|-------------|--------------|
| **Developer Portal** | Aplicación en el [portal de desarrolladores Daikin](https://developer.cloud.daikineurope.com/) | 200 consultas/día |
| **Mobile App** | Credenciales de la cuenta Onecta (aplicación móvil) | 3000 consultas/día |

Consulte la página [Autenticación]({{ site.baseurl }}/es_ES/authentification.html) para el detalle de cada modo.

Consulte la página [Autenticación]({{ site.baseurl }}/es_ES/authentification.html) para el detalle de cada modo.

### Credenciales

Según el modo elegido:

- **Mobile App:** introduzca su **email Onecta** y su **contraseña Onecta**.
- **Developer Portal:** introduzca el **Client ID** y el **Client Secret** de su aplicación Daikin Developer.

### Cuota API diaria

Este campo muestra el número máximo de consultas a la nube autorizadas por día según su modo de conexión. Se calcula automáticamente y no es modificable.

Para comprender qué implica esto en la práctica, consulte [Límites y buenas prácticas]({{ site.baseurl }}/es_ES/quota-api.html).

### Versiones

Las versiones del plugin y del servicio interno se muestran en solo lectura. Indíquelas si solicita ayuda en el foro.

---

## Configuración avanzada

Marque **Configuración avanzada** para mostrar los ajustes adicionales. **La mayoría de los usuarios pueden dejar los valores predeterminados.**

### Frecuencia de actualización

Estos ajustes determinan con qué frecuencia Jeedom consulta la nube Daikin para conocer el estado de sus dispositivos.

| Ajuste | Predeterminado | Descripción |
|---------|--------|-------------|
| **Intervalo diurno** | 15 min | Frecuencia de comprobación entre la mañana y la noche |
| **Intervalo nocturno** | 30 min | Frecuencia de comprobación durante la noche (ahorra cuota) |
| **Inicio de la noche** | 22h | Hora a la que comienza el periodo nocturno |
| **Fin de la noche** | 7h | Hora a la que termina el periodo nocturno |

> **Consejo:** con el modo Mobile App y las actualizaciones en tiempo real activadas, puede aumentar estos intervalos sin perder reactividad.

El campo **Número de peticiones planificadas/día** estima cuántas consultas GET planificará el daemon cada día (polling + estadísticas de energía), teniendo en cuenta el modo de autenticación y el WebSocket. La actualización es automática cuando modifica los ajustes. Los comandos y el refresh post-acción se suman a esta estimación.

### Comportamiento tras un comando

Cuando ejecuta un comando (cambiar la temperatura, encender la climatización…), el plugin puede reaccionar de tres formas:

| Modo | Comportamiento | Cuándo usarlo |
|------|--------------|------------------|
| **1 — Actualización completa diferida** | Espera y luego comprueba el estado real en Daikin | Si desea una confirmación sistemática de la nube |
| **2 — Actualización inmediata** | Actualiza Jeedom de inmediato, sin comprobar Daikin | Para ahorrar cuota, si la reactividad es suficiente |
| **3 — Híbrido** (predeterminado) | Actualización inmediata + comprobación Daikin tras un retardo | **Recomendado** — buen equilibrio reactividad / fiabilidad |

**Retardo de actualización:** en modo 1 y 3, tiempo de espera antes de la comprobación en Daikin (predeterminado: 60 segundos). Deje este retardo si sus dispositivos tardan un poco en reaccionar.

**Estrategia de verificación:**

| Estrategia | Descripción |
|-----------|-------------|
| **Fusión con la sincronización** (predeterminado) | Si una sincronización planificada llega pronto, el plugin espera en lugar de hacer una petición adicional |
| **Verificación dedicada** | El plugin consulta Daikin específicamente tras cada comando |
| **Sin verificación** | Ninguna consulta a la nube tras un comando |

**Refresh de estadísticas de energía:** hora diaria (predeterminado 23h58) a la que el plugin actualiza los contadores de consumo kWh.

### Compatibilidad automática de modelos

| Opción | Predeterminado | Descripción |
|--------|--------|-------------|
| **Modelos desconocidos** | Activado | Permite controlar automáticamente modelos Daikin no listados explícitamente |
| **Sensores solo lectura** | Activado | Muestra temperaturas exteriores, diagnósticos, etc. |
| **Publicación si cambio** | Activado | Solo actualiza Jeedom cuando un valor ha cambiado realmente |

Desactive **Modelos desconocidos** únicamente si encuentra un comportamiento anómalo con un dispositivo no reconocido.

### Opciones adicionales

| Opción | Predeterminado | Descripción |
|--------|--------|-------------|
| **WebSocket tiempo real** | Activado | Recibe cambios de estado en directo (solo modo Mobile App) |
| **Puerto de autenticación** | 8765 | Puerto local para la conexión Developer Portal únicamente |
| **Prefijo MQTT** | daikinToMQTT | Dejar predeterminado salvo conflicto con otro plugin |

---

## Ajustes expertos

> **No modifique estos ajustes salvo que el soporte se lo indique o sepa por qué.**

### Transporte HTTP

Si el plugin no logra comunicarse con Daikin (errores de red repetidos, bloqueo por un cortafuegos), cambie de **Node.js** a **curl**. Utiliza otro motor de red que a veces evita los bloqueos.

### Configuración de dependencias

Permite elegir qué versión del servicio interno del plugin se instala (rama o versión precisa). **Deje los valores predeterminados** (`release-beta`) salvo indicación contraria del soporte.

Tras cualquier modificación, relance la instalación de dependencias.

---

[Anterior: Instalación]({{ site.baseurl }}/es_ES/installation.html) — [Siguiente: Autenticación]({{ site.baseurl }}/es_ES/authentification.html)
