---
layout: default
title: Autenticazione - Daikin ONECTA
---

# Autenticazione

Per permettere a Jeedom di controllare i dispositivi Daikin, il plugin deve connettersi al tuo account cloud Daikin ONECTA. Sono disponibili due modalità di connessione.

## Quale modalità scegliere?

| | Mobile App | Developer Portal |
|---|-----------|------------------|
| **Consigliato per** | Tutti gli utenti | Sviluppatori, test avanzati |
| **Credenziali** | Email + password Onecta | Client ID + Client Secret |
| **Stesso account dell'app mobile?** | Sì | No (account sviluppatore separato) |
| **Quota giornaliera** | 3000 interrogazioni | 200 interrogazioni |
| **Aggiornamenti in tempo reale** | Sì | No |
| **Configurazione** | Semplice (2 campi) | Complessa (procedura OAuth) |

> **Consigliato:** usa la modalità **Mobile App** con le credenziali della tua applicazione Daikin Onecta.

---

## Modalità Mobile App (consigliata)

È la modalità più semplice. Usa lo stesso account dell'applicazione **Daikin Onecta** o **Daikin Residential Controller** sul telefono.

### Configurazione

1. Apri **Plugin → Daikin ONECTA → Configurazione**.
2. Seleziona **Mobile App (consigliata)**.
3. Inserisci la tua **email Onecta**.
4. Inserisci la tua **password Onecta**.
5. Clicca su **Salva**.
6. Avvia o riavvia il servizio del plugin.

### Funzionamento

- La connessione avviene automaticamente all'avvio del plugin.
- La password è memorizzata in modo sicuro in Jeedom.
- Gli aggiornamenti in tempo reale sono attivati per impostazione predefinita, rendendo il plugin molto reattivo senza consumare molta quota.

### In caso di problema

- Verifica di poterti connettere all'applicazione Daikin Onecta sul telefono con le stesse credenziali.
- Se hai cambiato la password Onecta, aggiornala nella configurazione del plugin.
- Riavvia il servizio del plugin dopo ogni modifica.

---

## Modalità Developer Portal

Questa modalità è rivolta agli utenti che hanno creato un'applicazione sul [portale sviluppatori Daikin](https://developer.cloud.daikineurope.com/). Offre una quota più limitata (200 interrogazioni/giorno) e richiede una procedura di connessione più lunga.

### Configurazione iniziale

1. Apri **Plugin → Daikin ONECTA → Configurazione**.
2. Seleziona **Developer Portal (OAuth)**.
3. Inserisci il **Client ID** e il **Client Secret** della tua applicazione.
4. Clicca su **Salva**.

### Tutorial per la prima connessione

#### Passo 1: Creare un account sviluppatore

Vai su [developer.cloud.daikineurope.com](https://developer.cloud.daikineurope.com/) e accedi.

#### Passo 2: Accedere alle tue applicazioni

Clicca sul tuo indirizzo email in alto a destra, poi scegli **My Apps**.

#### Passo 3: Creare un'applicazione

Clicca su **New App**, assegna un nome (es. « Jeedom ») e conferma.

#### Passo 4: Copiare le credenziali

Copia il **Client ID** e il **Client Secret** nella configurazione del plugin, poi salva.

#### Passo 5: Avviare il plugin e recuperare l'URL

1. Avvia il servizio del plugin.
2. Apri i **log** del plugin (**Analisi → Log**, filtro `daikinRCCloud`).
3. Copia l'**URL di autenticazione** visualizzato nei log.

#### Passo 6: Configurare l'URL di reindirizzamento

1. Torna sul portale sviluppatori Daikin.
2. Modifica la tua applicazione.
3. Incolla l'URL copiato nel campo **Redirect URI**.
4. Clicca su **Update**.

#### Passo 7: Autorizzare l'accesso

1. Apri l'URL di autenticazione in un browser.
2. Accetta il certificato se il browser lo mostra.
3. Segui la procedura di autorizzazione Daikin.

#### Passo 8: Verificare

Un messaggio di successo conferma che la connessione è stabilita. I dispositivi dovrebbero apparire in Jeedom.

### In caso di problema

- Se Daikin invalida la tua chiave API, ripeti la procedura di connessione dal passo 5.
- Verifica che la porta di autenticazione (predefinito: 8765) non sia bloccata da un firewall.
- Consulta il [tutorial sulla community Jeedom](https://community.jeedom.com/t/onecta-cloud-api-key-invalidation-action-required/127311/33?u=thibaut_t) in caso di invalidazione della chiave.

---

[Precedente: Configurazione]({{ site.baseurl }}/it_IT/configuration.html) — [Successivo: Utilizzo]({{ site.baseurl }}/it_IT/utilisation.html)
