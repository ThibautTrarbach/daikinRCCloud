---
layout: default
title: Utilizzo - Daikin ONECTA
---

# Utilizzo

Questa pagina spiega come utilizzare i dispositivi Daikin quotidianamente in Jeedom: trovare l'apparecchiatura, comprendere i comandi disponibili, visualizzarli sulla dashboard e integrarli negli scenari.

## Trovare i dispositivi

1. Apri **Plugin → Daikin ONECTA**.
2. I tuoi dispositivi appaiono in **I miei dispositivi**, sotto forma di schede.

Vengono aggiunti **automaticamente** durante la prima sincronizzazione. Non esiste un pulsante « Aggiungi dispositivo ».

### Bridge di sistema Daikin2MQTT Bridge

Vedrai sempre un dispositivo chiamato **Daikin - Daikin2MQTT Bridge**. È normale: si tratta del **bridge di sistema** del plugin, non di un dispositivo Daikin fisico.

| Ruolo | Esempi di comandi |
|------|-------------------|
| Autenticazione cloud | Authorization URL, Authorization Request |
| Supervisione | Rate Limit, Modules List, WebSocket Connected |
| Azioni globali | Refresh All Devices |

I tuoi **veri dispositivi Daikin** (climatizzatore, pompa di calore, ecc.) appaiono separatamente come **Daikin - {nome}**, come definito nell'app Onecta.

### Personalizzare un dispositivo

Fai clic sulla scheda del dispositivo per accedere alla sua configurazione:

| Impostazione | Descrizione |
|--------------|-------------|
| **Nome** | Rinominare il dispositivo (ad es. « Climatizzatore soggiorno ») |
| **Oggetto padre** | Collegarlo a una stanza o a un piano nella domotica |
| **Categoria** | Riscaldamento, comfort, ecc. |
| **Attiva** | Attivare o disattivare il dispositivo |
| **Visibile** | Mostrare o nascondere il dispositivo sulla dashboard |
| **Widget del plugin** | Attiva il widget HVAC dedicato (vedi sotto) |
| **Link panel mobile** | Mostra il pulsante verso il panel sul widget mobile |

### Informazioni sul dispositivo

Nella scheda **Dispositivo**, un riquadro mostra le informazioni fornite da Daikin:

| Informazione | Utilizzo |
|--------------|----------|
| **Modello** | Riferimento del dispositivo |
| **Numero di serie** | Identificatore univoco |
| **Versione firmware** | Versione del software integrato |
| **Codice errore** | Possibile codice di allerta — consulta il manuale del dispositivo o il supporto Daikin se viene mostrato un codice |

---

## Comandi disponibili

I comandi vengono creati automaticamente in base al modello del dispositivo. Si trovano nella scheda **Comandi** di ciascun dispositivo.

> **Nota:** non tutti i comandi elencati di seguito sono necessariamente disponibili sul tuo dispositivo. Dipende dal modello.

### Controllo principale

| Cosa vuoi fare | Comando in Jeedom |
|----------------|-------------------|
| Accendere / spegnere | **State** (o Power) |
| Cambiare modalità (freddo, caldo, auto, deumidificazione, ventilazione) | **Operation Mode** |
| Impostare la temperatura desiderata | **Temperature Control** |

### Ventilazione

| Cosa vuoi fare | Comando in Jeedom |
|----------------|-------------------|
| Scegliere la modalità di ventilazione | **Fan Current Mode** |
| Impostare la velocità della ventola | **Fan Fixed** |
| Orientare il flusso d'aria orizzontalmente | **Fan Horizontal** (a seconda del modello) |
| Orientare il flusso d'aria verticalmente | **Fan Vertical** (a seconda del modello) |

### Modalità speciali

| Cosa vuoi fare | Comando in Jeedom |
|----------------|-------------------|
| Attivare la modalità eco | **Eco Mode** |
| Attivare la modalità powerful | **Powerful Mode** |
| Attivare la modalità streamer (purificazione) | **Streamer Mode** |

### Informazioni (sola lettura)

| Cosa vuoi consultare | Comando in Jeedom |
|----------------------|-------------------|
| Temperatura ambiente | **Room Temperature** |
| Temperatura esterna | **Outdoor Temperature** |
| Umidità ambiente | **Room Humidity** |

### Consumo energetico

| Cosa vuoi consultare | Comando in Jeedom |
|----------------------|-------------------|
| Consumo riscaldamento (giorno / settimana / mese) | **Heating Consumption D/W/M** |
| Consumo raffrescamento (giorno / settimana / mese) | **Cooling Consumption D/W/M** |

