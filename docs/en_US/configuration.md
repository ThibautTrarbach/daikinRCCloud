---
layout: default
title: Configuration - Daikin ONECTA
---

# Configuration

The configuration page is located in **Plugins → Daikin ONECTA → Configuration**.

Most users only need to change the **Daikin connection** section. Other settings are available via the **Advanced configuration** checkbox.

---

## Daikin connection

This is the most important section. It links Jeedom to your Daikin account.

### Authentication mode

Two modes are available:

| Mode | For whom? | Daily quota |
|------|-----------|-------------|
| **Mobile App** (recommended) | Users with the Daikin Onecta app | 3000 requests/day |
| **Developer Portal** | Advanced users who created an app on the Daikin developer portal | 200 requests/day |

> **Recommended:** choose **Mobile App** and use the same credentials as on the Daikin Onecta app on your phone.

See the [Authentication]({{ site.baseurl }}/en_US/authentification.html) page for details on each mode.

### Credentials

Depending on the chosen mode:

- **Mobile App:** enter your **Onecta email** and **Onecta password**.
- **Developer Portal:** enter the **Client ID** and **Client Secret** of your Daikin Developer application.

### Daily API quota

This field displays the maximum number of cloud requests allowed per day according to your connection mode. It is calculated automatically and cannot be edited.

To understand what this means in practice, see [Limits and best practices]({{ site.baseurl }}/en_US/quota-api.html).

### Versions

Plugin and internal service versions are displayed read-only. Include them when asking for help on the forum.

---

## Advanced configuration

Check **Advanced configuration** to show additional settings. **Most users can leave the default values.**

### Update frequency

These settings determine how often Jeedom queries the Daikin cloud for your device status.

| Setting | Default | Description |
|---------|--------|-------------|
| **Daytime interval** | 15 min | Check frequency between morning and evening |
| **Nighttime interval** | 30 min | Check frequency during the night (saves quota) |
| **Night start** | 10 PM | Time when the night period begins |
| **Night end** | 7 AM | Time when the night period ends |

> **Tip:** with Mobile App mode and real-time updates enabled, you can increase these intervals without losing responsiveness.

The **Scheduled requests/day** field estimates how many GET requests the daemon will schedule each day (polling + energy stats), based on authentication mode and WebSocket. It updates automatically when you change settings. Commands and post-action refresh add to this estimate.

### Behavior after a command

When you trigger a command (change temperature, turn on AC, etc.), the plugin can react in three ways:

| Mode | Behavior | When to use |
|------|----------|-------------|
| **1 — Deferred full refresh** | Waits then checks actual state with Daikin | If you want systematic cloud confirmation |
| **2 — Immediate update** | Updates Jeedom right away without checking Daikin | To save quota if responsiveness is enough |
| **3 — Hybrid** (default) | Immediate update + Daikin check after a delay | **Recommended** — good balance of responsiveness / reliability |

**Refresh delay:** in modes 1 and 3, wait time before checking with Daikin (default: 60 seconds). Keep this delay if your devices take a moment to react.

**Verification strategy:**

| Strategy | Description |
|----------|-------------|
| **Merge with sync** (default) | If a scheduled sync is coming soon, the plugin waits instead of making an extra request |
| **Dedicated verification** | The plugin queries Daikin specifically after each command |
| **No verification** | No cloud request after a command |

**Energy stats refresh:** daily time (default 11:58 PM) when the plugin updates kWh consumption counters.

### Automatic model support

| Option | Default | Description |
|--------|--------|-------------|
| **Unknown models** | Enabled | Automatically control Daikin models not explicitly listed |
| **Read-only sensors** | Enabled | Shows outdoor temperatures, diagnostics, etc. |
| **Publish on change** | Enabled | Updates Jeedom only when a value actually changed |

Disable **Unknown models** only if you encounter abnormal behavior with an unrecognized device.

### Additional options

| Option | Default | Description |
|--------|--------|-------------|
| **Real-time WebSocket** | Enabled | Receives state changes live (Mobile App mode only) |
| **Authentication port** | 8765 | Local port for Developer Portal connection only |
| **MQTT prefix** | daikinToMQTT | Leave default unless conflict with another plugin |

---

## Expert settings

> **Only change these settings if support asks you to or if you know why.**

### HTTP transport

If the plugin cannot communicate with Daikin (repeated network errors, firewall blocking), switch from **Node.js** to **curl**. This uses another network engine that sometimes bypasses blocks.

### Dependency configuration

Lets you choose which version of the plugin's internal service is installed (branch or exact version). **Leave default values** (`release-beta`) unless support instructs otherwise.

After any change, rerun dependency installation.

---

[Previous: Installation]({{ site.baseurl }}/en_US/installation.html) — [Next: Authentication]({{ site.baseurl }}/en_US/authentification.html)
