---
layout: default
title: Konfiguration - Daikin ONECTA
---

# Konfiguration

Die Konfigurationsseite befindet sich unter **Plugins → Daikin ONECTA → Konfiguration**.

Die meisten Benutzer müssen nur den Abschnitt **Daikin-Verbindung** ändern. Weitere Einstellungen sind über das Kontrollkästchen **Erweiterte Konfiguration** verfügbar.

---

## Daikin-Verbindung

Dies ist der wichtigste Abschnitt. Er verknüpft Jeedom mit Ihrem Daikin-Konto.

### Authentifizierungsmodus

Zwei Modi sind verfügbar:

| Modus | Für wen? | Tageskontingent |
|------|-----------|-------------|
| **Developer Portal** (empfohlen) | Alle Benutzer | 200 Anfragen/Tag |
| **Mobile App** | Fortgeschrittene Benutzer mit Onecta-Zugangsdaten | 3000 Anfragen/Tag |

> **Empfohlen:** Wählen Sie **Developer Portal** und erstellen Sie eine Anwendung auf dem [Daikin-Entwicklerportal](https://developer.cloud.daikineurope.com/).

Details zu jedem Modus finden Sie auf der Seite [Authentifizierung]({{ site.baseurl }}/de_DE/authentification.html).

### Zugangsdaten

Je nach gewähltem Modus:

- **Mobile App:** Geben Sie Ihre **Onecta-E-Mail** und Ihr **Onecta-Passwort** ein.
- **Developer Portal:** Geben Sie die **Client ID** und das **Client Secret** Ihrer Daikin-Developer-Anwendung ein.

### Tägliches API-Kontingent

Dieses Feld zeigt die maximale Anzahl der pro Tag erlaubten Cloud-Anfragen gemäß Ihrem Verbindungsmodus an. Es wird automatisch berechnet und kann nicht bearbeitet werden.

Um zu verstehen, was dies in der Praxis bedeutet, siehe [Limits und Best Practices]({{ site.baseurl }}/de_DE/quota-api.html).

### Versionen

Plugin- und interne Dienstversionen werden schreibgeschützt angezeigt. Geben Sie diese an, wenn Sie im Forum um Hilfe bitten.

---

## Erweiterte Konfiguration

Aktivieren Sie **Erweiterte Konfiguration**, um zusätzliche Einstellungen anzuzeigen. **Die meisten Benutzer können die Standardwerte beibehalten.**

### Aktualisierungsfrequenz

Diese Einstellungen legen fest, wie oft Jeedom die Daikin-Cloud nach dem Status Ihrer Geräte abfragt.

| Einstellung | Standard | Beschreibung |
|---------|--------|-------------|
| **Tagesintervall** | 15 Min. | Abfragefrequenz zwischen Morgen und Abend |
| **Nachtintervall** | 30 Min. | Abfragefrequenz während der Nacht (spart Kontingent) |
| **Nachtbeginn** | 22 Uhr | Uhrzeit, zu der die Nachtperiode beginnt |
| **Nachtende** | 7 Uhr | Uhrzeit, zu der die Nachtperiode endet |

> **Tipp:** Mit dem Mobile-App-Modus und aktivierten Echtzeit-Updates können Sie diese Intervalle erhöhen, ohne an Reaktionsfähigkeit zu verlieren.

Das Feld **Geplante Anfragen/Tag** schätzt, wie viele GET-Anfragen der Daemon pro Tag plant (Polling + Energiestatistiken), basierend auf Authentifizierungsmodus und WebSocket. Es aktualisiert sich automatisch, wenn Sie Einstellungen ändern. Befehle und Aktualisierung nach Aktionen erhöhen diese Schätzung.

### Verhalten nach einem Befehl

Wenn Sie einen Befehl auslösen (Temperatur ändern, Klimaanlage einschalten usw.), kann das Plugin auf drei Arten reagieren:

| Modus | Verhalten | Wann verwenden |
|------|----------|-------------|
| **1 — Verzögerte vollständige Aktualisierung** | Wartet und prüft dann den tatsächlichen Zustand bei Daikin | Wenn Sie eine systematische Cloud-Bestätigung wünschen |
| **2 — Sofortige Aktualisierung** | Aktualisiert Jeedom sofort ohne Daikin-Abfrage | Um Kontingent zu sparen, wenn die Reaktionsfähigkeit ausreicht |
| **3 — Hybrid** (Standard) | Sofortige Aktualisierung + Daikin-Prüfung nach Verzögerung | **Empfohlen** — gutes Gleichgewicht zwischen Reaktionsfähigkeit und Zuverlässigkeit |

**Aktualisierungsverzögerung:** In den Modi 1 und 3 die Wartezeit vor der Daikin-Abfrage (Standard: 60 Sekunden). Behalten Sie diese Verzögerung bei, wenn Ihre Geräte einen Moment brauchen, um zu reagieren.

**Verifizierungsstrategie:**

| Strategie | Beschreibung |
|----------|-------------|
| **Mit Synchronisation zusammenführen** (Standard) | Wenn eine geplante Synchronisation bald ansteht, wartet das Plugin statt eine zusätzliche Anfrage zu senden |
| **Dedizierte Verifizierung** | Das Plugin fragt Daikin gezielt nach jedem Befehl ab |
| **Keine Verifizierung** | Keine Cloud-Anfrage nach einem Befehl |

**Aktualisierung der Energiestatistiken:** Tägliche Uhrzeit (Standard 23:58 Uhr), zu der das Plugin die kWh-Verbrauchszähler aktualisiert.

### Automatische Modellunterstützung

| Option | Standard | Beschreibung |
|--------|--------|-------------|
| **Unbekannte Modelle** | Aktiviert | Steuert automatisch Daikin-Modelle, die nicht explizit aufgeführt sind |
| **Nur-Lese-Sensoren** | Aktiviert | Zeigt Außentemperaturen, Diagnosen usw. an |
| **Bei Änderung veröffentlichen** | Aktiviert | Aktualisiert Jeedom nur, wenn sich ein Wert tatsächlich geändert hat |

Deaktivieren Sie **Unbekannte Modelle** nur, wenn Sie abnormalen Verhalten bei einem nicht erkannten Gerät feststellen.

### Zusätzliche Optionen

| Option | Standard | Beschreibung |
|--------|--------|-------------|
| **Echtzeit-WebSocket** | Aktiviert | Empfängt Zustandsänderungen live (nur Mobile-App-Modus) |
| **Authentifizierungsport** | 8765 | Lokaler Port nur für Developer-Portal-Verbindung |
| **MQTT-Präfix** | daikinToMQTT | Standard beibehalten, sofern kein Konflikt mit einem anderen Plugin besteht |

---

## Experteneinstellungen

> **Ändern Sie diese Einstellungen nur, wenn der Support Sie dazu auffordert oder wenn Sie wissen, warum.**

### HTTP-Transport

Wenn das Plugin nicht mit Daikin kommunizieren kann (wiederholte Netzwerkfehler, Firewall blockiert), wechseln Sie von **Node.js** zu **curl**. Dies verwendet eine andere Netzwerk-Engine, die manchmal Blockaden umgeht.

### Abhängigkeitskonfiguration

Ermöglicht die Auswahl, welche Version des internen Dienstes des Plugins installiert wird (Branch oder exakte Version). **Standardwerte beibehalten** (`release-beta`), sofern der Support nichts anderes anweist.

Führen Sie nach jeder Änderung die Abhängigkeitsinstallation erneut aus.

---

[Vorherige: Installation]({{ site.baseurl }}/de_DE/installation.html) — [Weiter: Authentifizierung]({{ site.baseurl }}/de_DE/authentification.html)
