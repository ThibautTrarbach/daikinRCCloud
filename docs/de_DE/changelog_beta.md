---
layout: default
title: Changelog Daikin ONECTA (Beta)
---

# Changelog Daikin ONECTA

Alle bemerkenswerten Änderungen an diesem Plugin werden auf dieser Seite dokumentiert.

## [0.10.0] - 2026-08-30

### Breaking change
- Daemon V1 (< 2.0.0) wird nicht mehr unterstützt — obligatorische Migration auf Daemon V2
- Standard-Abhängigkeitsbranch: `release-beta`
- Automatische Migration von V1-Branches (`release-stable`, `dev`, etc.) zu `release-beta` beim Update

### Entfernt
- V1-Plugin-Code (Konfiguration, MQTT-Nachrichten, OAuth-Verwaltung über MQTT)

### Geändert
- Versionsverzweigung beibehalten mit kommentierten V3-Stubs für zukünftige Entwicklungen

---

## [0.9.3] - 2025-12-10

### Verbessert
- Verbesserung des Plugin-Aktualisierungsprozesses
- Verbesserung der Plugin-Logs mit Übersetzung
- Behebung der Behandlung leerer Werte auf der Konfigurationsseite
- Bessere Fehlerbehandlung auf der Konfigurationsseite und in Aufgaben, die während der Plugin-Installation oder -Aktualisierung ausgeführt werden

---

## [0.9.2] - 2025-12-09

### Behoben
- Behebung eines Fehlers bei der Konfigurationsaktualisierung

### Breaking Change
- Das Plugin erfordert jetzt mindestens Jeedom 4.4

---

## [0.9.1] - 2025-12-05

### Informationen
- Daemon 2.0.0 ist noch nicht ordnungsgemäß verfügbar. In den kommenden Tagen werden wir Ihnen anbieten, es im Alpha-Modus zu testen, um Ihre Produktion während der Heizperiode nicht zu beeinträchtigen

### Hinzugefügt
- Abhängigkeitskonfiguration: Möglichkeit, Branch oder Commit zur Installation auszuwählen
- Unterstützung für Polling-Parameter (Tag/Nacht) für Daemon 2.0.0+
- Unterstützung für Aktualisierungsmodi nach Aktion für Daemon 2.0.0+
- Kompatibilität mit Daemon-Versionen V1 und V2
- Vollständige Internationalisierung (FR, EN, ES, DE, IT)
- Link zur zukünftigen Dokumentation und Changelog

### Geändert
- Verbesserte MQTT-Nachrichtenbehandlung
- Optimierte Geräteerstellung

### Behoben
- Verschiedene Fehlerbehebungen

---

## [0.9.0] - Im Jahr 2022 (Keine Erinnerung an das Datum)

### Hinzugefügt
- Erste Plugin-Version

