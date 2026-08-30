---
layout: default
title: Límites y buenas prácticas - Daikin ONECTA
---

# Límites y buenas prácticas

Daikin impone un límite al número de veces que Jeedom puede consultar la nube por día. Esta página explica por qué existe este límite y cómo optimizarlo.

## ¿Por qué un límite?

Para funcionar, el plugin debe solicitar regularmente a Daikin el estado de sus dispositivos (temperatura, modo, encendido/apagado…). Cada solicitud cuenta en una **cuota diaria** fijada por Daikin. Esta cuota se reinicia cada día a medianoche.

No es una limitación del plugin, sino una regla impuesta por el servicio en la nube Daikin ONECTA.

## Cuota según su modo de conexión

| Modo de conexión | Consultas autorizadas por día |
|-------------------|-----------------------------------|
| **Developer Portal** (recomendado) | 200 |
| **Mobile App** | 3000 |

## ¿Qué consume la cuota?

| Acción | Consumo |
|--------|-------------|
| Sincronización planificada (cada 15 min por defecto) | 1 consulta (para todos sus dispositivos a la vez) |
| Comando (cambiar la temperatura, encender…) | 1 consulta por modificación |
| Verificación tras un comando | 1 consulta (según ajustes) |
| Actualización de contadores kWh (cada noche) | 1 consulta |
| Actualización en tiempo real (WebSocket, modo Mobile App) | **0** consultas |

**Punto importante:** una sincronización consulta **todos** sus dispositivos de una sola vez. Tener 1 o 5 climatizadores consume el mismo número de consultas.

## Estimación mostrada en la configuración

En la página de configuración (sección avanzada), el campo **Número de peticiones planificadas/día** estima cuántas consultas GET planificará el daemon cada día, en función de sus intervalos día/noche, del modo de autenticación y del WebSocket.

| Configuración | Detalle | Total planificado |
|---------------|--------|-------------------|
| Developer Portal, valores predeterminados (15 min día, 30 min noche, noche 22h→7h) | 60 polls día + 18 polls noche + 1 stats energía | **~79 GET/día** |
| Mobile App + WebSocket activado, mismos intervalos | Red de seguridad 30/60 min: 30 + 9 + 1 | **~40 GET/día** |

Estas cifras no incluyen sus comandos, los refresh post-acción (según ajustes) ni el GET de inicio del daemon (+1 en cada reinicio).

## Consejos para optimizar

### Para la mayoría de los usuarios (Developer Portal)

1. **Deje los ajustes predeterminados** — están diseñados para un buen equilibrio con la cuota de 200 consultas/día.
2. **No reduzca los intervalos de sincronización** por debajo de 15 minutos.
3. **Evite escenarios** que envíen muchos comandos seguidos.

### Si utiliza el modo Mobile App (3000/día)

- **Mantenga el WebSocket activado** — los cambios de estado llegan en tiempo real sin consumir cuota.
- Puede aumentar ligeramente los intervalos de sincronización manteniendo la reactividad gracias al WebSocket.

### Si tiene muchos dispositivos y automatizaciones

- Aumente ligeramente los intervalos de sincronización (p. ej. 20 min de día, 45 min de noche).
- El modo híbrido (predeterminado) para el comportamiento tras comando es el más económico.

## ¿Qué ocurre si se alcanza la cuota?

El plugin ralentiza automáticamente las sincronizaciones cuando la cuota está casi agotada. Sus comandos siguen funcionando, pero las actualizaciones de estado pueden ser menos frecuentes hasta el día siguiente.

En modo Developer Portal, un exceso de cuota puede bloquear todas las consultas hasta medianoche.

---

[Anterior: Uso]({{ site.baseurl }}/es_ES/utilisation.html) — [Siguiente: Resolución de problemas]({{ site.baseurl }}/es_ES/depannage.html)
