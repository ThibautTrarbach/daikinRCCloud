---
layout: default
title: Configurazione - Daikin ONECTA
---

# Configurazione

La pagina di configurazione si trova in **Plugin → Daikin ONECTA → Configurazione**.

La maggior parte degli utenti deve modificare solo la sezione **Connessione Daikin**. Le altre impostazioni sono accessibili tramite la casella **Configurazione avanzata**.

---

## Connessione Daikin

È la sezione più importante. Permette di collegare Jeedom al tuo account Daikin.

### Modalità di autenticazione

Sono disponibili due modalità:

| Modalità | Per chi? | Quota giornaliera |
|----------|----------|-------------------|
| **Mobile App** (consigliata) | Utenti con l'applicazione Daikin Onecta | 3000 interrogazioni/giorno |
| **Developer Portal** | Utenti avanzati che hanno creato un'applicazione sul portale sviluppatori Daikin | 200 interrogazioni/giorno |

> **Consigliato:** scegli **Mobile App** e usa le stesse credenziali dell'applicazione Daikin Onecta sul telefono.

Consulta la pagina [Autenticazione]({{ site.baseurl }}/it_IT/authentification.html) per i dettagli di ciascuna modalità.

### Credenziali

A seconda della modalità scelta:

- **Mobile App:** inserisci la tua **email Onecta** e la tua **password Onecta**.
- **Developer Portal:** inserisci il **Client ID** e il **Client Secret** della tua applicazione Daikin Developer.

### Quota API giornaliera

Questo campo mostra il numero massimo di interrogazioni cloud autorizzate al giorno in base alla modalità di connessione. È calcolato automaticamente e non è modificabile.

Per capire cosa implica concretamente, consulta [Limiti e buone pratiche]({{ site.baseurl }}/it_IT/quota-api.html).

### Versioni

Le versioni del plugin e del servizio interno sono visualizzate in sola lettura. Indicale se chiedi aiuto sul forum.

---

## Configurazione avanzata

Seleziona **Configurazione avanzata** per visualizzare le impostazioni aggiuntive. **La maggior parte degli utenti può lasciare i valori predefiniti.**

### Frequenza di aggiornamento

Queste impostazioni determinano la frequenza con cui Jeedom interroga il cloud Daikin per conoscere lo stato dei dispositivi.

| Impostazione | Predefinito | Descrizione |
|--------------|-------------|-------------|
| **Intervallo diurno** | 15 min | Frequenza di verifica tra mattina e sera |
| **Intervallo notturno** | 30 min | Frequenza di verifica durante la notte (risparmia quota) |
| **Inizio notte** | 22:00 | Ora in cui inizia il periodo notturno |
| **Fine notte** | 7:00 | Ora in cui termina il periodo notturno |

> **Consiglio:** con la modalità Mobile App e gli aggiornamenti in tempo reale attivati, puoi aumentare questi intervalli senza perdere reattività.

Il campo **Numero di richieste pianificate/giorno** stima quante interrogazioni GET il daemon pianificherà ogni giorno (polling + statistiche energia), tenendo conto della modalità di autenticazione e del WebSocket. L'aggiornamento è automatico quando modifichi le impostazioni. I comandi e i refresh post-azione si aggiungono a questa stima.

### Comportamento dopo un comando

Quando invii un comando (cambiare la temperatura, accendere la clim…), il plugin può reagire in tre modi:

| Modalità | Comportamento | Quando usarla |
|----------|---------------|---------------|
| **1 — Refresh completo differito** | Attende poi verifica lo stato reale presso Daikin | Se vuoi una conferma sistematica dal cloud |
| **2 — Aggiornamento immediato** | Aggiorna Jeedom subito, senza verificare Daikin | Per risparmiare quota, se la reattività basta |
| **3 — Ibrido** (predefinito) | Aggiornamento immediato + verifica Daikin dopo un ritardo | **Consigliato** — buon equilibrio reattività / affidabilità |

**Ritardo di refresh:** nelle modalità 1 e 3, tempo di attesa prima della verifica presso Daikin (predefinito: 60 secondi). Lascia questo ritardo se i dispositivi impiegano un po' a reagire.

**Strategia di verifica:**

| Strategia | Descrizione |
|-----------|-------------|
| **Fusione con la sincronizzazione** (predefinito) | Se una sincronizzazione pianificata arriva presto, il plugin attende invece di fare una richiesta aggiuntiva |
| **Verifica dedicata** | Il plugin interroga Daikin specificamente dopo ogni comando |
| **Nessuna verifica** | Nessuna interrogazione cloud dopo un comando |

**Refresh statistiche energia:** ora quotidiana (predefinito 23:58) in cui il plugin aggiorna i contatori di consumo kWh.

### Supporto automatico dei modelli

| Opzione | Predefinito | Descrizione |
|---------|-------------|-------------|
| **Modelli sconosciuti** | Attivato | Permette di controllare automaticamente i modelli Daikin non elencati esplicitamente |
| **Sensori in sola lettura** | Attivato | Mostra temperature esterne, diagnostiche, ecc. |
| **Pubblicazione se variazione** | Attivato | Aggiorna Jeedom solo quando un valore è effettivamente cambiato |

Disattiva **Modelli sconosciuti** solo se riscontri un comportamento anomalo con un dispositivo non riconosciuto.

### Opzioni aggiuntive

| Opzione | Predefinito | Descrizione |
|---------|-------------|-------------|
| **WebSocket tempo reale** | Attivato | Riceve i cambiamenti di stato in diretta (solo modalità Mobile App) |
| **Porta di autenticazione** | 8765 | Porta locale per la connessione Developer Portal |
| **Prefisso MQTT** | daikinToMQTT | Lasciare predefinito salvo conflitto con un altro plugin |

---

## Impostazioni esperte

> **Modifica queste impostazioni solo se il supporto te lo chiede o se sai perché.**

### Trasporto HTTP

Se il plugin non riesce a comunicare con Daikin (errori di rete ripetuti, blocco da firewall), passa da **Node.js** a **curl**. Usa un altro motore di rete che a volte aggira i blocchi.

### Configurazione delle dipendenze

Permette di scegliere quale versione del servizio interno del plugin viene installata (branch o versione precisa). **Lascia i valori predefiniti** (`release-beta`) salvo indicazione contraria del supporto.

Dopo ogni modifica, rilancia l'installazione delle dipendenze.

---

[Precedente: Installazione]({{ site.baseurl }}/it_IT/installation.html) — [Successivo: Autenticazione]({{ site.baseurl }}/it_IT/authentification.html)
