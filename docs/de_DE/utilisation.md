---
layout: default
title: Nutzung - Daikin ONECTA
---

# Nutzung

Diese Seite erklärt, wie Sie Ihre Daikin-Geräte im Alltag in Jeedom nutzen: Geräte finden, verfügbare Befehle verstehen, sie auf dem Dashboard anzeigen und in Szenarien einbinden.

## Ihre Geräte finden

1. Öffnen Sie **Plugins → Daikin ONECTA**.
2. Ihre Geräte erscheinen unter **Meine Geräte** als Karten.

Sie werden bei der ersten Synchronisation **automatisch** hinzugefügt. Es gibt keine Schaltfläche „Gerät hinzufügen“.

### Gerät anpassen

Klicken Sie auf die Gerätekarte, um zur Konfiguration zu gelangen:

| Einstellung | Beschreibung |
|-------------|-------------|
| **Name** | Gerät umbenennen (z. B. „Klima Wohnzimmer“) |
| **Übergeordnetes Objekt** | Einer Raum- oder Etagenstruktur zuordnen |
| **Kategorie** | Heizung, Komfort usw. |
| **Aktivieren** | Gerät aktivieren oder deaktivieren |
| **Sichtbar** | Gerät im Dashboard anzeigen oder ausblenden |

### Geräteinformationen

Im Tab **Gerät** zeigt ein Kasten die von Daikin gemeldeten Informationen:

| Information | Nutzen |
|-------------|--------|
| **Modell** | Referenz Ihres Geräts |
| **Seriennummer** | Eindeutige Kennung |
| **Firmware-Version** | Version der eingebetteten Software |
| **Fehlercode** | Möglicher Warncode — konsultieren Sie die Geräteanleitung oder den Daikin-Support bei angezeigtem Code |

---

## Verfügbare Befehle

Die Befehle werden automatisch je nach Gerätemodell erstellt. Sie finden sie im Tab **Befehle** jedes Geräts.

> **Hinweis:** Nicht alle unten genannten Befehle sind auf Ihrem Gerät verfügbar. Das hängt vom Modell ab.

### Hauptsteuerung

| Was Sie tun möchten | Befehl in Jeedom |
|---------------------|------------------|
| Ein- / Ausschalten | **State** (oder Betrieb) |
| Modus ändern (Kühlung, Heizung, Auto, Trocknen, Lüfter) | **Operation Mode** |
| Solltemperatur einstellen | **Temperature Control** |

### Belüftung

| Was Sie tun möchten | Befehl in Jeedom |
|---------------------|------------------|
| Belüftungsmodus wählen | **Fan Current Mode** |
| Lüftergeschwindigkeit einstellen | **Fan Fixed** |
| Luftstrom horizontal ausrichten | **Fan Horizontal** (je nach Modell) |
| Luftstrom vertikal ausrichten | **Fan Vertical** (je nach Modell) |

### Spezialmodi

| Was Sie tun möchten | Befehl in Jeedom |
|---------------------|------------------|
| Eco-Modus aktivieren | **Eco Mode** |
| Powerful-Modus aktivieren | **Powerful Mode** |
| Streamer-Modus aktivieren (Reinigung) | **Streamer Mode** |

### Informationen (Nur-Lese)

| Was Sie abfragen möchten | Befehl in Jeedom |
|--------------------------|------------------|
| Raumtemperatur | **Room Temperature** |
| Außentemperatur | **Outdoor Temperature** |
| Raumluftfeuchtigkeit | **Room Humidity** |

### Energieverbrauch

| Was Sie abfragen möchten | Befehl in Jeedom |
|--------------------------|------------------|
| Heizverbrauch (Tag / Woche / Monat) | **Heating Consumption D/W/M** |
| Kühlverbrauch (Tag / Woche / Monat) | **Cooling Consumption D/W/M** |

Die Zähler werden automatisch täglich aktualisiert (Standard: gegen 23:58 Uhr).

### Zeitpläne und erweiterte Modi

| Was Sie tun möchten | Befehl in Jeedom |
|---------------------|------------------|
| Onecta-Zeitplan aktivieren / deaktivieren | **Schedule** (je nach Modell) |
| Urlaubsmodus aktivieren | **Preset Away** (je nach Modell) |
| Firmware-Update starten | **Firmware Update** (wenn von Daikin angeboten) |

---

## Multi-Zone-Geräte

Einige Modelle, insbesondere **Altherma**-Wärmepumpen, haben mehrere unabhängige Zonen. Dann sehen Sie separate Befehle für jede Zone:

- **Zone 1:** Hauptheizung
- **Zone 2:** Warmwasser oder zweite Heizzone

Jede Zone hat eigene Befehle für Betrieb, Modus und Sollwert.

---

## Auf dem Dashboard anzeigen

Um Ihre Geräte vom Jeedom-Startbildschirm zu steuern:

1. Öffnen Sie das Gerät und gehen Sie zum Tab **Befehle**.
2. Machen Sie die gewünschten Befehle **sichtbar** (Kontrollkästchen in der Spalte Optionen).
3. Fügen Sie sie über den Jeedom-Design-Konfigurator Ihrem Dashboard hinzu.

Binäre Befehle (Ein/Aus) erscheinen als Schaltflächen. Temperatursollwerte als Schieberegler (Slider).

**Tipp:** Für den täglichen Gebrauch sollten mindestens **State**, **Operation Mode** und **Temperature Control** sichtbar sein.

---

## In Szenarien verwenden

Ihre Daikin-Geräte können wie andere Geräte in beliebige Jeedom-Szenarien eingebunden werden.

### Beispiele

**Sommerkomfort:**
> Wenn Wohnzimmertemperatur > 26 °C → Klima einschalten, Kühlmodus, Sollwert 24 °C

**Nachtmodus:**
> Um 22:00 Uhr → Eco-Modus auf allen Klimaanlagen aktivieren

**Urlaubsabfahrt:**
> Wenn Modus „Abwesenheit“ aktiv → Preset Away an der Wärmepumpe aktivieren

**Energie sparen:**
> Wenn niemand zu Hause → Klimatisierung ausschalten

**Heimkehr:**
> Wenn Geolokalisierung Rückkehr erkennt → Klima einschalten, Auto-Modus, Sollwert 22 °C

Um ein Szenario zu erstellen, gehen Sie zu **Werkzeuge → Szenarien** und nutzen Sie die Befehle Ihrer Daikin-Geräte als Aktionen oder Bedingungen.

---

## Befehle bearbeiten

Die Befehle werden automatisch vom Plugin erzeugt. Sie können:

- Einen Befehl umbenennen
- Die Sichtbarkeit ändern
- Einheit oder Symbol anpassen

> **Achtung:** Vermeiden Sie das Löschen automatisch erzeugter Befehle. Sie können bei der nächsten Synchronisation neu erstellt werden.

---

[Vorherige: Authentifizierung]({{ site.baseurl }}/de_DE/authentification.html) — [Nächste: Limits und Best Practices]({{ site.baseurl }}/de_DE/quota-api.html)
