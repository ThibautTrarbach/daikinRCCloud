---
layout: default
title: Limits und Best Practices - Daikin ONECTA
---

# Limits und Best Practices

Daikin begrenzt, wie oft Jeedom die Cloud pro Tag abfragen darf. Diese Seite erklärt, warum diese Grenze existiert und wie Sie sie optimieren.

## Warum eine Grenze?

Damit das Plugin funktioniert, muss es regelmäßig den Status Ihrer Geräte bei Daikin abfragen (Temperatur, Modus, Ein/Aus…). Jede Anfrage zählt in ein **Tagesquota**, das von Daikin festgelegt wird. Das Quota wird täglich um Mitternacht zurückgesetzt.

Das ist keine Einschränkung des Plugins, sondern eine Regel des Daikin-ONECTA-Cloud-Dienstes.

## Quota je nach Verbindungsmodus

| Verbindungsmodus | Erlaubte Abfragen pro Tag |
|------------------|---------------------------|
| **Mobile App** (empfohlen) | 3000 |
| **Developer Portal** | 200 |

> **Empfohlen:** Verwenden Sie den Modus **Mobile App**, um ein deutlich komfortableres Quota zu nutzen.

## Was verbraucht Quota?

| Aktion | Verbrauch |
|--------|-----------|
| Geplante Synchronisation (Standard: alle 15 Min.) | 1 Abfrage (für alle Geräte gleichzeitig) |
| Befehl (Temperatur ändern, einschalten…) | 1 Abfrage pro Änderung |
| Prüfung nach einem Befehl | 1 Abfrage (je nach Einstellungen) |
| Aktualisierung der kWh-Zähler (jeden Abend) | 1 Abfrage |
| Echtzeit-Update (WebSocket, Modus Mobile App) | **0** Abfragen |

**Wichtig:** Eine Synchronisation fragt **alle** Ihre Geräte in einem Vorgang ab. 1 oder 5 Klimaanlagen verbrauchen dieselbe Anzahl an Abfragen.

## Schätzung in der Konfiguration

Auf der Konfigurationsseite (erweiterter Abschnitt) schätzt das Feld **Geplante Anfragen/Tag**, wie viele GET-Abfragen der Daemon täglich plant — abhängig von Ihren Tag-/Nacht-Intervallen, dem Authentifizierungsmodus und dem WebSocket.

| Konfiguration | Detail | Geplant gesamt |
|---------------|--------|----------------|
| Developer Portal, Standard (15 Min. Tag, 30 Min. Nacht, Nacht 22→7 Uhr) | 60 Polls Tag + 18 Polls Nacht + 1 Energiestatistik | **~79 GET/Tag** |
| Mobile App + WebSocket aktiv, gleiche Intervalle | Sicherheitsnetz 30/60 Min.: 30 + 9 + 1 | **~40 GET/Tag** |

Diese Zahlen umfassen nicht Ihre Befehle, Refresh nach Aktion (je nach Einstellungen) noch den GET beim Daemon-Start (+1 bei jedem Neustart).

## Tipps zur Optimierung

### Für die meisten Benutzer

1. **Nutzen Sie den Modus Mobile App** — 15-mal höheres Quota.
2. **Behalten Sie die Standardwerte** — sie sind für ein gutes Gleichgewicht ausgelegt.
3. **Lassen Sie WebSocket aktiviert** (Modus Mobile App) — Statusänderungen kommen in Echtzeit ohne Quota-Verbrauch.

### Im Modus Developer Portal (200/Tag)

- Reduzieren Sie die Synchronisationsintervalle nicht unter 15 Minuten.
- Vermeiden Sie Szenarien mit vielen schnell aufeinanderfolgenden Befehlen.
- Erwägen Sie den Wechsel zum Modus Mobile App.

### Bei vielen Geräten und Automatisierungen

- Erhöhen Sie die Synchronisationsintervalle leicht (z. B. 20 Min. tagsüber, 45 Min. nachts).
- Der Hybridmodus (Standard) für das Verhalten nach Befehlen ist am sparsamsten.

## Was passiert, wenn das Quota erreicht ist?

Das Plugin verlangsamt automatisch die Synchronisationen, wenn das Quota fast aufgebraucht ist. Ihre Befehle funktionieren weiter, aber Statusaktualisierungen können bis zum nächsten Tag seltener werden.

Im Modus Developer Portal kann ein Quota-Überschreitung alle Abfragen bis Mitternacht blockieren.

---

[Vorherige: Nutzung]({{ site.baseurl }}/de_DE/utilisation.html) — [Nächste: Fehlerbehebung]({{ site.baseurl }}/de_DE/depannage.html)
