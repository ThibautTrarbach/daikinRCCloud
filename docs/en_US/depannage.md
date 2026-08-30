---
layout: default
title: Troubleshooting - Daikin ONECTA
---

# Troubleshooting

This page answers the most common problems encountered with the Daikin ONECTA plugin.

## My equipment does not appear

**Checks to perform in order:**

1. Is the **plugin service** running? (Tools → Health)
2. Is the **mqtt2** plugin installed, activated, and running?
3. Are **dependencies** installed? (“Reinstall dependencies” button)
4. Are your **Daikin credentials** correct? (test on the Onecta mobile app)
5. Are your devices visible in the **Daikin Onecta** app on your phone?

**Actions:**

- Restart the plugin service.
- Save the configuration again, then restart.
- Check the logs (see below).

## The plugin service does not start

**Common causes:**

| Cause | Solution |
|-------|----------|
| Dependencies not installed | Click “Reinstall dependencies” |
| Incorrect Daikin credentials | Check email/password or Client ID/Secret |
| mqtt2 not running | Start the mqtt2 plugin |

After fixing the issue, restart the plugin service.

## Daikin connection error

### Mobile App mode

- Check that you can log in to the Daikin Onecta app on your phone.
- If you changed your password, update it in the plugin configuration.
- Restart the service after any change.

### Developer Portal mode

- Check that the Client ID and Client Secret are correct.
- If Daikin invalidated your key, restart the connection procedure (see [Authentication]({{ site.baseurl }}/en_US/authentification.html)).
- Check that the authentication port (default: 8765) is not blocked.

## My commands do not respond

1. Is the equipment **enabled** in Jeedom?
2. Is an **error code** displayed on the equipment? (Equipment tab)
3. Is the plugin service still **running**?
4. Is the **daily quota exhausted**? (see [Limits and best practices]({{ site.baseurl }}/en_US/quota-api.html))

Try sending the same command from the Daikin Onecta app on your phone. If it does not work there either, the problem comes from the device or the Daikin cloud, not Jeedom.

## Few commands on my device

Commands depend on your device model. If you see few commands:

1. Check that **Unknown models** is enabled (Advanced configuration).
2. Wait a few minutes after starting the plugin — synchronization can take a moment.
3. Restart the plugin service.

If your model is very recent, report it on the [Jeedom Community](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55) to improve support.

## Repeated network errors

If the plugin shows connection errors to the Daikin cloud (timeouts, blocks):

1. Open **Plugins → Daikin ONECTA → Configuration → Advanced configuration**.
2. Switch **HTTP transport** from “Node.js” to “curl”.
3. Save and restart the service.

## View logs

To diagnose a problem:

1. Go to **Analysis → Logs** in Jeedom.
2. Filter by plugin: `daikinRCCloud`.
3. Look for error messages (in red).

## Ask for help

If you cannot find a solution, ask for help and provide:

| Information | Where to find it |
|-------------|------------------|
| Plugin version | Configuration → Information |
| Internal service version | Configuration → Information |
| Jeedom version | Jeedom home page |
| Connection mode used | Configuration → Authentication mode |
| Problem description | What you expected vs what happened |
| Log excerpt | Analysis → Logs, filter daikinRCCloud |

### Jeedom Community

- [Plugin discussion thread](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55)
- On Jeedom 4.4+, use the **Create Community post** button on the plugin page to pre-fill a help form.

### GitHub

To report a bug: [GitHub Issues](https://github.com/ThibautTrarbach/daikinRCCloud/issues)

---

[Previous: Limits and best practices]({{ site.baseurl }}/en_US/quota-api.html) — [Back to home]({{ site.baseurl }}/en_US/)
