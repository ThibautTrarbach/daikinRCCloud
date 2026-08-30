---
layout: default
title: Usage - Daikin ONECTA
---

# Usage

This page explains how to use your Daikin devices day to day in Jeedom: find your equipment, understand available commands, display them on the dashboard, and integrate them into scenarios.

## Find your equipment

1. Open **Plugins → Daikin ONECTA**.
2. Your devices appear under **My equipment**, as cards.

They are added **automatically** during the first synchronization. There is no “Add equipment” button.

### Customize an equipment

Click the equipment card to open its configuration:

| Setting | Description |
|---------|-------------|
| **Name** | Rename the equipment (e.g. “Living room AC”) |
| **Parent object** | Attach it to a room or floor in your home automation |
| **Category** | Heating, comfort, etc. |
| **Enable** | Enable or disable the equipment |
| **Visible** | Show or hide the equipment on the dashboard |

### Device information

In the **Equipment** tab, a box displays information reported by Daikin:

| Information | Purpose |
|-------------|---------|
| **Model** | Your device reference |
| **Serial number** | Unique identifier |
| **Firmware version** | Embedded software version |
| **Error code** | Possible alert code — check your device manual or Daikin support if a code is displayed |

---

## Available commands

Commands are created automatically based on your device model. They are in the **Commands** tab of each equipment.

> **Note:** not all commands below are necessarily available on your device. It depends on the model.

### Main control

| What you want to do | Command in Jeedom |
|---------------------|-------------------|
| Turn on / off | **State** (or Marche) |
| Change mode (cool, heat, auto, dry, fan) | **Operation Mode** |
| Set desired temperature | **Temperature Control** |

### Ventilation

| What you want to do | Command in Jeedom |
|---------------------|-------------------|
| Choose ventilation mode | **Fan Current Mode** |
| Set fan speed | **Fan Fixed** |
| Orient airflow horizontally | **Fan Horizontal** (model-dependent) |
| Orient airflow vertically | **Fan Vertical** (model-dependent) |

### Special modes

| What you want to do | Command in Jeedom |
|---------------------|-------------------|
| Enable eco mode | **Eco Mode** |
| Enable powerful mode | **Powerful Mode** |
| Enable streamer mode (purification) | **Streamer Mode** |

### Information (read-only)

| What you want to view | Command in Jeedom |
|-----------------------|-------------------|
| Indoor temperature | **Room Temperature** |
| Outdoor temperature | **Outdoor Temperature** |
| Indoor humidity | **Room Humidity** |

### Energy consumption

| What you want to view | Command in Jeedom |
|-----------------------|-------------------|
| Heating consumption (day / week / month) | **Heating Consumption D/W/M** |
| Cooling consumption (day / week / month) | **Cooling Consumption D/W/M** |

Counters are updated automatically each day (around 11:58 PM by default).

### Schedules and advanced modes

| What you want to do | Command in Jeedom |
|---------------------|-------------------|
| Enable / disable an Onecta schedule | **Schedule** (model-dependent) |
| Enable holiday mode | **Preset Away** (model-dependent) |
| Start a firmware update | **Firmware Update** (if offered by Daikin) |

---

## Multi-zone devices

Some models, especially **Altherma** heat pumps, have several independent zones. In that case, you will see separate commands for each zone:

- **Zone 1:** main heating
- **Zone 2:** domestic hot water, or a second heating zone

Each zone has its own on/off, mode, and setpoint commands.

---

## Display on the dashboard

To control your devices from the Jeedom home screen:

1. Open the equipment and go to the **Commands** tab.
2. Make the commands you want to display **visible** (checkbox in the Options column).
3. Add them to your dashboard via the Jeedom design configurator.

Binary commands (on/off) appear as buttons. Temperature setpoints appear as sliders.

**Tip:** for daily use, make at least **State**, **Operation Mode**, and **Temperature Control** visible.

---

## Use in scenarios

Your Daikin devices can be integrated into any Jeedom scenario, like other equipment.

### Examples

**Summer comfort:**
> If living room temperature > 26°C → Turn on AC in cooling mode, setpoint 24°C

**Night mode:**
> At 10:00 PM → Enable Eco mode on all air conditioners

**Leaving on holiday:**
> When “Away” mode is enabled → Enable Preset Away on the heat pump

**Energy saving:**
> If nobody is home → Turn off air conditioning

**Coming home:**
> When geolocation detects return → Turn on AC, auto mode, setpoint 22°C

To create a scenario, go to **Tools → Scenarios** and use your Daikin equipment commands as actions or conditions.

---

## Edit commands

Commands are generated automatically by the plugin. You can:

- Rename a command
- Change its visibility
- Change its unit or icon

> **Warning:** avoid deleting automatically generated commands. They may be recreated during the next synchronization.

---

[Previous: Authentication]({{ site.baseurl }}/en_US/authentification.html) — [Next: Limits and best practices]({{ site.baseurl }}/en_US/quota-api.html)
