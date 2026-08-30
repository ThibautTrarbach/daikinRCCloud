---
layout: default
title: Configurazione - Daikin ONECTA
---

# Configurazione

La pagina di configurazione si trova in **Plugin → Daikin ONECTA → Configurazione**.

La maggior parte degli utenti deve modificare solo la sezione **Connessione Daikin**. Le altre impostazioni sono disponibili tramite la casella **Configurazione avanzata**.

---

## Connessione Daikin

È la sezione più importante. Collega Jeedom al tuo account Daikin.

### Modalità di autenticazione

Sono disponibili due modalità:

| Modalità | Per chi? | Quota giornaliera |
|----------|----------|-------------------|
| **Mobile App** (consigliata) | Utenti con l'app Daikin Onecta | 3000 richieste/giorno |
| **Developer Portal** | Utenti avanzati che hanno creato un'app sul portale sviluppatori Daikin | 200 richieste/giorno |

> **Consigliato:** scegli **Mobile App** e usa le stesse credenziali dell'app Daikin Onecta sul telefono.

Consulta la pagina [Autenticazione]({{ site.baseurl }}/it_IT/authentification.html) per i dettagli su ciascuna modalità.

### Credenziali

A seconda della modalità scelta:

- **Mobile App:** inserisci l'**email Onecta** e la **password Onecta**.
- **Developer Portal:** inserisci il **Client ID** e il **Client Secret** della tua applicazione Daikin Developer.

### Quota API giornaliera

Questo campo mostra il numero massimo di richieste cloud consentite al giorno in base alla modalità di connessione. Viene calcolato automaticamente e non è modificabile.

Per capire cosa significa nella pratica, consulta [Limiti e buone pratiche]({{ site.baseurl }}/it_IT/quota-api.html).

### Versioni

Le versioni del plugin e del servizio interno sono visualizzate in sola lettura. Includile quando chiedi aiuto sul forum.

---

## Configurazione avanzata

Seleziona **Configurazione avanzata** per mostrare impostazioni aggiuntive. **La maggior parte degli utenti può lasciare i valori predefiniti.**

### Frequenza di aggiornamento

Queste impostazioni determinano la frequenza con cui Jeedom interroga il cloud Daikin per lo stato dei dispositivi.

| Impostazione | Predefinito | Descrizione |
|--------------|-------------|-------------|
| **Intervallo diurno** | 15 min | Frequenza di controllo tra mattina e sera |
| **Intervallo notturno** | 30 min | Frequenza di controllo durante la notte (risparmia quota) |
| **Inizio notte** | 22:00 | Ora di inizio del periodo notturno |
| **Fine notte** | 07:00 | Ora di fine del periodo notturno |

> **Suggerimento:** con la modalità Mobile App e gli aggiornamenti in tempo reale attivati, puoi aumentare questi intervalli senza perdere reattività.

Il campo **Richieste pianificate/giorno** stima quante richieste GET il daemon programmerà ogni giorno (polling + statistiche energetiche), in base alla modalità di autenticazione e al WebSocket. Si aggiorna automaticamente quando modifichi le impostazioni. I comandi e l'aggiornamento post-azione si aggiungono a questa stima.

### Comportamento dopo un comando

Quando invii un comando (cambio temperatura, accensione climatizzatore, ecc.), il plugin può reagire in tre modi:

| Modalità | Comportamento | Quando usarla |
|----------|---------------|---------------|
| **1 — Aggiornamento completo differito** | Attende, poi verifica lo stato reale con Daikin | Se desideri una conferma sistematica dal cloud |
| **2 — Aggiornamento immediato** | Aggiorna Jeedom subito senza verificare con Daikin | Per risparmiare quota se la reattività è sufficiente |
| **3 — Ibrido** (predefinito) | Aggiornamento immediato + verifica Daikin dopo un ritardo | **Consigliato** — buon equilibrio tra reattività e affidabilità |

**Ritardo di aggiornamento:** nelle modalità 1 e 3, tempo di attesa prima della verifica con Daikin (predefinito: 60 secondi). Mantieni questo ritardo se i tuoi dispositivi impiegano un momento a reagire.

**Strategia di verifica:**

| Strategia | Descrizione |
|-----------|-------------|
| **Unione con sincronizzazione** (predefinito) | Se una sincronizzazione pianificata è imminente, il plugin attende invece di effettuare una richiesta aggiuntiva |
| **Verifica dedicata** | Il plugin interroga Daikin specificamente dopo ogni comando |
| **Nessuna verifica** | Nessuna richiesta cloud dopo un comando |

**Aggiornamento statistiche energetiche:** ora giornaliera (predefinito 23:58) in cui il plugin aggiorna i contatori di consumo in kWh.

### Supporto automatico dei modelli

| Opzione | Predefinito | Descrizione |
|---------|-------------|-------------|
| **Modelli sconosciuti** | Attivato | Controlla automaticamente i modelli Daikin non elencati esplicitamente |
| **Sensori in sola lettura** | Attivato | Mostra temperature esterne, diagnostica, ecc. |
| **Pubblica al cambiamento** | Attivato | Aggiorna Jeedom solo quando un valore è effettivamente cambiato |

Disattiva **Modelli sconosciuti** solo se riscontri un comportamento anomalo con un dispositivo non riconosciuto.

### Opzioni aggiuntive

| Opzione | Predefinito | Descrizione |
|---------|-------------|-------------|
| **WebSocket in tempo reale** | Attivato | Riceve i cambiamenti di stato in diretta (solo modalità Mobile App) |
| **Porta di autenticazione** | 8765 | Porta locale solo per la connessione Developer Portal |
| **Prefisso MQTT** | daikinToMQTT | Lascia il valore predefinito salvo conflitto con un altro plugin |

---

## Impostazioni esperte

> **Modifica queste impostazioni solo se il supporto te lo chiede o se sai perché.**

### Trasporto HTTP

Se il plugin non riesce a comunicare con Daikin (errori di rete ripetuti, firewall che blocca), passa da **Node.js** a **curl**. Utilizza un altro motore di rete che a volte aggira i blocchi.

### Configurazione delle dipendenze

Consente di scegliere quale versione del servizio interno del plugin installare (branch o versione esatta). **Lascia i valori predefiniti** (`release-beta`) salvo indicazioni contrarie del supporto.

Dopo ogni modifica, reinstalla le dipendenze.

---

[Precedente: Installazione]({{ site.baseurl }}/it_IT/installation.html) — [Successivo: Autenticazione]({{ site.baseurl }}/it_IT/authentification.html)
