---
layout: default
title: Changelog Daikin ONECTA (Beta)
---

# Changelog Daikin ONECTA

Todas las modificaciones notables de este plugin se documentarán en esta página.

## [0.10.2] - 2026-09-01

> Daemon incluido en la rama `release-beta`: **2.1.3** (mínimo requerido: 2.0.0)

### Añadido — Plugin
- Panel de diagnóstico de soporte ampliado: mensaje de soporte, puntos de gestión, unidades detectadas, datapoints no mapeados, informe de debug con botón copiar
- Enlace GitHub para reportar dispositivos no soportados (`githubIssueUrl`)
- Sincronización de metadatos de soporte daemon → configuración del equipo
- Limpieza automática de comandos de soporte obsoletos (logical IDs `_supportStatus`, `_configCoverage`, etc.)

### Añadido — Daemon
- `gatewayDiagnosticsPack` y `auxiliaryUnitPack`: sensores de red/diagnóstico del gateway y unidades indoor/outdoor (BRP069C4x)
- Mapeo read-only `isPowerfulModeActive` (API firmware 2.6.x)
- Módulo de metadatos de soporte: informe de debug, URL de issue GitHub, comandos de soporte sincronizados

### Corregido — Daemon
- Auditoría de cobertura API: normalización de rutas datapoint, conteo de metadatos `_device`
- Preset mode Home Assistant: fallback a `_isPowerfulModeActive` si `_powerfulMode` está ausente

### Modificado — Daemon
- Enriquecimiento `_device`: `ipAddress`, `macAddress`, alias `ssid`
- Informe de debug: mensaje de soporte, indicador de truncamiento de datapoints no mapeados

---

## [0.10.1] - 2026-09-01

> Daemon incluido en la rama `release-beta`: **2.1.2** (mínimo requerido: 2.0.0)

### Añadido — Plugin
- Alertas de estado de soporte: notificación en caso de soporte parcial o configuración incompleta de un dispositivo Daikin (banner UI + información de diagnóstico)
- Limpieza de equipos fantasma: eliminación automática de IDs lógicos creados desde topics MQTT internos (puente del sistema)
- Notificaciones de autenticación: solicitud de validación OAuth con mensaje al usuario y gestión de la expiración del tiempo de espera

### Añadido — Daemon
- Timeout de autorización OAuth configurable (`authorizationTimeoutSeconds`, 60–3600 s)
- Limpieza al inicio de topics MQTT retenidos obsoletos
- WebSocket: soporte de características anidadas y referencias en actualizaciones push

### Modificado
- Aclaración de la documentación: distinción entre el puente Daikin2MQTT y los dispositivos Daikin reales
- Apagado controlado del daemon reforzado (omitir operaciones durante el apagado)
- Gestión de rate-limit mejorada: fusión de actualizaciones parciales, conservación de valores anteriores

---

## [0.10.0] - 2026-08-30

> Daemon incluido en la rama `release-beta`: **2.1.0** (mínimo requerido: 2.0.0)

### Cambio importante
- El daemon V1 (< 2.0.0) ya no es compatible — migración obligatoria al daemon V2
- **Reinstalación de dependencias requerida** tras actualizar el plugin
- Rama predeterminada de dependencias: `release-beta`
- Migración automática de ramas V1 (`release-stable`, `dev`, `release-dev`, etc.) hacia `release-beta`
- Requisitos: Jeedom 4.4+, plugin **mqtt2** instalado y en ejecución

### Añadido — Plugin
- Renovación completa de la página de configuración (secciones plegables, modo «Configuración avanzada» memorizado)
- Visualización de versiones del plugin y del daemon (útil para el soporte comunitario)
- Modo **Mobile App**: conexión con email/contraseña Onecta, cuota ~3000 req/día
- Modo **Developer Portal**: OAuth Client ID/Secret, cuota ~200 req/día
- Estimación automática de la cuota API y de las peticiones/día según el modo de autenticación
- Nuevos ajustes avanzados: estrategia refresh post-acción, fusión con polling, coalescencia de comandos, refresh estadísticas energía, WebSocket, DynamicGateway, sensores solo lectura, publicación si delta, transporte HTTP curl, prefijo MQTT
- Contraseña Onecta almacenada cifrada
- Comprobaciones mqtt2 con mensajes de error explícitos si está ausente o no iniciado
- Primera versión de la documentación en línea: instalación, configuración, autenticación, uso, cuotas API, resolución de problemas. Contenido generado rápidamente por una IA antes de su publicación — **aún no revisado ni validado**

### Añadido — Daemon (vía el plugin)
- Conexión simplificada con la misma cuenta que la aplicación Onecta (modo Mobile App)
- Actualizaciones en tiempo real vía WebSocket (reactividad sin consumir la cuota API)
- Soporte automático de modelos Daikin no listados (DynamicGateway)
- Contadores de energía kWh actualizados diariamente a hora configurable
- Respuesta inmediata en Jeedom tras un comando (publicación optimista MQTT)
- Ahorro de cuota: fusión refresh/polling, polling adaptativo según presupuesto API, skip si WebSocket confirma el cambio
- Contorno de bloqueos de red/WAF Daikin (transporte HTTP curl)
- Estado del presupuesto API visible en el puente del sistema MQTT

### Modificado
- Interfaz de autenticación: conmutación dinámica Mobile App / Developer Portal con cuotas mostradas
- Valores predeterminados de polling: 15 min (día) / 30 min (noche); retardo refresh post-acción: 60 s (en lugar de 120 s)
- Instalación del daemon más fiable: ejecución vía `main.js` compilado, comprobaciones en la instalación
- Mensajes de error explícitos (mqtt2 ausente, daemon demasiado antiguo, `main.js` no encontrado)
- ~90 nuevas traducciones de interfaz (FR, EN, ES, DE, IT)
- Ramificación versionada conservada con stubs V3 comentados para futuras evoluciones

### Eliminado
- Soporte del daemon V1 (< 2.0.0) y autenticación OAuth vía MQTT
- Código V1 del plugin (configuración, mensajes MQTT legacy)
- Ramas de dependencias obsoletas: `release-stable`, `dev`, `release-dev`

---

## [0.9.3] - 2025-12-10

### Mejora
- Mejora de la actualización del plugin
- Mejoras de los logs del plugin con traducciones
- Corrección de valores que podían estar vacíos en la página de configuración
- Mejor gestión de errores en la página de configuración y en las tareas ejecutadas durante la instalación o actualización del plugin


---
## [0.9.2] - 2025-12-09

### Corrección
- Corrección de un bug de actualización de la configuración

### Breaking change
- El plugin requiere ahora Jeedom 4.4 como mínimo

---

## [0.9.1] - 2025-12-05

### Información
- El Daemon 2.0.x aún no está disponible correctamente. En los próximos días, se propondrá probarlo en modo alpha para evitar impactar sus instalaciones en producción durante el periodo de calefacción

### Añadido
- Configuración de dependencias: posibilidad de elegir la rama o el commit a instalar
- Soporte de parámetros de polling (día/noche) para el daemon 2.0.0+
- Soporte de modos de actualización tras acción para el daemon 2.0.0+
- Compatibilidad con versiones V1 y V2 del daemon
- Internacionalización completa (FR, EN, ES, DE, IT)
- Enlace hacia la futura documentación y changelog

### Modificado
- Mejora de la gestión de mensajes MQTT
- Optimización de la creación de equipos

### Corregido
- Diversas correcciones de bugs

---

## [0.9.0] - En el año 2022 (Sin recuerdo de la fecha)

### Añadido
- Versión inicial del plugin
