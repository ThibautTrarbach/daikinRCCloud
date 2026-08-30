---
layout: default
title: Konfiguration - Daikin ONECTA
---

# Konfiguration

Die Konfigurationsseite finden Sie unter **Plugins → Daikin ONECTA → Konfiguration**.

Die meisten Benutzer müssen nur den Abschnitt **Daikin-Verbindung** anpassen. Die übrigen Einstellungen sind über das Kontrollkästchen **Erweiterte Konfiguration** erreichbar.

---

## Daikin-Verbindung

Das ist der wichtigste Abschnitt. Er verknüpft Jeedom mit Ihrem Daikin-Konto.

### Authentifizierungsmodus

Zwei Modi stehen zur Verfügung:

| Modus | Für wen? | Tagesquota |
|-------|----------|------------|
| **Mobile App** (empfohlen) | Nutzer mit der Daikin-Onecta-App | 3000 Abfragen/Tag |
| **Developer Portal** | Fortgeschrittene Nutzer mit einer App im Daikin-Entwicklerportal | 200 Abfragen/Tag |

> **Empfohlen:** Wählen Sie **Mobile App** und verwenden Sie dieselben Zugangsdaten wie in der Daikin-Onecta-App auf Ihrem Smartphone.

Details zu jedem Modus finden Sie auf der Seite [Authentifizierung]({{ site.baseurl }}/de_DE/authentification.html).

### Zugangsdaten

Je nach gewähltem Modus:

- **Mobile App:** Geben Sie Ihre **Onecta-E-Mail** und Ihr **Onecta-Passwort** ein.
- **Developer Portal:** Geben Sie die **Client ID** und das **Client Secret** Ihrer Daikin-Developer-Anwendung ein.

### Tägliches API-Quota

Dieses Feld zeigt die maximal erlaubten Cloud-Abfragen pro Tag je nach Verbindungsmodus. Es wird automatisch berechnet und ist nicht editierbar.

Was das konkret bedeutet, erfahren Sie unter [Limits und Best Practices]({{ site.baseurl }}/de_DE/quota-api.html).

### Versionen

Die Versionen des Plugins und des internen Dienstes werden schreibgeschützt angezeigt. Geben Sie diese an, wenn Sie im Forum um Hilfe bitten.

---

## Erweiterte Konfiguration

Aktivieren Sie **Erweiterte Konfiguration**, um zusätzliche Einstellungen anzuzeigen. **Die meisten Benutzer können die Standardwerte beibehalten.**

### Aktualisierungsintervall

Diese Einstellungen legen fest, wie oft Jeedom die Daikin-Cloud abfragt, um den Status Ihrer Geräte zu erfahren.

| Einstellung | Standard | Beschreibung |
|-------------|----------|-------------|
| **Intervall tagsüber** | 15 Min. | Prüfintervall zwischen Morgen und Abend |
| **Intervall nachts** | 30 Min. | Prüfintervall in der Nacht (spart Quota) |
| **Nachtbeginn** | 22 Uhr | Uhrzeit, ab der die Nachtperiode beginnt |
| **Nachtende** | 7 Uhr | Uhrzeit, ab der die Nachtperiode endet |

> **Tipp:** Mit dem Modus Mobile App und aktivierten Echtzeit-Updates können Sie diese Intervalle erhöhen, ohne an Reaktionsfähigkeit zu verlieren.

Das Feld **Geplante Anfragen/Tag** schätzt, wie viele GET-Abfragen der Daemon täglich plant (Polling + Energiestatistiken), unter Berücksichtigung des Authentifizierungsmodus und des WebSocket. Die Aktualisierung erfolgt automatisch, wenn Sie Einstellungen ändern. Befehle und Refresh nach Aktion kommen zu dieser Schätzung hinzu.

### Verhalten nach einem Befehl

Wenn Sie einen Befehl auslösen (Temperatur ändern, Klima einschalten…), kann das Plugin auf drei Arten reagieren:

