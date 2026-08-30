---
layout: default
title: Fehlerbehebung - Daikin ONECTA
---

# Fehlerbehebung

Diese Seite beantwortet die häufigsten Probleme mit dem Daikin-ONECTA-Plugin.

## Meine Geräte erscheinen nicht

**Prüfungen in dieser Reihenfolge:**

1. Ist der **Plugin-Dienst** gestartet? (Werkzeuge → Gesundheit)
2. Ist das Plugin **mqtt2** installiert, aktiviert und gestartet?
3. Sind die **Abhängigkeiten** installiert? (Schaltfläche „Abhängigkeiten neu installieren“)
4. Sind Ihre **Daikin-Zugangsdaten** korrekt? (Test in der Onecta-Mobile App)
5. Sind Ihre Geräte in der **Daikin-Onecta**-App auf Ihrem Smartphone sichtbar?

**Maßnahmen:**

- Starten Sie den Plugin-Dienst neu.
- Speichern Sie die Konfiguration erneut und starten Sie neu.
- Prüfen Sie die Logs (siehe unten).

## Der Plugin-Dienst startet nicht

**Häufige Ursachen:**

| Ursache | Lösung |
|---------|--------|
| Abhängigkeiten nicht installiert | Klicken Sie auf „Abhängigkeiten neu installieren“ |
| Falsche Daikin-Zugangsdaten | Prüfen Sie E-Mail/Passwort oder Client ID/Secret |
| mqtt2 nicht gestartet | Starten Sie das Plugin mqtt2 |

Starten Sie nach der Korrektur den Plugin-Dienst neu.

## Verbindungsfehler zu Daikin

### Modus Mobile App

- Prüfen Sie, ob Sie sich in der Daikin-Onecta-App auf Ihrem Smartphone anmelden können.
- Wenn Sie Ihr Passwort geändert haben, aktualisieren Sie es in der Plugin-Konfiguration.
- Starten Sie den Dienst nach der Änderung neu.

### Modus Developer Portal

- Prüfen Sie, ob Client ID und Client Secret korrekt sind.
- Wenn Daikin Ihren Schlüssel ungültig gemacht hat, wiederholen Sie das Verbindungsverfahren (siehe [Authentifizierung]({{ site.baseurl }}/de_DE/authentification.html)).
- Prüfen Sie, ob der Authentifizierungsport (Standard: 8765) nicht blockiert ist.

## Meine Befehle reagieren nicht

1. Ist das Gerät in Jeedom **aktiviert**?
2. Wird ein **Fehlercode** auf dem Gerät angezeigt? (Tab Gerät)
3. Läuft der Plugin-Dienst noch?
4. Ist das **Tagesquota** aufgebraucht? (siehe [Limits und Best Practices]({{ site.baseurl }}/de_DE/quota-api.html))

Versuchen Sie denselben Befehl in der Daikin-Onecta-App auf Ihrem Smartphone. Wenn er dort auch nicht funktioniert, liegt das Problem am Gerät oder an der Daikin-Cloud, nicht an Jeedom.

## Wenige Befehle auf meinem Gerät

Die Befehle hängen von Ihrem Gerätemodell ab. Wenn Sie wenige Befehle sehen:

1. Prüfen Sie, ob **Unbekannte Modelle** aktiviert ist (Erweiterte Konfiguration).
2. Warten Sie einige Minuten nach dem Plugin-Start — die Synchronisation kann dauern.
3. Starten Sie den Plugin-Dienst neu.

Wenn Ihr Modell sehr neu ist, melden Sie es im [Jeedom-Forum](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55), um die Unterstützung zu verbessern.

## Wiederholte Netzwerkfehler

Wenn das Plugin Verbindungsfehler zur Daikin-Cloud anzeigt (Timeouts, Blockaden):

1. Öffnen Sie **Plugins → Daikin ONECTA → Konfiguration → Erweiterte Konfiguration**.
2. Wechseln Sie **HTTP-Transport** von „Node.js“ zu „curl“.
3. Speichern Sie und starten Sie den Dienst neu.

## Logs einsehen

Zur Diagnose:

1. Gehen Sie in Jeedom zu **Analyse → Logs**.
2. Filtern Sie nach Plugin: `daikinRCCloud`.
3. Suchen Sie nach Fehlermeldungen (rot).

## Hilfe anfordern

Wenn Sie keine Lösung finden, bitten Sie um Hilfe und geben Sie an:

| Information | Wo finden |
|-------------|-----------|
| Plugin-Version | Konfiguration → Informationen |
| Version des internen Dienstes | Konfiguration → Informationen |
| Jeedom-Version | Jeedom-Startseite |
| Verwendeter Verbindungsmodus | Konfiguration → Authentifizierungsmodus |
| Problembeschreibung | Was Sie erwartet haben vs. was passiert |
| Log-Auszug | Analyse → Logs, Filter daikinRCCloud |

### Jeedom-Forum

- [Diskussionsthread des Plugins](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55)
- Ab Jeedom 4.4+: Nutzen Sie die Schaltfläche **Community-Beitrag erstellen** auf der Plugin-Seite für ein vorausgefülltes Hilfeformular.

### GitHub

Bug melden: [GitHub Issues](https://github.com/ThibautTrarbach/daikinRCCloud/issues)

---

[Vorherige: Limits und Best Practices]({{ site.baseurl }}/de_DE/quota-api.html) — [Zurück zur Startseite]({{ site.baseurl }}/de_DE/)
