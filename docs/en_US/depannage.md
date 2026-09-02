---
layout: default
title: Troubleshooting - Daikin ONECTA
---

# Troubleshooting

This page answers the most common issues with the Daikin ONECTA plugin.

## My devices do not appear

**Checks to perform in order:**

1. Is the **plugin service** running? (Tools → Health)
2. Is the **mqtt2** plugin installed, active, and running?
3. Are **dependencies** installed? ("Reinstall dependencies" button)
4. Are your **Daikin credentials** correct? (test on the Onecta mobile app)
5. Are your devices visible in the **Daikin Onecta** app on your phone?

**Actions:**

- Restart the plugin service.
- Save the configuration again, then restart.
- Check the logs (see below).

## The plugin service won't start

**Common causes:**

| Cause | Solution |
|-------|----------|
| Dependencies not installed | Click "Reinstall dependencies" |
| Incorrect Daikin credentials | Check email/password or Client ID/Secret |
| mqtt2 not running | Start the mqtt2 plugin |

After fixing, restart the plugin service.

## Dependency installation failure

**Common symptoms in the `daikinRCCloud_packages` log:**

- `could not read Username for 'https://github.com'`
- `main.js is missing`
- `daikintomqtt: No such file or directory`

**Important points:**

- A manual SSH clone may succeed while "Reinstall dependencies" fails: the automated script deletes `daikintomqtt` on every run and re-downloads the daemon.
- The issue may affect **all branches** (`release-beta`, `release-alpha`, etc.) — it is usually not a wrong branch choice.
- Recommended branch: **`release-beta`** (unless support instructs otherwise).

**Checks:**

1. Open **Analysis → Logs → daikinRCCloud_packages** to see whether git clone or tarball fallback succeeded.
2. Verify the file exists: `/var/www/html/plugins/daikinRCCloud/resources/daikintomqtt/main.js`
3. Update the plugin to the latest version (fixed install scripts), then click "Reinstall dependencies" again.

**Network test from the Jeedom box (SSH):**

```bash
cd /var/www/html/plugins/daikinRCCloud/resources
sudo env GIT_TERMINAL_PROMPT=0 git -c credential.helper= -c http.version=HTTP/1.1 clone --depth 1 -b release-beta https://github.com/ThibautTrarbach/daikintomqtt.git daikintomqtt-test
ls daikintomqtt-test/main.js
sudo rm -rf daikintomqtt-test
```

If this test passes but the UI still fails, update the plugin: recent `pre_install.sh` / `post_install.sh` scripts apply these safeguards automatically.

## Daikin connection error

### Mobile App mode

- Verify you can log in to the Daikin Onecta app on your phone.
- If you changed your password, update it in the plugin configuration.
- Restart the service after any change.

### Developer Portal mode

- Verify Client ID and Client Secret are correct.
- If Daikin invalidated your key, restart the connection procedure (see [Authentication]({{ site.baseurl }}/en_US/authentification.html)).
- Verify the authentication port (default: 8765) is not blocked.

## My commands don't respond

1. Is the device **enabled** in Jeedom?
2. Is there an **error code** shown on the device? (Device tab)
3. Is the plugin service still **running**?
4. Is the **daily quota exhausted**? (see [Limits and best practices]({{ site.baseurl }}/en_US/quota-api.html))

Try sending the same command from the Daikin Onecta app on your phone. If it doesn't work there either, the issue is with the device or Daikin cloud, not Jeedom.

## Few commands on my device

Commands depend on your device model. If you see few commands:

1. Verify **Unknown models** is enabled (Advanced configuration).
2. Wait a few minutes after starting the plugin — sync may take a moment.
3. Restart the plugin service.

If your model is very recent, report it on the [Jeedom forum](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55) to improve support.

## Repeated network errors

If the plugin shows cloud connection errors (timeouts, blocks):

1. Open **Plugins → Daikin ONECTA → Configuration → Advanced configuration**.
2. Change **HTTP transport** from "Node.js" to "curl".
3. Save and restart the service.

## Check logs

To diagnose an issue:

1. Go to **Analysis → Logs** in Jeedom.
2. Filter by plugin: `daikinRCCloud`.
3. Look for error messages (in red).

## Ask for help

If you cannot find a solution, ask for help and include:

| Information | Where to find it |
|-------------|------------------|
| Plugin version | Configuration → Information |
| Internal service version | Configuration → Information |
| Jeedom version | Jeedom home page |
| Connection mode used | Configuration → Authentication mode |
| Problem description | What you expected vs what happened |
| Log excerpt | Analysis → Logs, filter daikinRCCloud |

### Jeedom forum

- [Plugin discussion thread](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55)
- On Jeedom 4.4+, use the **Create Community post** button on the plugin page to pre-fill a help form.

### GitHub

To report a bug: [GitHub Issues](https://github.com/ThibautTrarbach/daikinRCCloud/issues)

---

[Previous: Limits and best practices]({{ site.baseurl }}/en_US/quota-api.html) — [Back to home]({{ site.baseurl }}/en_US/)
