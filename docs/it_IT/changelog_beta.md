---
layout: default
title: Changelog Daikin ONECTA (Beta)
---

# Changelog Daikin ONECTA

Tutte le modifiche notevoli di questo plugin saranno documentate su questa pagina.

## [0.10.3] - 2026-09-01

> Daemon fornito dal branch `release-beta`: **2.1.5** (minimo richiesto: 2.0.0)

### Corretto — Daemon
- BRP069B4x / BRP069A4x: copertura statica completa (`gatewayDiagnosticsPack`, `auxiliaryUnitPack`) — [#42](https://github.com/ThibautTrarbach/daikinRCCloud/issues/42)
- BRP069A78: diagnostica gateway, offset acqua di mandata, accumulo ACS, unità hydro/outdoor/UI — [#41](https://github.com/ThibautTrarbach/daikinRCCloud/issues/41)
- Rapporto di debug: mascheramento del numero di serie (`serialNumber`)

---

## [0.10.2] - 2026-09-01

> Daemon fornito dal branch `release-beta`: **2.1.3** (minimo richiesto: 2.0.0)

### Aggiunto — Plugin
- Pannello diagnostico supporto arricchito: messaggio di supporto, management points, unità rilevate, datapoint non mappati, rapporto di debug con pulsante copia
- Link GitHub per segnalare dispositivi non supportati (`githubIssueUrl`)
- Sincronizzazione metadati supporto daemon → configurazione equipaggiamento
- Pulizia automatica dei comandi supporto obsoleti (logical IDs `_supportStatus`, `_configCoverage`, ecc.)

### Aggiunto — Daemon
- `gatewayDiagnosticsPack` e `auxiliaryUnitPack`: sensori rete/diagnostica gateway e unità indoor/outdoor (BRP069C4x)
- Mapping read-only `isPowerfulModeActive` (API firmware 2.6.x)
- Modulo metadati supporto: rapporto di debug, URL issue GitHub, comandi supporto sincronizzati

### Corretto — Daemon
- Audit copertura API: normalizzazione percorsi datapoint, conteggio metadati `_device`
- Preset mode Home Assistant: fallback su `_isPowerfulModeActive` se `_powerfulMode` è assente

### Modificato — Daemon
- Arricchimento `_device`: `ipAddress`, `macAddress`, alias `ssid`
- Rapporto di debug: messaggio di supporto, indicatore di troncamento datapoint non mappati

---

## [0.10.1] - 2026-09-01

> Daemon fornito dal branch `release-beta`: **2.1.2** (minimo richiesto: 2.0.0)

### Aggiunto — Plugin
- Avvisi di stato del supporto: notifica in caso di supporto parziale o configurazione incompleta di un dispositivo Daikin (banner UI + informazioni diagnostiche)
- Pulizia degli equipaggiamenti fantasma: rimozione automatica degli ID logici creati da topic MQTT interni (ponte di sistema)
- Notifiche di autenticazione: richiesta di validazione OAuth con messaggio utente e gestione della scadenza del timeout

### Aggiunto — Daemon
- Timeout di autorizzazione OAuth configurabile (`authorizationTimeoutSeconds`, 60–3600 s)
- Pulizia all'avvio dei topic MQTT retained obsoleti
- WebSocket: supporto delle caratteristiche annidate e dei riferimenti negli aggiornamenti push

### Modificato
- Chiarimento della documentazione: distinzione tra il ponte Daikin2MQTT e i dispositivi Daikin reali
- Arresto controllato del daemon rafforzato (skip delle operazioni durante l'arresto)
- Gestione rate-limit migliorata: fusione degli aggiornamenti parziali, conservazione dei valori precedenti

---

## [0.10.0] - 2026-08-30

> Daemon fornito dal branch `release-beta`: **2.1.0** (minimo richiesto: 2.0.0)

### Cambiamento importante
- Il daemon V1 (< 2.0.0) non è più supportato — migrazione obbligatoria al daemon V2
- **Reinstallazione delle dipendenze richiesta** dopo l'aggiornamento del plugin
- Branch predefinito delle dipendenze: `release-beta`
- Migrazione automatica dei branch V1 (`release-stable`, `dev`, `release-dev`, ecc.) verso `release-beta`
- Prerequisiti: Jeedom 4.4+, plugin **mqtt2** installato e avviato

### Aggiunto — Plugin
- Riprogettazione completa della pagina di configurazione (sezioni richiudibili, modalità « Configurazione avanzata » memorizzata)
- Visualizzazione delle versioni plugin e daemon (utile per il supporto della community)
- Modalità **Mobile App**: connessione con email/password Onecta, quota ~3000 req/g
- Modalità **Developer Portal**: OAuth Client ID/Secret, quota ~200 req/g
- Stima automatica della quota API e delle richieste/giorno in base alla modalità di autenticazione
- Nuove impostazioni avanzate: strategia refresh post-azione, fusione con polling, coalescenza comandi, refresh statistiche energia, WebSocket, DynamicGateway, sensori in sola lettura, pubblicazione se delta, trasporto HTTP curl, prefisso MQTT
- Password Onecta memorizzata cifrata
- Verifiche mqtt2 con messaggi di errore espliciti se assente o non avviato
- Prima versione della documentazione online: installazione, configurazione, autenticazione, utilizzo, quote API, risoluzione problemi. Contenuto generato rapidamente da un'IA prima della pubblicazione — **non ancora revisionato né validato**

### Aggiunto — Daemon (tramite il plugin)
- Connessione semplificata con lo stesso account dell'applicazione Onecta (modalità Mobile App)
- Aggiornamenti in tempo reale tramite WebSocket (reattività senza consumare la quota API)
- Supporto automatico dei modelli Daikin non elencati (DynamicGateway)
- Contatori energia kWh aggiornati quotidianamente a ora configurabile
- Risposta immediata in Jeedom dopo un comando (pubblicazione ottimistica MQTT)
- Risparmio quota: fusione refresh/polling, polling adattivo in base al budget API, skip se WebSocket conferma la modifica
- Aggiramento dei blocchi rete/WAF Daikin (trasporto HTTP curl)
- Stato del budget API visibile sul bridge di sistema MQTT

### Modificato
- Interfaccia di autenticazione: commutazione dinamica Mobile App / Developer Portal con quote visualizzate
- Predefiniti polling: 15 min (giorno) / 30 min (notte); ritardo refresh post-azione: 60 s (invece di 120 s)
- Installazione daemon più affidabile: esecuzione tramite `main.js` compilato, verifiche all'installazione
- Messaggi di errore espliciti (mqtt2 assente, daemon troppo vecchio, `main.js` non trovato)
- ~90 nuove traduzioni UI (FR, EN, ES, DE, IT)
- Ramificazione versionata conservata con stub V3 commentati per le future evoluzioni

### Rimosso
- Supporto del daemon V1 (< 2.0.0) e autenticazione OAuth tramite MQTT
- Codice V1 del plugin (configurazione, messaggi MQTT legacy)
- Branch dipendenze obsoleti: `release-stable`, `dev`, `release-dev`

---

## [0.9.3] - 2025-12-10

### Migliorato
- Miglioramento del processo di aggiornamento del plugin
- Miglioramento dei log del plugin con traduzione
- Correzione della gestione di valori vuoti nella pagina di configurazione
- Migliore gestione degli errori nella pagina di configurazione e nelle attività eseguite durante l'installazione o l'aggiornamento del plugin

---

## [0.9.2] - 2025-12-09

### Corretto
- Correzione di un bug di aggiornamento della configurazione

### Cambiamento importante
- Il plugin ora richiede Jeedom 4.4 come minimo

---

## [0.9.1] - 2025-12-05

### Informazioni
- Il Daemon 2.0.x non è ancora disponibile correttamente. Nei prossimi giorni, vi proporremo di testarlo in modalità alpha per evitare di impattare la vostra produzione durante il periodo di riscaldamento

### Aggiunto
- Configurazione delle dipendenze: possibilità di scegliere il branch o il commit da installare
- Supporto per parametri di polling (giorno/notte) per daemon 2.0.0+
- Supporto per modalità di aggiornamento dopo azione per daemon 2.0.0+
- Compatibilità con versioni V1 e V2 del daemon
- Internazionalizzazione completa (FR, EN, ES, DE, IT)
- Link alla futura documentazione e changelog

### Modificato
- Miglioramento della gestione dei messaggi MQTT
- Ottimizzazione della creazione delle apparecchiature

### Corretto
- Varie correzioni di bug

---

## [0.9.0] - Nell'anno 2022 (Nessun ricordo della data)

### Aggiunto
- Versione iniziale del plugin
