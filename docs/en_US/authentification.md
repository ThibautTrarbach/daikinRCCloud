---
layout: default
title: Authentication - Daikin ONECTA
---

# Authentication

For Jeedom to control your Daikin devices, the plugin must connect to your Daikin ONECTA cloud account. Two connection modes are available.

## Which mode to choose?

| | Mobile App | Developer Portal |
|---|-----------|------------------|
| **Recommended for** | All users | Developers, advanced testing |
| **Credentials** | Onecta email + password | Client ID + Client Secret |
| **Same account as mobile app?** | Yes | No (separate developer account) |
| **Daily quota** | 3000 requests | 200 requests |
| **Real-time updates** | Yes | No |
| **Setup** | Simple (2 fields) | Complex (OAuth procedure) |

> **Recommended:** use **Mobile App** mode with your Daikin Onecta app credentials.

---

## Mobile App mode (recommended)

This is the simplest mode. It uses the same account as the **Daikin Onecta** or **Daikin Residential Controller** app on your phone.

### Configuration

1. Open **Plugins → Daikin ONECTA → Configuration**.
2. Select **Mobile App (recommended)**.
3. Enter your **Onecta email**.
4. Enter your **Onecta password**.
5. Click **Save**.
6. Start or restart the plugin service.

### How it works

- Connection happens automatically when the plugin starts.
- Your password is stored securely in Jeedom.
- Real-time updates are enabled by default, making the plugin very responsive without consuming much quota.

### Troubleshooting

- Verify you can log in to the Daikin Onecta app on your phone with the same credentials.
- If you changed your Onecta password, update it in the plugin configuration.
- Restart the plugin service after any change.

---

## Developer Portal mode

This mode is for users who created an application on the [Daikin developer portal](https://developer.cloud.daikineurope.com/). It offers a more limited quota (200 requests/day) and requires a longer connection procedure.

### Initial configuration

1. Open **Plugins → Daikin ONECTA → Configuration**.
2. Select **Developer Portal (OAuth)**.
3. Enter the **Client ID** and **Client Secret** of your application.
4. Click **Save**.

### First connection tutorial

#### Step 1: Create a developer account

Go to [developer.cloud.daikineurope.com](https://developer.cloud.daikineurope.com/) and sign in.

#### Step 2: Access your applications

Click your email address at the top right, then choose **My Apps**.

#### Step 3: Create an application

Click **New App**, give it a name (e.g. "Jeedom") and confirm.

#### Step 4: Copy credentials

Copy the **Client ID** and **Client Secret** into the plugin configuration, then save.

#### Step 5: Start the plugin and get the URL

1. Start the plugin service.
2. Open the plugin **logs** (**Analysis → Logs**, filter `daikinRCCloud`).
3. Copy the **authentication URL** shown in the logs.

#### Step 6: Configure the redirect URL

1. Return to the Daikin developer portal.
2. Edit your application.
3. Paste the copied URL in the **Redirect URI** field.
4. Click **Update**.

#### Step 7: Authorize access

1. Open the authentication URL in a browser.
2. Accept the certificate if your browser shows it.
3. Follow the Daikin authorization procedure.

#### Step 8: Verify

A success message confirms the connection is established. Your devices should appear in Jeedom.

### Troubleshooting

- If Daikin invalidates your API key, restart the connection procedure from step 5.
- Verify the authentication port (default: 8765) is not blocked by a firewall.
- See the [Jeedom community tutorial](https://community.jeedom.com/t/onecta-cloud-api-key-invalidation-action-required/127311/33?u=thibaut_t) if your key was invalidated.

---

[Previous: Configuration]({{ site.baseurl }}/en_US/configuration.html) — [Next: Usage]({{ site.baseurl }}/en_US/utilisation.html)
