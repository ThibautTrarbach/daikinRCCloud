---
layout: default
title: Documentación Daikin ONECTA
---

# Documentación Daikin ONECTA

El plugin **Daikin ONECTA** permite controlar y supervisar sus equipos Daikin compatibles con ONECTA directamente desde Jeedom: climatizadores, bombas de calor Altherma y otros dispositivos conectados a través de la nube Daikin.

## ¿Para qué sirve este plugin?

Con este plugin, puede integrar sus dispositivos Daikin en su instalación Jeedom como cualquier otro equipo domótico:

- Mostrar su estado en su **dashboard**
- Controlarlos mediante **escenarios** o **comandos de voz**
- Automatizar la calefacción, la climatización o la ventilación según sus hábitos

Sus dispositivos se **descubren automáticamente** en cuanto el plugin está correctamente configurado. No hay botón «Añadir un equipo»: si su climatizador aparece en la aplicación Daikin Onecta en su teléfono, puede aparecer en Jeedom.

## Qué puede hacer

| Función | Descripción |
|----------|-------------|
| **Encendido / apagado** | Encender o apagar su dispositivo |
| **Modos** | Frío, calor, automático, secado, ventilación sola |
| **Consigna** | Ajustar la temperatura deseada |
| **Ventilación** | Elegir la velocidad y la orientación del flujo de aire (según modelo) |
| **Modos especiales** | Eco, Powerful, Streamer (según modelo) |
| **Temperaturas** | Consultar la temperatura ambiente y exterior |
| **Humedad** | Consultar la humedad ambiente (según modelo) |
| **Consumo** | Seguir el consumo energético en kWh (día, semana, mes) |
| **Programaciones** | Activar o desactivar las programaciones configuradas en Onecta |
| **Modo vacaciones** | Activar el modo ausencia (según modelo) |
| **Actualización** | Lanzar una actualización de firmware si Daikin la propone |

> **Nota:** los comandos exactos dependen del modelo de su dispositivo. La mayoría de los modelos Onecta recientes son compatibles automáticamente, aunque no figuren en la lista siguiente.

## Equipos compatibles

El plugin admite especialmente las siguientes gamas:

| Gama / tipo | Ejemplos |
|--------------|----------|
| Climatización mono-zona | Daikin Perfera (FTXM), Stylish, Emura… |
| Climatización ampliada | Modelos con modos eco, streamer, orientación del flujo |
| Bomba de calor dual-zona | Daikin Altherma (calefacción + agua caliente) |
| Climatización multi-zona | Instalaciones con varias zonas |

Si su modelo no está listado explícitamente, el plugin intenta admitirlo automáticamente gracias a la **compatibilidad automática de modelos recientes** (opción activada por defecto en la configuración avanzada).

## Qué necesita

| Requisito | Detalle |
|-----------|--------|
| **Jeedom** | Versión 4.4 o superior |
| **Plugin mqtt2** | Obligatorio — instalado y activo en su Jeedom |
| **Cuenta Daikin** | **Developer Portal** (aplicación en [developer.cloud.daikineurope.com](https://developer.cloud.daikineurope.com/)) o **Mobile App** (cuenta Onecta) |
| **Acceso a Internet** | Requerido para comunicarse con la nube Daikin |

## Índice

| Página | Descripción |
|------|-------------|
| [Instalación]({{ site.baseurl }}/es_ES/installation.html) | Instalar el plugin y hacer aparecer sus dispositivos |
| [Configuración]({{ site.baseurl }}/es_ES/configuration.html) | Ajustes del plugin |
| [Autenticación]({{ site.baseurl }}/es_ES/authentification.html) | Conectarse a su cuenta Daikin |
| [Uso]({{ site.baseurl }}/es_ES/utilisation.html) | Controlar sus dispositivos a diario |
| [Límites y buenas prácticas]({{ site.baseurl }}/es_ES/quota-api.html) | Comprender los límites de la nube Daikin |
| [Resolución de problemas]({{ site.baseurl }}/es_ES/depannage.html) | Resolver los problemas habituales |

## Enlaces útiles

- [Changelog]({{ site.baseurl }}/es_ES/changelog.html)
- [Foro Jeedom](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55)
- [Repositorio GitHub](https://github.com/ThibautTrarbach/daikinRCCloud)

---

**Siguiente:** [Instalación]({{ site.baseurl }}/es_ES/installation.html)
