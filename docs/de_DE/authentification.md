---
layout: default
title: Authentifizierung - Daikin ONECTA
---

# Authentifizierung

Damit Jeedom Ihre Daikin-Geräte steuern kann, muss das Plugin eine Verbindung zu Ihrem Daikin-ONECTA-Cloud-Konto herstellen. Zwei Verbindungsmodi sind verfügbar.

## Welchen Modus wählen?

| | Developer Portal | Mobile App |
|---|-------------------|-----------|
| **Empfohlen für** | Alle Benutzer | Fortgeschrittene Benutzer (Onecta-Zugangsdaten) |
| **Zugangsdaten** | Client ID + Client Secret | Onecta-E-Mail + Passwort |
| **Gleiches Konto wie Mobile App?** | Nein (separates Developer-Konto) | Ja |
| **Tageskontingent** | 200 Anfragen | 3000 Anfragen |
| **Echtzeit-Updates** | Nein | Ja |
| **Einrichtung** | OAuth-Verfahren | Einfach (2 Felder) |

> **Empfohlen:** Verwenden Sie den Modus **Developer Portal** mit einer auf dem [Daikin-Entwicklerportal](https://developer.cloud.daikineurope.com/) erstellten Anwendung.

---

## Mobile-App-Modus

Dies ist der einfachste Modus. Er verwendet dasselbe Konto wie die **Daikin Onecta**- oder **Daikin Residential Controller**-App auf Ihrem Smartphone.

### Konfiguration

1. Öffnen Sie **Plugins → Daikin ONECTA → Konfiguration**.
2. Wählen Sie **Mobile App**.
3. Geben Sie Ihre **Onecta-E-Mail** ein.
4. Geben Sie Ihr **Onecta-Passwort** ein.
5. Klicken Sie auf **Speichern**.
6. Starten oder starten Sie den Plugin-Dienst neu.

### Funktionsweise

- Die Verbindung erfolgt automatisch beim Start des Plugins.
- Ihr Passwort wird sicher in Jeedom gespeichert.
- Echtzeit-Updates sind standardmäßig aktiviert, wodurch das Plugin sehr reaktionsschnell ist, ohne viel Kontingent zu verbrauchen.

### Fehlerbehebung

- Prüfen Sie, ob Sie sich mit denselben Zugangsdaten in der Daikin-Onecta-App auf Ihrem Smartphone anmelden können.
- Wenn Sie Ihr Onecta-Passwort geändert haben, aktualisieren Sie es in der Plugin-Konfiguration.
- Starten Sie den Plugin-Dienst nach jeder Änderung neu.

---

## Developer-Portal-Modus

Dieser Modus ist für Benutzer, die eine Anwendung im [Daikin Developer Portal](https://developer.cloud.daikineurope.com/) erstellt haben. Er bietet ein begrenzteres Kontingent (200 Anfragen/Tag) und erfordert ein längeres Verbindungsverfahren.

### Erstkonfiguration

1. Öffnen Sie **Plugins → Daikin ONECTA → Konfiguration**.
2. Wählen Sie **Developer Portal (OAuth)**.
3. Geben Sie die **Client ID** und das **Client Secret** Ihrer Anwendung ein.
4. Klicken Sie auf **Speichern**.

### Anleitung für die erste Verbindung

#### Schritt 1: Developer-Konto erstellen

Gehen Sie zu [developer.cloud.daikineurope.com](https://developer.cloud.daikineurope.com/) und melden Sie sich an.

#### Schritt 2: Auf Ihre Anwendungen zugreifen

Klicken Sie oben rechts auf Ihre E-Mail-Adresse und wählen Sie **My Apps**.

#### Schritt 3: Anwendung erstellen

Klicken Sie auf **New App**, geben Sie einen Namen ein (z. B. „Jeedom") und bestätigen Sie.

#### Schritt 4: Zugangsdaten kopieren

Kopieren Sie die **Client ID** und das **Client Secret** in die Plugin-Konfiguration und speichern Sie.

#### Schritt 5: Plugin starten und URL abrufen

1. Starten Sie den Plugin-Dienst.
2. Öffnen Sie die Plugin-**Logs** (**Analyse → Logs**, Filter `daikinRCCloud`).
3. Kopieren Sie die in den Logs angezeigte **Authentifizierungs-URL**.

#### Schritt 6: Redirect-URL konfigurieren

1. Kehren Sie zum Daikin Developer Portal zurück.
2. Bearbeiten Sie Ihre Anwendung.
3. Fügen Sie die kopierte URL in das Feld **Redirect URI** ein.
4. Klicken Sie auf **Update**.

#### Schritt 7: Zugriff autorisieren

1. Öffnen Sie die Authentifizierungs-URL in einem Browser.
2. Akzeptieren Sie das Zertifikat, falls Ihr Browser es anzeigt.
3. Folgen Sie dem Daikin-Autorisierungsverfahren.

#### Schritt 8: Verifizieren

Eine Erfolgsmeldung bestätigt, dass die Verbindung hergestellt ist. Ihre Geräte sollten in Jeedom erscheinen.

### Fehlerbehebung

- Wenn Daikin Ihren API-Schlüssel ungültig macht, starten Sie das Verbindungsverfahren ab Schritt 5 erneut.
- Prüfen Sie, ob der Authentifizierungsport (Standard: 8765) nicht von einer Firewall blockiert wird.
- Siehe das [Jeedom-Community-Tutorial](https://community.jeedom.com/t/onecta-cloud-api-key-invalidation-action-required/127311/33?u=thibaut_t), wenn Ihr Schlüssel ungültig gemacht wurde.

---

[Vorherige: Konfiguration]({{ site.baseurl }}/de_DE/configuration.html) — [Weiter: Nutzung]({{ site.baseurl }}/de_DE/utilisation.html)
