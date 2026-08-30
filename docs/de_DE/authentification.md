---
layout: default
title: Authentifizierung - Daikin ONECTA
---

# Authentifizierung

Damit Jeedom Ihre Daikin-Geräte steuern kann, muss sich das Plugin mit Ihrem Daikin-ONECTA-Cloud-Konto verbinden. Zwei Verbindungsmodi stehen zur Verfügung.

## Welchen Modus wählen?

| | Mobile App | Developer Portal |
|---|-----------|------------------|
| **Empfohlen für** | Alle Benutzer | Entwickler, fortgeschrittene Tests |
| **Zugangsdaten** | Onecta-E-Mail + Passwort | Client ID + Client Secret |
| **Gleiches Konto wie Mobile App?** | Ja | Nein (separates Entwicklerkonto) |
| **Tagesquota** | 3000 Abfragen | 200 Abfragen |
| **Echtzeit-Updates** | Ja | Nein |
| **Konfiguration** | Einfach (2 Felder) | Komplex (OAuth-Verfahren) |

> **Empfohlen:** Verwenden Sie den Modus **Mobile App** mit den Zugangsdaten Ihrer Daikin-Onecta-App.

---

## Modus Mobile App (empfohlen)

Das ist der einfachste Modus. Er nutzt dasselbe Konto wie die **Daikin Onecta**- oder **Daikin Residential Controller**-App auf Ihrem Smartphone.

### Konfiguration

1. Öffnen Sie **Plugins → Daikin ONECTA → Konfiguration**.
2. Wählen Sie **Mobile App (empfohlen)**.
3. Geben Sie Ihre **Onecta-E-Mail** ein.
4. Geben Sie Ihr **Onecta-Passwort** ein.
5. Klicken Sie auf **Speichern**.
6. Starten oder starten Sie den Plugin-Dienst neu.

### Funktionsweise

- Die Verbindung erfolgt automatisch beim Start des Plugins.
- Ihr Passwort wird sicher in Jeedom gespeichert.
- Echtzeit-Updates sind standardmäßig aktiviert — das Plugin reagiert sehr schnell, ohne viel Quota zu verbrauchen.

### Bei Problemen

- Prüfen Sie, ob Sie sich mit denselben Zugangsdaten in der Daikin-Onecta-App auf Ihrem Smartphone anmelden können.
- Wenn Sie Ihr Onecta-Passwort geändert haben, aktualisieren Sie es in der Plugin-Konfiguration.
- Starten Sie den Plugin-Dienst nach jeder Änderung neu.

---

## Modus Developer Portal

Dieser Modus richtet sich an Nutzer, die eine Anwendung im [Daikin-Entwicklerportal](https://developer.cloud.daikineurope.com/) erstellt haben. Er bietet ein geringeres Quota (200 Abfragen/Tag) und erfordert ein längeres Verbindungsverfahren.

### Erstkonfiguration

1. Öffnen Sie **Plugins → Daikin ONECTA → Konfiguration**.
2. Wählen Sie **Developer Portal (OAuth)**.
3. Geben Sie die **Client ID** und das **Client Secret** Ihrer Anwendung ein.
4. Klicken Sie auf **Speichern**.

### Anleitung für die erste Verbindung

#### Schritt 1: Entwicklerkonto erstellen

Gehen Sie zu [developer.cloud.daikineurope.com](https://developer.cloud.daikineurope.com/) und melden Sie sich an.

#### Schritt 2: Zu Ihren Anwendungen

Klicken Sie oben rechts auf Ihre E-Mail-Adresse und wählen Sie **My Apps**.

#### Schritt 3: Anwendung erstellen

Klicken Sie auf **New App**, geben Sie einen Namen ein (z. B. „Jeedom“) und bestätigen Sie.

#### Schritt 4: Zugangsdaten kopieren

Kopieren Sie die **Client ID** und das **Client Secret** in die Plugin-Konfiguration und speichern Sie.

#### Schritt 5: Plugin starten und URL abrufen

1. Starten Sie den Plugin-Dienst.
2. Öffnen Sie die **Logs** des Plugins (**Analyse → Logs**, Filter `daikinRCCloud`).
3. Kopieren Sie die in den Logs angezeigte **Authentifizierungs-URL**.

#### Schritt 6: Redirect-URL konfigurieren

1. Kehren Sie zum Daikin-Entwicklerportal zurück.
2. Bearbeiten Sie Ihre Anwendung.
3. Fügen Sie die kopierte URL in das Feld **Redirect URI** ein.
4. Klicken Sie auf **Update**.

#### Schritt 7: Zugriff autorisieren

1. Öffnen Sie die Authentifizierungs-URL in einem Browser.
2. Akzeptieren Sie das Zertifikat, falls Ihr Browser es anzeigt.
3. Folgen Sie dem Daikin-Autorisierungsverfahren.

#### Schritt 8: Prüfen

Eine Erfolgsmeldung bestätigt, dass die Verbindung hergestellt ist. Ihre Geräte sollten in Jeedom erscheinen.

### Bei Problemen

- Wenn Daikin Ihren API-Schlüssel ungültig macht, starten Sie das Verbindungsverfahren ab Schritt 5 erneut.
- Prüfen Sie, ob der Authentifizierungsport (Standard: 8765) nicht von einer Firewall blockiert wird.
- Lesen Sie bei Schlüsselinvalidierung das [Tutorial in der Jeedom-Community](https://community.jeedom.com/t/onecta-cloud-api-key-invalidation-action-required/127311/33?u=thibaut_t).

---

[Vorherige: Konfiguration]({{ site.baseurl }}/de_DE/configuration.html) — [Nächste: Nutzung]({{ site.baseurl }}/de_DE/utilisation.html)
