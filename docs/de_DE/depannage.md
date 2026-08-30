---
layout: default
title: Fehlerbehebung - Daikin ONECTA
---

# Fehlerbehebung

Diese Seite beantwortet die häufigsten Probleme mit dem Daikin-ONECTA-Plugin.

## Meine Geräte erscheinen nicht

**Prüfungen in dieser Reihenfolge:**

1. Läuft der **Plugin-Dienst**? (Werkzeuge → Gesundheit)
2. Ist das **mqtt2**-Plugin installiert, aktiv und läuft es?
3. Sind **Abhängigkeiten** installiert? (Schaltfläche „Abhängigkeiten neu installieren")
4. Sind Ihre **Daikin-Zugangsdaten** korrekt? (Test in der Onecta Mobile App)
5. Sind Ihre Geräte in der **Daikin Onecta**-App auf Ihrem Smartphone sichtbar?

**Maßnahmen:**

- Plugin-Dienst neu starten.
- Konfiguration erneut speichern, dann neu starten.
- Logs prüfen (siehe unten).

## Der Plugin-Dienst startet nicht

**Häufige Ursachen:**

| Ursache | Lösung |
|-------|----------|
| Abhängigkeiten nicht installiert | Auf „Abhängigkeiten neu installieren" klicken |
| Falsche Daikin-Zugangsdaten | E-Mail/Passwort oder Client ID/Secret prüfen |
| mqtt2 läuft nicht | mqtt2-Plugin starten |

Nach der Behebung den Plugin-Dienst neu starten.

## Daikin-Verbindungsfehler

### Mobile-App-Modus

- Prüfen Sie, ob Sie sich in der Daikin-Onecta-App auf Ihrem Smartphone anmelden können.
- Wenn Sie Ihr Passwort geändert haben, aktualisieren Sie es in der Plugin-Konfiguration.
- Dienst nach jeder Änderung neu starten.

### Developer-Portal-Modus

- Prüfen Sie, ob Client ID und Client Secret korrekt sind.
- Wenn Daikin Ihren Schlüssel ungültig gemacht hat, starten Sie das Verbindungsverfahren erneut (siehe [Authentifizierung]({{ site.baseurl }}/de_DE/authentification.html)).
- Prüfen Sie, ob der Authentifizierungsport (Standard: 8765) nicht blockiert wird.

## Meine Befehle reagieren nicht

1. Ist das Gerät in Jeedom **aktiviert**?
2. Wird ein **Fehlercode** am Gerät angezeigt? (Tab Gerät)
3. Läuft der Plugin-Dienst noch?
4. Ist das **Tageskontingent erschöpft**? (siehe [Limits und Best Practices]({{ site.baseurl }}/de_DE/quota-api.html))

Versuchen Sie, denselben Befehl über die Daikin-Onecta-App auf Ihrem Smartphone zu senden. Wenn es dort auch nicht funktioniert, liegt das Problem beim Gerät oder der Daikin-Cloud, nicht bei Jeedom.

## Wenige Befehle auf meinem Gerät

Befehle hängen von Ihrem Gerätemodell ab. Wenn Sie wenige Befehle sehen:

1. Prüfen Sie, ob **Unbekannte Modelle** aktiviert ist (Erweiterte Konfiguration).
2. Warten Sie einige Minuten nach dem Start des Plugins — die Synchronisation kann einen Moment dauern.
3. Plugin-Dienst neu starten.

Wenn Ihr Modell sehr neu ist, melden Sie es im [Jeedom-Forum](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55), um die Unterstützung zu verbessern.

## Wiederholte Netzwerkfehler

Wenn das Plugin Cloud-Verbindungsfehler anzeigt (Timeouts, Blockaden):

1. Öffnen Sie **Plugins → Daikin ONECTA → Konfiguration → Erweiterte Konfiguration**.
2. Ändern Sie **HTTP-Transport** von „Node.js" zu „curl".
3. Speichern und Dienst neu starten.

## Logs prüfen

Zur Diagnose eines Problems:

1. Gehen Sie zu **Analyse → Logs** in Jeedom.
2. Filtern Sie nach Plugin: `daikinRCCloud`.
3. Suchen Sie nach Fehlermeldungen (in Rot).

## Hilfe anfordern

Wenn Sie keine Lösung finden, bitten Sie um Hilfe und geben Sie Folgendes an:

| Information | Wo zu finden |
|-------------|------------------|
| Plugin-Version | Konfiguration → Information |
| Interne Dienstversion | Konfiguration → Information |
| Jeedom-Version | Jeedom-Startseite |
| Verwendeter Verbindungsmodus | Konfiguration → Authentifizierungsmodus |
| Problembeschreibung | Was Sie erwartet haben vs. was passiert ist |
| Log-Auszug | Analyse → Logs, Filter daikinRCCloud |

### Jeedom-Forum

- [Plugin-Diskussionsthread](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55)
- Ab Jeedom 4.4+ verwenden Sie die Schaltfläche **Community-Beitrag erstellen** auf der Plugin-Seite, um ein Hilfeformular vorab auszufüllen.

### GitHub

Um einen Fehler zu melden: [GitHub Issues](https://github.com/ThibautTrarbach/daikinRCCloud/issues)

---

[Vorherige: Limits und Best Practices]({{ site.baseurl }}/de_DE/quota-api.html) — [Zurück zur Startseite]({{ site.baseurl }}/de_DE/)