| Modus | Verhalten | Wann verwenden |
|-------|-----------|----------------|
| **1 — Verzögerter vollständiger Refresh** | Wartet und prüft den tatsächlichen Status bei Daikin | Wenn Sie eine systematische Cloud-Bestätigung wünschen |
| **2 — Sofortige Aktualisierung** | Aktualisiert Jeedom sofort, ohne Daikin zu prüfen | Um Quota zu sparen, wenn die Reaktionsfähigkeit ausreicht |
| **3 — Hybrid** (Standard) | Sofortige Aktualisierung + Daikin-Prüfung nach Verzögerung | **Empfohlen** — gutes Gleichgewicht zwischen Reaktionsfähigkeit und Zuverlässigkeit |

**Refresh-Verzögerung:** In Modus 1 und 3 die Wartezeit vor der Prüfung bei Daikin (Standard: 60 Sekunden). Behalten Sie diese Verzögerung bei, wenn Ihre Geräte etwas Zeit zum Reagieren brauchen.

**Prüfstrategie:**

| Strategie | Beschreibung |
|-----------|-------------|
| **Zusammenführung mit Synchronisation** (Standard) | Wenn bald eine geplante Synchronisation ansteht, wartet das Plugin, statt eine zusätzliche Anfrage zu senden |
| **Dedizierte Prüfung** | Das Plugin fragt Daikin gezielt nach jedem Befehl ab |
| **Keine Prüfung** | Keine Cloud-Abfrage nach einem Befehl |

**Refresh Energiestatistiken:** Tägliche Uhrzeit (Standard 23:58 Uhr), zu der das Plugin die kWh-Verbrauchszähler aktualisiert.

### Automatische Modellunterstützung

| Option | Standard | Beschreibung |
|--------|----------|-------------|
| **Unbekannte Modelle** | Aktiviert | Ermöglicht die automatische Steuerung nicht explizit gelisteter Daikin-Modelle |
| **Nur-Lese-Sensoren** | Aktiviert | Zeigt Außentemperaturen, Diagnosen usw. an |
| **Veröffentlichung bei Änderung** | Aktiviert | Aktualisiert Jeedom nur, wenn sich ein Wert tatsächlich geändert hat |

Deaktivieren Sie **Unbekannte Modelle** nur, wenn Sie abnormes Verhalten mit einem nicht erkannten Gerät feststellen.

### Zusätzliche Optionen

| Option | Standard | Beschreibung |
|--------|----------|-------------|
| **WebSocket Echtzeit** | Aktiviert | Empfängt Statusänderungen live (nur Modus Mobile App) |
| **Authentifizierungsport** | 8765 | Lokaler Port nur für Developer Portal |
| **MQTT-Präfix** | daikinToMQTT | Standard beibehalten, außer bei Konflikt mit einem anderen Plugin |

---

## Experteneinstellungen

> **Ändern Sie diese Einstellungen nur, wenn der Support es verlangt oder Sie wissen warum.**

### HTTP-Transport

Wenn das Plugin nicht mit Daikin kommunizieren kann (wiederholte Netzwerkfehler, Firewall-Blockade), wechseln Sie von **Node.js** zu **curl**. Das nutzt eine andere Netzwerk-Engine, die manchmal Blockaden umgeht.

### Abhängigkeitskonfiguration

Ermöglicht die Auswahl, welche Version des internen Plugin-Dienstes installiert wird (Branch oder exakte Version). **Behalten Sie die Standardwerte** (`release-beta`) bei, sofern der Support nichts anderes angibt.

Starten Sie nach jeder Änderung die Abhängigkeitsinstallation erneut.

---

[Vorherige: Installation]({{ site.baseurl }}/de_DE/installation.html) — [Nächste: Authentifizierung]({{ site.baseurl }}/de_DE/authentification.html)
