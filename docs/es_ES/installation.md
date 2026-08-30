---
layout: default
title: Instalación - Daikin ONECTA
---

# Instalación

Esta guía le acompaña paso a paso para instalar el plugin y hacer aparecer sus dispositivos Daikin en Jeedom.

## Antes de empezar

Asegúrese de tener:

- Jeedom **4.4** o más reciente
- Una **aplicación Daikin Developer** (Client ID / Client Secret) — consulte [Autenticación]({{ site.baseurl }}/es_ES/authentification.html)
- Sus dispositivos Daikin ya configurados y visibles en la aplicación Onecta

## Paso 1: Instalar el plugin

1. Abra **Plugins → Gestión de plugins** en Jeedom.
2. Busque **Daikin ONECTA**.
3. Haga clic en **Instalar**, luego en **Activar**.

## Paso 2: Instalar el plugin mqtt2

El plugin **mqtt2** es **obligatorio**. Actúa como relé interno entre el plugin Daikin y sus equipos. En general, no tiene nada particular que configurar en él.

1. Instale el plugin **mqtt2** desde el market de Jeedom (si aún no lo está).
2. Actívelo.
3. Compruebe en **Herramientas → Salud** que mqtt2 está en ejecución.

## Paso 3: Instalar las dependencias

El plugin necesita componentes adicionales para funcionar. Jeedom los instala automáticamente:

1. Vaya a la página del plugin **Daikin ONECTA**.
2. Haga clic en **Reinstalar dependencias** (o mediante el botón dedicado en la gestión de plugins).
3. Espere a que finalice la instalación (puede tardar unos minutos).

> **Nota:** tras cada actualización del plugin, relance la instalación de dependencias si Jeedom se lo indica.

## Paso 4: Configurar la conexión Daikin

1. Abra **Plugins → Daikin ONECTA → Configuración**.
2. Elija el modo **Developer Portal (OAuth, recomendado)**.
3. Introduzca el **Client ID** y el **Client Secret** de su aplicación Daikin Developer.
4. Haga clic en **Guardar**.
5. Inicie el servicio del plugin y siga el procedimiento OAuth descrito en [Autenticación]({{ site.baseurl }}/es_ES/authentification.html).

Para el modo Mobile App (credenciales Onecta), consulte también [Autenticación]({{ site.baseurl }}/es_ES/authentification.html).

## Paso 5: Iniciar el servicio del plugin

1. Vaya a **Herramientas → Salud** (o a la página del plugin).
2. Inicie el **daemon** del plugin Daikin ONECTA.
3. Compruebe que el estado indica **En ejecución**.

## Paso 6: Verificar sus dispositivos

1. Vuelva a la página **Plugins → Daikin ONECTA**.
2. Sus dispositivos Daikin deberían aparecer en **Mis equipos**.

Si no aparece ningún dispositivo, consulte la página [Resolución de problemas]({{ site.baseurl }}/es_ES/depannage.html).

## ¿Y ahora qué?

- [Configurar los ajustes del plugin]({{ site.baseurl }}/es_ES/configuration.html)
- [Aprender a usar sus equipos]({{ site.baseurl }}/es_ES/utilisation.html)

---

[Anterior: Inicio]({{ site.baseurl }}/es_ES/) — [Siguiente: Configuración]({{ site.baseurl }}/es_ES/configuration.html)
