---
layout: default
title: Installation - Daikin ONECTA
---

# Installation

This guide walks you through installing the plugin and making your Daikin devices appear in Jeedom.

## Before you start

Make sure you have:

- Jeedom **4.4** or newer
- A **Daikin Onecta account** (the same one as on the mobile app)
- Your Daikin devices already configured and visible in the Onecta app

## Step 1: Install the plugin

1. Open **Plugins → Plugin management** in Jeedom.
2. Search for **Daikin ONECTA**.
3. Click **Install**, then **Activate**.

## Step 2: Install the mqtt2 plugin

The **mqtt2** plugin is **mandatory**. It acts as an internal relay between the Daikin plugin and your devices. In most cases, you do not need any special configuration inside it.

1. Install the **mqtt2** plugin from the Jeedom market (if not already installed).
2. Activate it.
3. Check in **Tools → Health** that mqtt2 is running.

## Step 3: Install dependencies

The plugin needs additional components to work. Jeedom installs them automatically:

1. Go to the **Daikin ONECTA** plugin page.
2. Click **Reinstall dependencies** (or use the dedicated button in plugin management).
3. Wait for the installation to finish (this may take a few minutes).

> **Note:** after each plugin update, rerun dependency installation if Jeedom asks you to.

## Step 4: Configure the Daikin connection

1. Open **Plugins → Daikin ONECTA → Configuration**.
2. Choose **Mobile App (recommended)** mode.
3. Enter the **email** and **password** of your Daikin Onecta account (the same as on the mobile app).
4. Click **Save**.

For more details on connection modes, see the [Authentication]({{ site.baseurl }}/en_US/authentification.html) page.

## Step 5: Start the plugin service

1. Go to **Tools → Health** (or the plugin page).
2. Start the Daikin ONECTA plugin **daemon**.
3. Check that the status shows **Running**.

## Step 6: Check your devices

1. Return to **Plugins → Daikin ONECTA**.
2. Your Daikin devices should appear under **My devices**.

If no device appears, see the [Troubleshooting]({{ site.baseurl }}/en_US/depannage.html) page.

## What's next?

- [Configure plugin settings]({{ site.baseurl }}/en_US/configuration.html)
- [Learn how to use your devices]({{ site.baseurl }}/en_US/utilisation.html)

---

[Previous: Home]({{ site.baseurl }}/en_US/) — [Next: Configuration]({{ site.baseurl }}/en_US/configuration.html)
