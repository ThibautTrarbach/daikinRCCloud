---
layout: default
title: Daikin ONECTA Documentation
---

# Daikin ONECTA Documentation

The **Daikin ONECTA** plugin lets you control and monitor your ONECTA-compatible Daikin equipment directly from Jeedom: air conditioners, Altherma heat pumps, and other devices connected via the Daikin cloud.

## What is this plugin for?

With this plugin, you can integrate your Daikin devices into your Jeedom installation like any other home automation equipment:

- Display their status on your **dashboard**
- Control them via **scenarios** or **voice commands**
- Automate heating, air conditioning, or ventilation based on your habits

Your devices are **discovered automatically** once the plugin is properly configured. There is no “Add equipment” button: if your air conditioner appears in the Daikin Onecta app on your phone, it can appear in Jeedom.

## What you can do

| Function | Description |
|----------|-------------|
| **On / off** | Turn your device on or off |
| **Modes** | Cool, heat, automatic, dry, fan only |
| **Setpoint** | Set the desired temperature |
| **Ventilation** | Choose fan speed and airflow direction (model-dependent) |
| **Special modes** | Eco, Powerful, Streamer (model-dependent) |
| **Temperatures** | View indoor and outdoor temperature |
| **Humidity** | View indoor humidity (model-dependent) |
| **Consumption** | Track energy consumption in kWh (day, week, month) |
| **Schedules** | Enable or disable schedules configured in Onecta |
| **Holiday mode** | Enable away mode (model-dependent) |
| **Update** | Start a firmware update if Daikin offers one |

> **Note:** the exact commands depend on your device model. Most recent Onecta models are supported automatically, even if they are not listed below.

## Compatible equipment

The plugin supports the following ranges in particular:

| Range / type | Examples |
|--------------|----------|
| Single-zone air conditioning | Daikin Perfera (FTXM), Stylish, Emura… |
| Extended air conditioning | Models with eco, streamer, airflow direction modes |
| Dual-zone heat pump | Daikin Altherma (heating + hot water) |
| Multi-zone air conditioning | Installations with multiple zones |

If your model is not listed explicitly, the plugin attempts to support it automatically thanks to **automatic support for recent models** (option enabled by default in advanced configuration).

## What you need

| Prerequisite | Detail |
|--------------|--------|
| **Jeedom** | Version 4.4 or higher |
| **mqtt2 plugin** | Required — installed and active on your Jeedom |
| **Daikin Developer account** | App created on [developer.cloud.daikineurope.com](https://developer.cloud.daikineurope.com/) (Developer Portal mode recommended) |
| **Internet access** | Required to communicate with the Daikin cloud |

## Table of contents

| Page | Description |
|------|-------------|
| [Installation]({{ site.baseurl }}/en_US/installation.html) | Install the plugin and make your devices appear |
| [Configuration]({{ site.baseurl }}/en_US/configuration.html) | Plugin settings |
| [Authentication]({{ site.baseurl }}/en_US/authentification.html) | Connect to your Daikin account |
| [Usage]({{ site.baseurl }}/en_US/utilisation.html) | Control your devices day to day |
| [Limits and best practices]({{ site.baseurl }}/en_US/quota-api.html) | Understand Daikin cloud limits |
| [Troubleshooting]({{ site.baseurl }}/en_US/depannage.html) | Solve common problems |

## Useful links

- [Changelog]({{ site.baseurl }}/en_US/changelog.html)
- [Jeedom Community](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55)
- [GitHub repository](https://github.com/ThibautTrarbach/daikinRCCloud)

---

**Next:** [Installation]({{ site.baseurl }}/en_US/installation.html)
