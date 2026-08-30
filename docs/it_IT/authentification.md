---
layout: default
title: Autenticazione - Daikin ONECTA
---

# Autenticazione

Per consentire a Jeedom di controllare i tuoi dispositivi Daikin, il plugin deve connettersi al tuo account cloud Daikin ONECTA. Sono disponibili due modalità di connessione.

## Quale modalità scegliere?

| | Developer Portal | Mobile App |
|---|-------------------|-----------|
| **Credenziali** | Client ID + Client Secret | Email Onecta + password |
| **Stesso account dell'app mobile?** | No (account sviluppatore separato) | Sì |
| **Quota giornaliera** | 200 richieste | 3000 richieste |
| **Aggiornamenti in tempo reale** | No | Sì |
| **Configurazione** | Procedura OAuth | Semplice (2 campi) |

---

## Modalità Mobile App

Questa modalità utilizza lo stesso account dell'app **Daikin Onecta** o **Daikin Residential Controller** sul telefono.

### Configurazione

1. Apri **Plugin → Daikin ONECTA → Configurazione**.
2. Seleziona **Mobile App**.
3. Inserisci la tua **email Onecta**.
4. Inserisci la tua **password Onecta**.
5. Fai clic su **Salva**.
6. Avvia o riavvia il servizio del plugin.

### Come funziona

- La connessione avviene automaticamente all'avvio del plugin.
- La password è archiviata in modo sicuro in Jeedom.
- Gli aggiornamenti in tempo reale sono attivati per impostazione predefinita, rendendo il plugin molto reattivo senza consumare molta quota.

### Risoluzione problemi

- Verifica di poter accedere all'app Daikin Onecta sul telefono con le stesse credenziali.
- Se hai cambiato la password Onecta, aggiornala nella configurazione del plugin.
- Riavvia il servizio del plugin dopo ogni modifica.

---

## Modalità Developer Portal

Questa modalità utilizza un'applicazione creata sul [portale sviluppatori Daikin](https://developer.cloud.daikineurope.com/). Quota: 200 richieste/giorno. La procedura OAuth è necessaria per la prima connessione.

### Configurazione iniziale

1. Apri **Plugin → Daikin ONECTA → Configurazione**.
2. Seleziona **Developer Portal (OAuth)**.
3. Inserisci il **Client ID** e il **Client Secret** della tua applicazione.
4. Fai clic su **Salva**.

### Tutorial per la prima connessione

#### Passo 1: Creare un account sviluppatore

Vai su [developer.cloud.daikineurope.com](https://developer.cloud.daikineurope.com/) e accedi.

#### Passo 2: Accedere alle applicazioni

Fai clic sul tuo indirizzo email in alto a destra, quindi scegli **My Apps**.

#### Passo 3: Creare un'applicazione

Fai clic su **New App**, assegna un nome (ad es. « Jeedom ») e conferma.

#### Passo 4: Copiare le credenziali

Copia il **Client ID** e il **Client Secret** nella configurazione del plugin, quindi salva.

#### Passo 5: Avviare il plugin e ottenere l'URL

1. Avvia il servizio del plugin.
2. Apri i **log** del plugin (**Analisi → Log**, filtro `daikinRCCloud`).
3. Copia l'**URL di autenticazione** mostrato nei log.

#### Passo 6: Configurare l'URL di reindirizzamento

1. Torna al portale sviluppatori Daikin.
2. Modifica la tua applicazione.
3. Incolla l'URL copiato nel campo **Redirect URI**.
4. Fai clic su **Update**.

#### Passo 7: Autorizzare l'accesso

1. Apri l'URL di autenticazione in un browser.
2. Accetta il certificato se il browser lo mostra.
3. Segui la procedura di autorizzazione Daikin.

#### Passo 8: Verificare

Un messaggio di successo conferma che la connessione è stabilita. I tuoi dispositivi dovrebbero apparire in Jeedom.

### Risoluzione problemi

- Se Daikin invalida la tua chiave API, riavvia la procedura di connessione dal passo 5.
- Verifica che la porta di autenticazione (predefinito: 8765) non sia bloccata da un firewall.
- Consulta il [tutorial della community Jeedom](https://community.jeedom.com/t/onecta-cloud-api-key-invalidation-action-required/127311/33?u=thibaut_t) se la tua chiave è stata invalidata.

---

[Precedente: Configurazione]({{ site.baseurl }}/it_IT/configuration.html) — [Successivo: Utilizzo]({{ site.baseurl }}/it_IT/utilisation.html)
