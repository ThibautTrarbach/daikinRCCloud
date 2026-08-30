---
layout: default
title: Installation - Daikin ONECTA
---

# Installation

Diese Anleitung führt Sie durch die Installation des Plugins und das Sichtbarmachen Ihrer Daikin-Geräte in Jeedom.

## Bevor Sie beginnen

Stellen Sie sicher, dass Sie Folgendes haben:

- Jeedom **4.4** oder neuer
- Ein **Daikin-Onecta-Konto** (dasselbe wie in der Mobile App)
- Ihre Daikin-Geräte bereits konfiguriert und in der Onecta-App sichtbar

## Schritt 1: Plugin installieren

1. Öffnen Sie **Plugins → Plugin-Verwaltung** in Jeedom.
2. Suchen Sie nach **Daikin ONECTA**.
3. Klicken Sie auf **Installieren**, dann auf **Aktivieren**.

## Schritt 2: mqtt2-Plugin installieren

Das **mqtt2**-Plugin ist **obligatorisch**. Es fungiert als internes Relais zwischen dem Daikin-Plugin und Ihren Geräten. In den meisten Fällen benötigen Sie keine spezielle Konfiguration darin.

1. Installieren Sie das **mqtt2**-Plugin aus dem Jeedom-Markt (falls noch nicht installiert).
2. Aktivieren Sie es.
3. Prüfen Sie unter **Werkzeuge → Gesundheit**, ob mqtt2 läuft.

## Schritt 3: Abhängigkeiten installieren

Das Plugin benötigt zusätzliche Komponenten, um zu funktionieren. Jeedom installiert diese automatisch:

1. Gehen Sie zur **Daikin ONECTA**-Plugin-Seite.
2. Klicken Sie auf **Abhängigkeiten neu installieren** (oder verwenden Sie die entsprechende Schaltfläche in der Plugin-Verwaltung).
3. Warten Sie, bis die Installation abgeschlossen ist (dies kann einige Minuten dauern).

> **Hinweis:** Führen Sie nach jedem Plugin-Update die Abhängigkeitsinstallation erneut aus, wenn Jeedom Sie dazu auffordert.

## Schritt 4: Daikin-Verbindung konfigurieren

1. Öffnen Sie **Plugins → Daikin ONECTA → Konfiguration**.
2. Wählen Sie den Modus **Mobile App (empfohlen)**.
3. Geben Sie die **E-Mail** und das **Passwort** Ihres Daikin-Onecta-Kontos ein (dieselben wie in der Mobile App).
4. Klicken Sie auf **Speichern**.

Weitere Details zu den Verbindungsmodi finden Sie auf der Seite [Authentifizierung]({{ site.baseurl }}/de_DE/authentification.html).

## Schritt 5: Plugin-Dienst starten

1. Gehen Sie zu **Werkzeuge → Gesundheit** (oder zur Plugin-Seite).
2. Starten Sie den **Daemon** des Daikin-ONECTA-Plugins.
3. Prüfen Sie, ob der Status **Läuft** anzeigt.

## Schritt 6: Geräte prüfen

1. Kehren Sie zu **Plugins → Daikin ONECTA** zurück.
2. Ihre Daikin-Geräte sollten unter **Meine Geräte** erscheinen.

Wenn kein Gerät erscheint, siehe die Seite [Fehlerbehebung]({{ site.baseurl }}/de_DE/depannage.html).

## Wie geht es weiter?

- [Plugin-Einstellungen konfigurieren]({{ site.baseurl }}/de_DE/configuration.html)
- [Erfahren Sie, wie Sie Ihre Geräte nutzen]({{ site.baseurl }}/de_DE/utilisation.html)

---

[Vorherige: Startseite]({{ site.baseurl }}/de_DE/) — [Weiter: Konfiguration]({{ site.baseurl }}/de_DE/configuration.html)
