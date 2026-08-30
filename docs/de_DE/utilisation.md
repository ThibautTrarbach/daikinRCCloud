---
layout: default
title: Nutzung - Daikin ONECTA
---

# Nutzung

Diese Seite erklärt, wie Sie Ihre Daikin-Geräte im Alltag in Jeedom nutzen: Geräte finden, verfügbare Befehle verstehen, auf dem Dashboard anzeigen und in Szenarien integrieren.

## Geräte finden

1. Öffnen Sie **Plugins → Daikin ONECTA**.
2. Ihre Geräte erscheinen unter **Meine Geräte** als Karten.

Sie werden **automatisch** bei der ersten Synchronisation hinzugefügt. Es gibt keine Schaltfläche „Gerät hinzufügen".

### Gerät anpassen

Klicken Sie auf die Gerätekarte, um auf die Konfiguration zuzugreifen:

| Einstellung | Beschreibung |
|---------|-------------|
| **Name** | Gerät umbenennen (z. B. „Klimaanlage Wohnzimmer") |
| **Übergeordnetes Objekt** | An einen Raum oder eine Etage in Ihrer Hausautomation anbinden |
| **Kategorie** | Heizung, Komfort usw. |
| **Aktivieren** | Gerät aktivieren oder deaktivieren |
| **Sichtbar** | Gerät auf dem Dashboard anzeigen oder ausblenden |

### Geräteinformationen

Im Tab **Gerät** zeigt ein Feld Informationen an, die von Daikin gemeldet werden:

| Information | Verwendung |
|-------------|-----|
| **Modell** | Ihre Gerätereferenz |
| **Seriennummer** | Eindeutige Kennung |
| **Firmware-Version** | Version der eingebetteten Software |
| **Fehlercode** | Möglicher Warnungscode — prüfen Sie Ihr Gerätehandbuch oder den Daikin-Support, wenn ein Code angezeigt wird |

---

## Verfügbare Befehle

Befehle werden automatisch basierend auf Ihrem Gerätemodell erstellt. Sie befinden sich im Tab **Befehle** jedes Geräts.

> **Hinweis:** Nicht alle unten aufgeführten Befehle sind unbedingt auf Ihrem Gerät verfügbar. Dies hängt vom Modell ab.

### Hauptsteuerung

| Was Sie tun möchten | Befehl in Jeedom |
|---------------------|-------------------|
| Ein / Aus | **State** (oder Power) |
| Modus ändern (Kühlung, Heizung, Auto, Entfeuchtung, Lüfter) | **Operation Mode** |
| Solltemperatur einstellen | **Temperature Control** |

### Belüftung

| Was Sie tun möchten | Befehl in Jeedom |
|---------------------|-------------------|
| Belüftungsmodus wählen | **Fan Current Mode** |
| Lüftergeschwindigkeit einstellen | **Fan Fixed** |
| Luftstrom horizontal ausrichten | **Fan Horizontal** (modellabhängig) |
| Luftstrom vertikal ausrichten | **Fan Vertical** (modellabhängig) |

### Spezialmodi

| Was Sie tun möchten | Befehl in Jeedom |
|---------------------|-------------------|
| Eco-Modus aktivieren | **Eco Mode** |
| Powerful-Modus aktivieren | **Powerful Mode** |
| Streamer-Modus aktivieren (Reinigung) | **Streamer Mode** |

### Informationen (Nur-Lese)

| Was Sie prüfen möchten | Befehl in Jeedom |
|------------------------|-------------------|
| Raumtemperatur | **Room Temperature** |
| Außentemperatur | **Outdoor Temperature** |
| Raumluftfeuchtigkeit | **Room Humidity** |

### Energieverbrauch

| Was Sie prüfen möchten | Befehl in Jeedom |
|------------------------|-------------------|
| Heizverbrauch (Tag / Woche / Monat) | **Heating Consumption D/W/M** |
| Kühlverbrauch (Tag / Woche / Monat) | **Cooling Consumption D/W/M** |

Zähler werden automatisch täglich aktualisiert (standardmäßig gegen 23:58 Uhr).

### Zeitpläne und erweiterte Modi

| Was Sie tun möchten | Befehl in Jeedom |
|---------------------|-------------------|
| Onecta-Zeitplan aktivieren / deaktivieren | **Schedule** (modellabhängig) |
| Abwesenheitsmodus aktivieren | **Preset Away** (modellabhängig) |
| Firmware-Update starten | **Firmware Update** (falls von Daikin angeboten) |

---

## Multi-Zonen-Geräte

Einige Modelle, insbesondere **Altherma**-Wärmepumpen, haben mehrere unabhängige Zonen. In diesem Fall sehen Sie separate Befehle für jede Zone:

- **Zone 1:** Hauptheizung
- **Zone 2:** Warmwasser oder zweite Heizzone

Jede Zone hat eigene Ein/Aus-, Modus- und Sollwertbefehle.

---

## Anzeige auf dem Dashboard

Um Ihre Geräte vom Jeedom-Startbildschirm aus zu steuern:

1. Öffnen Sie das Gerät und gehen Sie zum Tab **Befehle**.
2. Machen Sie die Befehle, die Sie anzeigen möchten, **sichtbar** (Kontrollkästchen in der Spalte Optionen).
3. Fügen Sie sie über den Jeedom-Design-Konfigurator zu Ihrem Dashboard hinzu.

Binäre Befehle (Ein/Aus) erscheinen als Schaltflächen. Sollwerte erscheinen als Schieberegler.

**Tipp:** Für den täglichen Gebrauch machen Sie mindestens **State**, **Operation Mode** und **Temperature Control** sichtbar.

---

## Verwendung in Szenarien

Ihre Daikin-Geräte können wie jedes andere Gerät in jedes Jeedom-Szenario integriert werden.

### Beispiele

**Sommerkomfort:**
> Wenn Wohnzimmertemperatur > 26°C → Klimaanlage im Kühlmodus einschalten, Sollwert 24°C

**Nachtmodus:**
> Um 22:00 Uhr → Eco-Modus an allen Klimaanlagen aktivieren

**Urlaub:**
> Wenn „Abwesenheit"-Modus aktiv → Preset Away an der Wärmepumpe aktivieren

**Energieeinsparung:**
> Wenn niemand zu Hause ist → Klimaanlage ausschalten

**Heimkehr:**
> Wenn Geolokalisierung Rückkehr erkennt → Klimaanlage einschalten, Auto-Modus, Sollwert 22°C

Um ein Szenario zu erstellen, gehen Sie zu **Werkzeuge → Szenarien** und verwenden Sie die Befehle Ihrer Daikin-Geräte als Aktionen oder Bedingungen.

---

## Befehle ändern

Befehle werden automatisch vom Plugin generiert. Sie können:

- Einen Befehl umbenennen
- Seine Sichtbarkeit ändern
- Seine Einheit oder sein Symbol ändern

> **Warnung:** Vermeiden Sie das Löschen automatisch generierter Befehle. Sie können bei der nächsten Synchronisation neu erstellt werden.

---

[Vorherige: Authentifizierung]({{ site.baseurl }}/de_DE/authentification.html) — [Weiter: Limits und Best Practices]({{ site.baseurl }}/de_DE/quota-api.html)
