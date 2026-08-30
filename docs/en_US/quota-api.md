---
layout: default
title: Limits and best practices - Daikin ONECTA
---

# Limits and best practices

Daikin limits how many times Jeedom can query the cloud per day. This page explains why this limit exists and how to optimize it.

## Why a limit?

To work, the plugin must regularly ask Daikin for your device status (temperature, mode, on/off, etc.). Each request counts toward a **daily quota** set by Daikin. This quota resets every day at midnight.

This is not a plugin limitation, but a rule imposed by the Daikin ONECTA cloud service.

## Quota by connection mode

| Connection mode | Allowed requests per day |
|-----------------|-------------------------|
| **Developer Portal** | 200 |
| **Mobile App** | 3000 |

## What consumes quota?

| Action | Consumption |
|--------|-------------|
| Scheduled sync (every 15 min by default) | 1 request (for all your devices at once) |
| Command (change temperature, turn on, etc.) | 1 request per change |
| Verification after a command | 1 request (depending on settings) |
| kWh counter update (each evening) | 1 request |
| Real-time update (WebSocket, Mobile App mode) | **0** requests |

**Important:** one sync queries **all** your devices at once. Having 1 or 5 air conditioners consumes the same number of requests.

## Estimate shown in configuration

On the configuration page (advanced section), the **Scheduled requests/day** field estimates how many GET requests the daemon will schedule each day, based on your day/night intervals, authentication mode, and WebSocket.

| Configuration | Detail | Scheduled total |
|---------------|--------|-----------------|
| Developer Portal, defaults (15 min day, 30 min night, night 10 PM→7 AM) | 60 day polls + 18 night polls + 1 energy stats | **~79 GET/day** |
| Mobile App + WebSocket enabled, same intervals | Safety net 30/60 min: 30 + 9 + 1 | **~40 GET/day** |

These figures do not include your commands, post-action refresh (depending on settings), or the daemon startup GET (+1 on each restart).

## Optimization tips

### Developer Portal mode (200/day)

1. **Keep default settings** — they are designed for a good balance with the 200 requests/day quota.
2. **Do not reduce sync intervals** below 15 minutes.
3. **Avoid scenarios** that send many commands in quick succession.

### If you use Mobile App mode (3000/day)

- **Keep WebSocket enabled** — state changes arrive in real time without consuming quota.
- You can slightly increase sync intervals while staying responsive thanks to WebSocket.

### If you have many devices and automations

- Slightly increase sync intervals (e.g. 20 min day, 45 min night).
- Hybrid mode (default) for post-command behavior is the most economical.

## What happens when quota is reached?

The plugin automatically slows sync when quota is almost exhausted. Your commands still work, but state updates may be less frequent until the next day.

In Developer Portal mode, exceeding quota can block all requests until midnight.

---

[Previous: Usage]({{ site.baseurl }}/en_US/utilisation.html) — [Next: Troubleshooting]({{ site.baseurl }}/en_US/depannage.html)