I contatori vengono aggiornati automaticamente ogni giorno (intorno alle 23:58 per impostazione predefinita).

### Programmazioni e modalità avanzate

| Cosa vuoi fare | Comando in Jeedom |
|----------------|-------------------|
| Attivare / disattivare una programmazione Onecta | **Schedule** (a seconda del modello) |
| Attivare la modalità assenza | **Preset Away** (a seconda del modello) |
| Avviare un aggiornamento firmware | **Firmware Update** (se proposto da Daikin) |

---

## Dispositivi multizona

Alcuni modelli, in particolare le pompe di calore **Altherma**, dispongono di più zone indipendenti. In questo caso, vedrai comandi separati per ciascuna zona:

- **Zona 1:** riscaldamento principale
- **Zona 2:** acqua calda sanitaria o seconda zona di riscaldamento

Ogni zona ha i propri comandi di accensione, modalità e setpoint.

---

## Visualizzazione sulla dashboard

### Widget HVAC del plugin (opzionale)

Il plugin offre un **widget dedicato** (ispirato a DaikinOnlineCtrl) che raggruppa accensione/spegnimento, modalità, setpoint, ventilazione e sonde su un'unica tile (dashboard, mobile e panel).

Queste opzioni si trovano nella scheda **Dispositivo** di ciascun apparecchio Daikin (non compaiono sul bridge di sistema):

| Opzione | Predefinito | Descrizione |
|---------|-------------|-------------|
| **Widget del plugin** — *Usa il widget HVAC (stile DaikinOnlineCtrl)* | Disattivato | Sostituisce la tile standard Jeedom con il widget HVAC del plugin |
| **Link panel mobile** — *Mostra il pulsante verso il panel sul widget mobile* | Attivato | Nella vista mobile, mostra un pulsante per aprire il **panel** (vista tipo app) |

1. Apri il dispositivo (non il bridge di sistema).
2. Attiva **Usa il widget HVAC (stile DaikinOnlineCtrl)**.
3. (Opzionale) Attiva o disattiva **Mostra il pulsante verso il panel sul widget mobile**.
4. Salva e posiziona il dispositivo sulla dashboard / design.

Sui dispositivi multizona (ad es. Altherma), compare un **selettore di zona** in alto sulla tile. Le schede assenti vengono nascoste automaticamente.

> Il widget HVAC è **disattivato per impostazione predefinita** per non modificare il comportamento delle installazioni esistenti.

### Tile Jeedom standard

Senza il widget del plugin:

1. Apri il dispositivo e vai alla scheda **Comandi**.
2. Rendi **visibili** i comandi che desideri mostrare (casella nella colonna Opzioni).
3. Aggiungili alla dashboard tramite il configuratore di design Jeedom.

I comandi binari (on/off) appaiono come pulsanti. I setpoint di temperatura appaiono come cursori.

**Suggerimento:** per l'uso quotidiano, rendi visibili almeno **State**, **Operation Mode** e **Temperature Control**.

---

## Utilizzo negli scenari

I tuoi dispositivi Daikin possono essere integrati in qualsiasi scenario Jeedom, come gli altri apparecchi.

### Esempi

**Comfort estivo:**
> Se temperatura soggiorno > 26°C → Accendi climatizzatore in modalità freddo, setpoint 24°C

**Modalità notte:**
> Alle 22:00 → Attiva Eco Mode su tutti i condizionatori

**Vacanza:**
> Quando la modalità « Assenza » è attiva → Attiva Preset Away sulla pompa di calore

**Risparmio energetico:**
> Se nessuno è a casa → Spegni il climatizzatore

**Rientro a casa:**
> Quando la geolocalizzazione rileva il rientro → Accendi climatizzatore, modalità auto, setpoint 22°C

Per creare uno scenario, vai su **Strumenti → Scenari** e usa i comandi dei dispositivi Daikin come azioni o condizioni.

---

## Modificare i comandi

I comandi sono generati automaticamente dal plugin. Puoi:

- Rinominare un comando
- Modificarne la visibilità
- Modificarne l'unità o l'icona

> **Attenzione:** evita di eliminare i comandi generati automaticamente. Potrebbero essere ricreati alla prossima sincronizzazione.

---

[Precedente: Autenticazione]({{ site.baseurl }}/it_IT/authentification.html) — [Successivo: Limiti e buone pratiche]({{ site.baseurl }}/it_IT/quota-api.html)
