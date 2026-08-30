---
layout: default
title: Daikin ONECTA Dokumentation
---

# Daikin ONECTA Dokumentation

Das Plugin **Daikin ONECTA** ermöglicht die Steuerung und Überwachung Ihrer ONECTA-kompatiblen Daikin-Geräte direkt von Jeedom aus: Klimaanlagen, Altherma-Wärmepumpen und andere über die Daikin-Cloud verbundene Geräte.

## Wofür ist dieses Plugin?

Mit diesem Plugin können Sie Ihre Daikin-Geräte in Ihre Jeedom-Installation wie jedes andere Smart-Home-Gerät integrieren:

- Deren Status auf Ihrem **Dashboard** anzeigen
- Sie über **Szenarien** oder **Sprachbefehle** steuern
- Heizung, Klimatisierung oder Belüftung nach Ihren Gewohnheiten automatisieren

Ihre Geräte werden **automatisch erkannt**, sobald das Plugin korrekt konfiguriert ist. Es gibt keine Schaltfläche „Gerät hinzufügen“: Wenn Ihre Klimaanlage in der Daikin-Onecta-App auf Ihrem Smartphone erscheint, kann sie auch in Jeedom erscheinen.

## Was Sie tun können

| Funktion | Beschreibung |
|----------|-------------|
| **Ein / Aus** | Ihr Gerät ein- oder ausschalten |
| **Modi** | Kühlung, Heizung, Automatik, Entfeuchtung, reine Belüftung |
| **Sollwert** | Die gewünschte Temperatur einstellen |
| **Belüftung** | Geschwindigkeit und Luftstromrichtung wählen (je nach Modell) |
| **Spezialmodi** | Eco, Powerful, Streamer (je nach Modell) |
| **Temperaturen** | Raum- und Außentemperatur abfragen |
| **Luftfeuchtigkeit** | Raumluftfeuchtigkeit abfragen (je nach Modell) |
| **Verbrauch** | Energieverbrauch in kWh verfolgen (Tag, Woche, Monat) |
| **Zeitpläne** | In Onecta konfigurierte Zeitpläne aktivieren oder deaktivieren |
| **Urlaubsmodus** | Abwesenheitsmodus aktivieren (je nach Modell) |
| **Update** | Firmware-Update starten, wenn Daikin es anbietet |

> **Hinweis:** Die verfügbaren Befehle hängen von Ihrem Gerätemodell ab. Die meisten aktuellen Onecta-Modelle werden automatisch unterstützt, auch wenn sie nicht in der Liste unten aufgeführt sind.

## Kompatible Geräte

Das Plugin unterstützt insbesondere folgende Produktlinien:

| Linie / Typ | Beispiele |
|-------------|-----------|
| Mono-Zone-Klimatisierung | Daikin Perfera (FTXM), Stylish, Emura… |
| Erweiterte Klimatisierung | Modelle mit Eco-, Streamer-Modi, Luftstromausrichtung |
| Dual-Zone-Wärmepumpe | Daikin Altherma (Heizung + Warmwasser) |
| Multi-Zone-Klimatisierung | Anlagen mit mehreren Zonen |

Wenn Ihr Modell nicht explizit aufgeführt ist, versucht das Plugin, es automatisch zu unterstützen — dank der **automatischen Unterstützung aktueller Modelle** (Option standardmäßig in der erweiterten Konfiguration aktiviert).

## Was Sie benötigen

| Voraussetzung | Detail |
|---------------|--------|
| **Jeedom** | Version 4.4 oder höher |
| **Plugin mqtt2** | Obligatorisch — installiert und aktiv auf Ihrem Jeedom |
| **Daikin-Developer-Konto** | Anwendung auf [developer.cloud.daikineurope.com](https://developer.cloud.daikineurope.com/) erstellt (Developer-Portal-Modus empfohlen) |
| **Internetzugang** | Erforderlich für die Kommunikation mit der Daikin-Cloud |

## Inhaltsverzeichnis

| Seite | Beschreibung |
|-------|-------------|
| [Installation]({{ site.baseurl }}/de_DE/installation.html) | Plugin installieren und Ihre Geräte sichtbar machen |
| [Konfiguration]({{ site.baseurl }}/de_DE/configuration.html) | Plugin-Einstellungen |
| [Authentifizierung]({{ site.baseurl }}/de_DE/authentification.html) | Mit Ihrem Daikin-Konto verbinden |
| [Nutzung]({{ site.baseurl }}/de_DE/utilisation.html) | Ihre Geräte im Alltag steuern |
| [Limits und Best Practices]({{ site.baseurl }}/de_DE/quota-api.html) | Die Grenzen der Daikin-Cloud verstehen |
| [Fehlerbehebung]({{ site.baseurl }}/de_DE/depannage.html) | Häufige Probleme lösen |

## Nützliche Links

- [Changelog]({{ site.baseurl }}/de_DE/changelog.html)
- [Jeedom-Forum](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55)
- [GitHub-Repository](https://github.com/ThibautTrarbach/daikinRCCloud)

---

**Weiter:** [Installation]({{ site.baseurl }}/de_DE/installation.html)
