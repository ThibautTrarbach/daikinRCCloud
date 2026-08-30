---
layout: default
title: Installazione - Daikin ONECTA
---

# Installazione

Questa guida ti accompagna passo dopo passo per installare il plugin e far apparire i tuoi dispositivi Daikin in Jeedom.

## Prima di iniziare

Assicurati di avere:

- Jeedom **4.4** o più recente
- Un **account Daikin Onecta** (lo stesso dell'applicazione mobile)
- I tuoi dispositivi Daikin già configurati e visibili nell'applicazione Onecta

## Passo 1: Installare il plugin

1. Apri **Plugin → Gestione plugin** in Jeedom.
2. Cerca **Daikin ONECTA**.
3. Clicca su **Installa**, poi **Attiva**.

## Passo 2: Installare il plugin mqtt2

Il plugin **mqtt2** è **obbligatorio**. Funge da relè interno tra il plugin Daikin e i tuoi dispositivi. In genere, non devi configurare nulla di particolare al suo interno.

1. Installa il plugin **mqtt2** dal market Jeedom (se non lo è già).
2. Attivalo.
3. Verifica in **Strumenti → Salute** che mqtt2 sia avviato.

## Passo 3: Installare le dipendenze

Il plugin ha bisogno di componenti aggiuntivi per funzionare. Jeedom li installa automaticamente:

1. Vai alla pagina del plugin **Daikin ONECTA**.
2. Clicca su **Reinstalla le dipendenze** (o tramite il pulsante dedicato nella gestione plugin).
3. Attendi la fine dell'installazione (può richiedere alcuni minuti).

> **Attenzione:** dopo ogni aggiornamento del plugin, rilancia l'installazione delle dipendenze se Jeedom te lo chiede.

## Passo 4: Configurare la connessione Daikin

1. Apri **Plugin → Daikin ONECTA → Configurazione**.
2. Scegli la modalità **Mobile App (consigliata)**.
3. Inserisci l'**email** e la **password** del tuo account Daikin Onecta (le stesse dell'applicazione mobile).
4. Clicca su **Salva**.

Per maggiori dettagli sulle modalità di connessione, consulta la pagina [Autenticazione]({{ site.baseurl }}/it_IT/authentification.html).

## Passo 5: Avviare il servizio del plugin

1. Vai in **Strumenti → Salute** (o sulla pagina del plugin).
2. Avvia il **daemon** del plugin Daikin ONECTA.
3. Verifica che lo stato indichi **Avviato**.

## Passo 6: Verificare i dispositivi

1. Torna alla pagina **Plugin → Daikin ONECTA**.
2. I tuoi dispositivi Daikin dovrebbero apparire sotto **I miei dispositivi**.

Se nessun dispositivo appare, consulta la pagina [Risoluzione problemi]({{ site.baseurl }}/it_IT/depannage.html).

## E poi?

- [Configurare le impostazioni del plugin]({{ site.baseurl }}/it_IT/configuration.html)
- [Imparare a usare i dispositivi]({{ site.baseurl }}/it_IT/utilisation.html)

---

[Precedente: Home]({{ site.baseurl }}/it_IT/) — [Successivo: Configurazione]({{ site.baseurl }}/it_IT/configuration.html)
