---
layout: default
title: Resolución de problemas - Daikin ONECTA
---

# Resolución de problemas

Esta página responde a los problemas más frecuentes encontrados con el plugin Daikin ONECTA.

## Mis equipos no aparecen

**Comprobaciones a realizar en orden:**

1. ¿Está **iniciado el servicio del plugin**? (Herramientas → Salud)
2. ¿Está el plugin **mqtt2** instalado, activado e iniciado?
3. ¿Están **instaladas las dependencias**? (botón «Reinstalar dependencias»)
4. ¿Son correctas sus **credenciales Daikin**? (pruébelas en la aplicación Onecta móvil)
5. ¿Son visibles sus dispositivos en la aplicación **Daikin Onecta** en su teléfono?

**Acciones:**

- Reinicie el servicio del plugin.
- Guarde de nuevo la configuración, luego reinicie.
- Consulte los logs (véase más abajo).

## El servicio del plugin no inicia

**Causas frecuentes:**

| Causa | Solución |
|-------|----------|
| Dependencias no instaladas | Haga clic en «Reinstalar dependencias» |
| Credenciales Daikin incorrectas | Compruebe email/contraseña o Client ID/Secret |
| mqtt2 no iniciado | Inicie el plugin mqtt2 |

Tras la corrección, reinicie el servicio del plugin.

## Error de conexión a Daikin

### Modo Mobile App

- Compruebe que puede conectarse a la aplicación Daikin Onecta en su teléfono.
- Si ha cambiado su contraseña, actualícela en la configuración del plugin.
- Reinicie el servicio tras la modificación.

### Modo Developer Portal

- Compruebe que el Client ID y el Client Secret son correctos.
- Si Daikin ha invalidado su clave, reinicie el procedimiento de conexión (véase [Autenticación]({{ site.baseurl }}/es_ES/authentification.html)).
- Compruebe que el puerto de autenticación (predeterminado: 8765) no está bloqueado.

## Mis comandos no reaccionan

1. ¿Está **activado** el equipo en Jeedom?
2. ¿Hay un **código de error** mostrado en el equipo? (pestaña Equipo)
3. ¿Sigue **iniciado** el servicio del plugin?
4. ¿Está **agotada** la cuota diaria? (véase [Límites y buenas prácticas]({{ site.baseurl }}/es_ES/quota-api.html))

Pruebe a enviar el mismo comando desde la aplicación Daikin Onecta en su teléfono. Si tampoco funciona, el problema proviene del dispositivo o de la nube Daikin, no de Jeedom.

## Pocos comandos en mi dispositivo

Los comandos dependen del modelo de su dispositivo. Si ve pocos comandos:

1. Compruebe que la opción **Modelos desconocidos** está activada (Configuración avanzada).
2. Espere unos minutos tras el inicio del plugin — la sincronización puede tardar un momento.
3. Reinicie el servicio del plugin.

Si su modelo es muy reciente, repórtelo en el [foro Jeedom](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55) para mejorar la compatibilidad.

## Errores de red repetidos

Si el plugin muestra errores de conexión a la nube Daikin (timeouts, bloqueos):

1. Abra **Plugins → Daikin ONECTA → Configuración → Configuración avanzada**.
2. Cambie el **Transporte HTTP** de «Node.js» a «curl».
3. Guarde y reinicie el servicio.

## Consultar los logs

Para diagnosticar un problema:

1. Vaya a **Análisis → Logs** en Jeedom.
2. Filtre por plugin: `daikinRCCloud`.
3. Busque los mensajes de error (en rojo).

## Solicitar ayuda

Si no encuentra la solución, solicite ayuda indicando:

| Información | Dónde encontrarla |
|-------------|---------------|
| Versión del plugin | Configuración → Informaciones |
| Versión del servicio interno | Configuración → Informaciones |
| Versión de Jeedom | Página de inicio de Jeedom |
| Modo de conexión utilizado | Configuración → Modo de autenticación |
| Descripción del problema | Lo que esperaba vs lo que ocurre |
| Extracto de logs | Análisis → Logs, filtro daikinRCCloud |

### Foro Jeedom

- [Hilo de discusión del plugin](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55)
- En Jeedom 4.4+, utilice el botón **Crear un post Community** en la página del plugin para rellenar previamente un formulario de ayuda.

### GitHub

Para reportar un bug: [Issues GitHub](https://github.com/ThibautTrarbach/daikinRCCloud/issues)

---

[Anterior: Límites y buenas prácticas]({{ site.baseurl }}/es_ES/quota-api.html) — [Volver al inicio]({{ site.baseurl }}/es_ES/)
