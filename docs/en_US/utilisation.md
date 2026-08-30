---
layout: default
title: Usage - Daikin ONECTA
---

# Usage

This page explains how to use your Daikin devices day to day in Jeedom: find your equipment, understand available commands, display them on the dashboard, and integrate them into scenarios.

## Find your devices

1. Open **Plugins → Daikin ONECTA**.
2. Your devices appear under **My devices**, as cards.

They are added **automatically** during the first sync. There is no "Add device" button.

### Customize a device

Click the device card to access its configuration:

| Setting | Description |
|---------|-------------|
| **Name** | Rename the device (e.g. "Living room AC") |
| **Parent object** | Attach it to a room or floor in your home automation |
| **Category** | Heating, comfort, etc. |
| **Enable** | Enable or disable the device |
| **Visible** | Show or hide the device on the dashboard |

### Device information

In the **Device** tab, a box shows information reported by Daikin:

| Information | Use |
|-------------|-----|
| **Model** | Your device reference |
| **Serial number** | Unique identifier |
| **Firmware version** | Embedded software version |
| **Error code** | Possible alert code — check your device manual or Daikin support if a code is shown |

---

## Available commands

Commands are created automatically based on your device model. They are in the **Commands** tab of each device.

> **Note:** not all commands below are necessarily available on your device. It depends on the model.

### Main control

| What you want to do | Command in Jeedom |
|---------------------|-------------------|
| Turn on / off | **State** (or Power) |
| Change mode (cool, heat, auto, dry, fan) | **Operation Mode** |
| Set desired temperature | **Temperature Control** |

### Ventilation

| What you want to do | Command in Jeedom |
|---------------------|-------------------|
| Choose ventilation mode | **Fan Current Mode** |
| Set fan speed | **Fan Fixed** |
| Orient airflow horizontally | **Fan Horizontal** (model dependent) |
| Orient airflow vertically | **Fan Vertical** (model dependent) |

### Special modes

| What you want to do | Command in Jeedom |
|---------------------|-------------------|
| Enable eco mode | **Eco Mode** |
| Enable powerful mode | **Powerful Mode** |
| Enable streamer mode (purification) | **Streamer Mode** |

### Information (read-only)

| What you want to check | Command in Jeedom |
|------------------------|-------------------|
| Room temperature | **Room Temperature** |
| Outdoor temperature | **Outdoor Temperature** |
| Room humidity | **Room Humidity** |

### Energy consumption

| What you want to check | Command in Jeedom |
|------------------------|-------------------|
| Heating consumption (day / week / month) | **Heating Consumption D/W/M** |
| Cooling consumption (day / week / month) | **Cooling Consumption D/W/M** |

Counters are updated automatically each day (around 11:58 PM by default).

### Schedules and advanced modes

| What you want to do | Command in Jeedom |
|---------------------|-------------------|
| Enable / disable an Onecta schedule | **Schedule** (model dependent) |
| Enable away mode | **Preset Away** (model dependent) |
| Start a firmware update | **Firmware Update** (if offered by Daikin) |

---

## Multi-zone devices

Some models, especially **Altherma** heat pumps, have several independent zones. In that case, you will see separate commands for each zone:

- **Zone 1:** main heating
- **Zone 2:** domestic hot water, or second heating zone

Each zone has its own power, mode, and setpoint commands.

---

## Display on the dashboard

To control your devices from the Jeedom home screen:

1. Open the device and go to the **Commands** tab.
2. Make **visible** the commands you want to display (checkbox in the Options column).
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

**Vacation:**
> When "Away" mode is active → Enable Preset Away on the heat pump

**Energy saving:**
> If nobody is home → Turn off air conditioning

**Return home:**
> When geolocation detects return → Turn on AC, auto mode, setpoint 22°C

To create a scenario, go to **Tools → Scenarios** and use your Daikin device commands as actions or conditions.

---

## Modify commands

Commands are generated automatically by the plugin. You can:

- Rename a command
- Change its visibility
- Modify its unit or icon

> **Warning:** avoid deleting automatically generated commands. They may be recreated on the next sync.

---

[Previous: Authentication]({{ site.baseurl }}/en_US/authentification.html) — [Next: Limits and best practices]({{ site.baseurl }}/en_US/quota-api.html)
