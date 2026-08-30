---
layout: default
title: Utilizzo - Daikin ONECTA
---

# Utilizzo

Questa pagina spiega come usare i dispositivi Daikin quotidianamente in Jeedom: trovare i dispositivi, comprendere i comandi disponibili, visualizzarli sulla dashboard e integrarli negli scenari.

## Trovare i dispositivi

1. Apri **Plugin → Daikin ONECTA**.
2. I dispositivi appaiono sotto **I miei dispositivi**, sotto forma di schede.

Vengono aggiunti **automaticamente** alla prima sincronizzazione. Non c'è un pulsante « Aggiungi dispositivo ».

### Personalizzare un dispositivo

Clicca sulla scheda del dispositivo per accedere alla sua configurazione:

| Impostazione | Descrizione |
|--------------|-------------|
| **Nome** | Rinomina il dispositivo (es. « Clima soggiorno ») |
| **Oggetto padre** | Collegalo a una stanza o a un piano della domotica |
| **Categoria** | Riscaldamento, comfort, ecc. |
| **Attiva** | Attiva o disattiva il dispositivo |
| **Visibile** | Mostra o nasconde il dispositivo nella dashboard |

### Informazioni sul dispositivo

Nella scheda **Dispositivo**, un riquadro mostra le informazioni inviate da Daikin:

| Informazione | Utilità |
|--------------|---------|
| **Modello** | Riferimento del dispositivo |
| **Numero di serie** | Identificativo univoco |
| **Versione firmware** | Versione del software integrato |
| **Codice errore** | Codice di allerta eventuale — consulta il manuale del dispositivo o il supporto Daikin in caso di codice visualizzato |

---

## Comandi disponibili

I comandi vengono creati automaticamente in base al modello del dispositivo. Si trovano nella scheda **Comandi** di ogni dispositivo.

> **Nota:** non tutti i comandi elencati di seguito sono necessariamente disponibili sul tuo dispositivo. Dipende dal modello.

### Controllo principale

| Cosa vuoi fare | Comando in Jeedom |
|----------------|-------------------|
| Accendere / spegnere | **State** (o Marche) |
| Cambiare modalità (freddo, caldo, auto, deumidificazione, ventilatore) | **Operation Mode** |
| Impostare la temperatura desiderata | **Temperature Control** |

### Ventilazione

| Cosa vuoi fare | Comando in Jeedom |
|----------------|-------------------|
| Scegliere la modalità di ventilazione | **Fan Current Mode** |
| Regolare la velocità del ventilatore | **Fan Fixed** |
| Orientare il flusso d'aria orizzontalmente | **Fan Horizontal** (a seconda del modello) |
| Orientare il flusso d'aria verticalmente | **Fan Vertical** (a seconda del modello) |

### Modalità speciali

| Cosa vuoi fare | Comando in Jeedom |
|----------------|-------------------|
| Attivare la modalità economica | **Eco Mode** |
| Attivare la modalità potente | **Powerful Mode** |
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
| Consumo raffreddamento (giorno / settimana / mese) | **Cooling Consumption D/W/M** |

I contatori vengono aggiornati automaticamente ogni giorno (verso le 23:58 per impostazione predefinita).

### Programmazioni e modalità avanzate

| Cosa vuoi fare | Comando in Jeedom |
|----------------|-------------------|
| Attivare / disattivare una programmazione Onecta | **Schedule** (a seconda del modello) |
| Attivare la modalità vacanza | **Preset Away** (a seconda del modello) |
| Avviare un aggiornamento firmware | **Firmware Update** (se proposto da Daikin) |

---

## Dispositivi multizona

Alcuni modelli, in particolare le pompe di calore **Altherma**, dispongono di più zone indipendenti. In questo caso, vedrai comandi separati per ogni zona:

- **Zona 1**: riscaldamento principale
- **Zona 2**: acqua calda sanitaria, o seconda zona di riscaldamento

Ogni zona possiede i propri comandi di accensione, modalità e setpoint.

---

## Visualizzare sulla dashboard

Per controllare i dispositivi dalla schermata principale di Jeedom:

1. Apri il dispositivo e vai alla scheda **Comandi**.
2. Rendi **visibili** i comandi che desideri mostrare (casella nella colonna Opzioni).
3. Aggiungili alla dashboard tramite il configuratore di design Jeedom.

I comandi binari (accensione/spegnimento) si visualizzano come pulsanti. I setpoint di temperatura si visualizzano come cursore (slider).

**Consiglio:** per un uso quotidiano, rendi visibili almeno **State**, **Operation Mode** e **Temperature Control**.

---

## Usare negli scenari

I dispositivi Daikin possono essere integrati in qualsiasi scenario Jeedom, come gli altri dispositivi.

### Esempi

**Comfort estivo:**
> Se temperatura soggiorno > 26°C → Accendere la clim in modalità raffreddamento, setpoint 24°C

**Modalità notte:**
> Alle 22:00 → Attivare la modalità Eco su tutti i condizionatori

**Partenza in vacanza:**
> Quando la modalità « Assenza » è attivata → Attivare Preset Away sulla pompa di calore

**Risparmio energetico:**
> Se nessuno è in casa → Spegnere la climatizzazione

**Ritorno a casa:**
> Quando la geolocalizzazione rileva un ritorno → Accendere la clim, modalità auto, setpoint 22°C

Per creare uno scenario, vai in **Strumenti → Scenari** e usa i comandi dei dispositivi Daikin come azioni o condizioni.

---

## Modificare i comandi

I comandi sono generati automaticamente dal plugin. Puoi:

- Rinominare un comando
- Cambiarne la visibilità
- Modificarne unità o icona

> **Attenzione:** evita di eliminare comandi generati automaticamente. Possono essere ricreati alla prossima sincronizzazione.

---

[Precedente: Autenticazione]({{ site.baseurl }}/it_IT/authentification.html) — [Successivo: Limiti e buone pratiche]({{ site.baseurl }}/it_IT/quota-api.html)
