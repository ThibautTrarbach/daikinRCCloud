---
layout: default
title: Limits und Best Practices - Daikin ONECTA
---

# Limits und Best Practices

Daikin begrenzt, wie oft Jeedom pro Tag die Cloud abfragen kann. Diese Seite erklärt, warum diese Grenze existiert und wie Sie sie optimieren.

## Warum eine Grenze?

Damit das Plugin funktioniert, muss es regelmäßig Daikin nach dem Status Ihrer Geräte fragen (Temperatur, Modus, Ein/Aus usw.). Jede Anfrage zählt zu einem **Tageskontingent**, das von Daikin festgelegt wird. Dieses Kontingent wird täglich um Mitternacht zurückgesetzt.

Dies ist keine Plugin-Beschränkung, sondern eine Regel, die vom Daikin-ONECTA-Cloud-Dienst auferlegt wird.

## Kontingent nach Verbindungsmodus

| Verbindungsmodus | Erlaubte Anfragen pro Tag |
|-----------------|-------------------------|
| **Mobile App** (empfohlen) | 3000 |
| **Developer Portal** | 200 |

> **Empfohlen:** Verwenden Sie den Modus **Mobile App** für ein deutlich komfortableres Kontingent.

## Was verbraucht Kontingent?

| Aktion | Verbrauch |
|--------|-------------|
| Geplante Synchronisation (standardmäßig alle 15 Min.) | 1 Anfrage (für alle Ihre Geräte gleichzeitig) |
| Befehl (Temperatur ändern, einschalten usw.) | 1 Anfrage pro Änderung |
| Verifizierung nach einem Befehl | 1 Anfrage (je nach Einstellungen) |
| kWh-Zähleraktualisierung (jeden Abend) | 1 Anfrage |
| Echtzeit-Update (WebSocket, Mobile-App-Modus) | **0** Anfragen |

**Wichtig:** Eine Synchronisation fragt **alle** Ihre Geräte gleichzeitig ab. Ob Sie 1 oder 5 Klimaanlagen haben, verbraucht dieselbe Anzahl von Anfragen.

## In der Konfiguration angezeigte Schätzung

Auf der Konfigurationsseite (erweiterter Abschnitt) schätzt das Feld **Geplante Anfragen/Tag**, wie viele GET-Anfragen der Daemon pro Tag plant, basierend auf Ihren Tag-/Nachtintervallen, Authentifizierungsmodus und WebSocket.

| Konfiguration | Detail | Geplante Summe |
|---------------|--------|-----------------|
| Developer Portal, Standardwerte (15 Min. Tag, 30 Min. Nacht, Nacht 22 Uhr→7 Uhr) | 60 Tag-Abfragen + 18 Nacht-Abfragen + 1 Energiestatistik | **~79 GET/Tag** |
| Mobile App + WebSocket aktiviert, gleiche Intervalle | Sicherheitsnetz 30/60 Min.: 30 + 9 + 1 | **~40 GET/Tag** |

Diese Zahlen umfassen nicht Ihre Befehle, die Aktualisierung nach Aktionen (je nach Einstellungen) oder den Daemon-Start-GET (+1 bei jedem Neustart).

## Optimierungstipps

### Für die meisten Benutzer

1. **Mobile-App-Modus verwenden** — 15-mal höheres Kontingent.
2. **Standardeinstellungen beibehalten** — sie sind für ein gutes Gleichgewicht ausgelegt.
3. **WebSocket aktiviert lassen** (Mobile-App-Modus) — Zustandsänderungen kommen in Echtzeit an, ohne Kontingent zu verbrauchen.

### Wenn Sie den Developer-Portal-Modus verwenden (200/Tag)

- Reduzieren Sie Synchronisationsintervalle nicht unter 15 Minuten.
- Vermeiden Sie Szenarien, die viele Befehle in schneller Folge senden.
- Erwägen Sie den Wechsel zum Mobile-App-Modus.

### Wenn Sie viele Geräte und Automationen haben

- Erhöhen Sie die Synchronisationsintervalle leicht (z. B. 20 Min. Tag, 45 Min. Nacht).
- Der Hybrid-Modus (Standard) für das Verhalten nach Befehlen ist am sparsamsten.

## Was passiert, wenn das Kontingent erreicht ist?

Das Plugin verlangsamt automatisch die Synchronisation, wenn das Kontingent fast erschöpft ist. Ihre Befehle funktionieren weiterhin, aber Zustandsaktualisierungen können seltener sein, bis zum nächsten Tag.

Im Developer-Portal-Modus kann das Überschreiten des Kontingents alle Anfragen bis Mitternacht blockieren.

---

[Vorherige: Nutzung]({{ site.baseurl }}/de_DE/utilisation.html) — [Weiter: Fehlerbehebung]({{ site.baseurl }}/de_DE/depannage.html)
