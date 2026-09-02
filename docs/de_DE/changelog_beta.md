---
layout: default
title: Changelog Daikin ONECTA (Beta)
---

# Changelog Daikin ONECTA

Alle bemerkenswerten Änderungen an diesem Plugin werden auf dieser Seite dokumentiert.

## [0.10.3] - 2026-09-01

> Mit der Branche `release-beta` gelieferter Daemon: **2.1.5** (Mindestanforderung: 2.0.0)

### Behoben — Daemon
- BRP069B4x / BRP069A4x: vollständige statische Abdeckung (`gatewayDiagnosticsPack`, `auxiliaryUnitPack`) — [#42](https://github.com/ThibautTrarbach/daikinRCCloud/issues/42)
- BRP069A78: Gateway-Diagnose, Vorlauf-Offset, Warmwasserspeicher, Hydro/Outdoor/UI-Einheiten — [#41](https://github.com/ThibautTrarbach/daikinRCCloud/issues/41)
- Debug-Bericht: Seriennummer (`serialNumber`) maskiert

---

## [0.10.2] - 2026-09-01

> Mit der Branche `release-beta` gelieferter Daemon: **2.1.3** (Mindestanforderung: 2.0.0)

### Hinzugefügt — Plugin
- Erweitertes Support-Diagnosepanel: Support-Nachricht, Management Points, erkannte Einheiten, nicht zugeordnete Datapoints, Debug-Bericht mit Kopier-Button
- GitHub-Link zum Melden nicht unterstützter Geräte (`githubIssueUrl`)
- Synchronisation der Support-Metadaten vom Daemon zur Gerätekonfiguration
- Automatische Bereinigung veralteter Support-Befehle (Logical IDs `_supportStatus`, `_configCoverage`, etc.)

### Hinzugefügt — Daemon
- `gatewayDiagnosticsPack` und `auxiliaryUnitPack`: Gateway-Netzwerk-/Diagnosesensoren und Indoor-/Outdoor-Einheitensensoren (BRP069C4x)
- Read-only-Mapping `isPowerfulModeActive` (Firmware 2.6.x API)
- Support-Metadaten-Modul: Debug-Bericht, GitHub-Issue-URL, synchronisierte Support-Befehle

### Behoben — Daemon
- API-Abdeckungsaudit: Normalisierung der Datapoint-Pfade, Berücksichtigung von `_device`-Metadaten
- Home Assistant Preset Mode: Fallback auf `_isPowerfulModeActive` wenn `_powerfulMode` fehlt

### Geändert — Daemon
- `_device`-Anreicherung: `ipAddress`, `macAddress`, `ssid`-Alias
- Debug-Bericht: Support-Nachricht, Trunkierungsanzeige für nicht zugeordnete Datapoints

---

## [0.10.1] - 2026-09-01

> Mit der Branche `release-beta` gelieferter Daemon: **2.1.2** (Mindestanforderung: 2.0.0)

### Hinzugefügt — Plugin
- Support-Status-Warnungen: Benachrichtigung bei teilweiser Unterstützung oder unvollständiger Konfiguration eines Daikin-Geräts (UI-Banner + Diagnoseinformationen)
- Bereinigung von Phantom-Geräten: automatische Entfernung logischer IDs aus internen MQTT-Topics (Systembrücke)
- Authentifizierungsbenachrichtigungen: OAuth-Validierungsanfrage mit Benutzermeldung und Ablauf des Zeitlimits

### Hinzugefügt — Daemon
- Konfigurierbares OAuth-Autorisierungs-Timeout (`authorizationTimeoutSeconds`, 60–3600 s)
- Bereinigung veralteter retained MQTT-Topics beim Start
- WebSocket: Unterstützung verschachtelter Merkmale und Referenzen in Push-Updates

### Geändert
- Dokumentationsklärung: Unterscheidung zwischen Daikin2MQTT-Brücke und echten Daikin-Geräten
- Verbesserter kontrollierter Daemon-Shutdown (Überspringen von Operationen während des Shutdowns)
- Verbesserte Rate-Limit-Verwaltung: Zusammenführung partieller Updates, Beibehaltung vorheriger Werte

---

## [0.10.0] - 2026-08-30

> Mit der Branche `release-beta` gelieferter Daemon: **2.1.0** (Mindestanforderung: 2.0.0)

### Wichtige Änderung
- Daemon V1 (< 2.0.0) wird nicht mehr unterstützt — obligatorische Migration auf Daemon V2
- **Neuinstallation der Abhängigkeiten erforderlich** nach dem Plugin-Update
- Standard-Abhängigkeitsbranch: `release-beta`
- Automatische Migration von V1-Branches (`release-stable`, `dev`, `release-dev` usw.) zu `release-beta`
- Voraussetzungen: Jeedom 4.4+, Plugin **mqtt2** installiert und gestartet

### Hinzugefügt — Plugin
- Vollständige Überarbeitung der Konfigurationsseite (einklappbare Abschnitte, gespeicherter Modus „Erweiterte Konfiguration“)
- Anzeige der Plugin- und Daemon-Versionen (nützlich für Community-Support)
- Modus **Mobile App**: Anmeldung mit Onecta-E-Mail/Passwort, Quota ~3000 Anfragen/Tag
- Modus **Developer Portal**: OAuth Client ID/Secret, Quota ~200 Anfragen/Tag
- Automatische Schätzung des API-Quotas und der Anfragen/Tag je nach Authentifizierungsmodus
- Neue erweiterte Einstellungen: Strategie Refresh nach Aktion, Zusammenführung mit Polling, Befehls-Koaleszenz, Refresh Energiestatistiken, WebSocket, DynamicGateway, Nur-Lese-Sensoren, Veröffentlichung bei Delta, HTTP-Transport curl, MQTT-Präfix
- Onecta-Passwort verschlüsselt gespeichert
- mqtt2-Prüfungen mit aussagekräftigen Fehlermeldungen, wenn nicht vorhanden oder nicht gestartet
- Erste Version der Online-Dokumentation: Installation, Konfiguration, Authentifizierung, Nutzung, API-Quotas, Fehlerbehebung. Inhalt vor der Veröffentlichung schnell von einer KI erstellt — **noch nicht geprüft oder validiert**

### Hinzugefügt — Daemon (über das Plugin)
- Vereinfachte Verbindung mit demselben Konto wie die Onecta-App (Modus Mobile App)
- Echtzeit-Updates über WebSocket (Reaktionsfähigkeit ohne API-Quota zu verbrauchen)
- Automatische Unterstützung nicht gelisteter Daikin-Modelle (DynamicGateway)
- kWh-Energiezähler täglich zu konfigurierbarer Uhrzeit aktualisiert
- Sofortige Rückmeldung in Jeedom nach einem Befehl (optimistische MQTT-Veröffentlichung)
- Quota-Einsparung: Zusammenführung Refresh/Polling, adaptives Polling je nach API-Budget, Überspringen wenn WebSocket die Änderung bestätigt
- Umgehung von Daikin-Netzwerk-/WAF-Blockaden (HTTP-Transport curl)
- API-Budget-Status auf der MQTT-Systembrücke sichtbar

### Geändert
- Authentifizierungsoberfläche: dynamisches Umschalten Mobile App / Developer Portal mit angezeigten Quotas
- Polling-Standardwerte: 15 Min. (Tag) / 30 Min. (Nacht); Refresh-Verzögerung nach Aktion: 60 s (statt 120 s)
- Zuverlässigere Daemon-Installation: Ausführung über kompiliertes `main.js`, Prüfungen bei der Installation
- Aussagekräftige Fehlermeldungen (mqtt2 fehlt, Daemon zu alt, `main.js` nicht gefunden)
- ~90 neue UI-Übersetzungen (FR, EN, ES, DE, IT)
- Versionsverzweigung beibehalten mit kommentierten V3-Stubs für zukünftige Entwicklungen

### Entfernt
- Unterstützung für Daemon V1 (< 2.0.0) und OAuth-Authentifizierung über MQTT
- V1-Plugin-Code (Konfiguration, Legacy-MQTT-Nachrichten)
- Veraltete Abhängigkeitsbranches: `release-stable`, `dev`, `release-dev`

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
- Daemon 2.0.x ist noch nicht ordnungsgemäß verfügbar. In den kommenden Tagen werden wir Ihnen anbieten, ihn im Alpha-Modus zu testen, um Ihre Produktion während der Heizperiode nicht zu beeinträchtigen

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
