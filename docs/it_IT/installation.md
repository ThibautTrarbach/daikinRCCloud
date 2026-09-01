---
layout: default
title: Installazione - Daikin ONECTA
---

# Installazione

Questa guida illustra passo dopo passo l'installazione del plugin e la visualizzazione dei dispositivi Daikin in Jeedom.

## Prima di iniziare

Assicurati di disporre di:

- Jeedom **4.4** o versione successiva
- Accesso al cloud Daikin: **Developer Portal** (Client ID / Client Secret) o **Mobile App** (email / password Onecta) — vedi [Autenticazione]({{ site.baseurl }}/it_IT/authentification.html)
- I tuoi dispositivi Daikin già configurati e visibili nell'app Onecta

## Passo 1: Installare il plugin

1. Apri **Plugin → Gestione plugin** in Jeedom.
2. Cerca **Daikin ONECTA**.
3. Fai clic su **Installa**, quindi su **Attiva**.

## Passo 2: Installare il plugin mqtt2

Il plugin **mqtt2** è **obbligatorio**. Funge da relè interno tra il plugin Daikin e i tuoi dispositivi. Nella maggior parte dei casi, non è necessaria alcuna configurazione particolare al suo interno.

1. Installa il plugin **mqtt2** dal market Jeedom (se non è già installato).
2. Attivalo.
3. Verifica in **Strumenti → Salute** che mqtt2 sia in esecuzione.

## Passo 3: Installare le dipendenze

Il plugin richiede componenti aggiuntivi per funzionare. Jeedom li installa automaticamente:

1. Vai alla pagina del plugin **Daikin ONECTA**.
2. Fai clic su **Reinstalla dipendenze** (o usa il pulsante dedicato nella gestione plugin).
3. Attendi il completamento dell'installazione (può richiedere alcuni minuti).

> **Nota:** dopo ogni aggiornamento del plugin, reinstalla le dipendenze se Jeedom te lo chiede.

## Passo 4: Configurare la connessione Daikin

1. Apri **Plugin → Daikin ONECTA → Configurazione**.
2. Scegli la modalità di autenticazione: **Developer Portal (OAuth)** o **Mobile App**.
3. Inserisci le credenziali corrispondenti (Client ID / Client Secret, o email / password Onecta).
4. Fai clic su **Salva**.
5. Avvia il servizio del plugin. Per la modalità Developer Portal, segui la procedura OAuth descritta in [Autenticazione]({{ site.baseurl }}/it_IT/authentification.html).

## Passo 5: Avviare il servizio del plugin

1. Vai su **Strumenti → Salute** (o sulla pagina del plugin).
2. Avvia il **daemon** del plugin Daikin ONECTA.
3. Verifica che lo stato indichi **In esecuzione**.

## Passo 6: Verificare i dispositivi

1. Torna su **Plugin → Daikin ONECTA**.
2. I tuoi dispositivi Daikin dovrebbero apparire in **I miei dispositivi**.

Se non compare alcun dispositivo, consulta la pagina [Risoluzione problemi]({{ site.baseurl }}/it_IT/depannage.html).

## Prossimi passi

- [Configurare le impostazioni del plugin]({{ site.baseurl }}/it_IT/configuration.html)
- [Scoprire come utilizzare i dispositivi]({{ site.baseurl }}/it_IT/utilisation.html)

---

[Precedente: Home]({{ site.baseurl }}/it_IT/) — [Successivo: Configurazione]({{ site.baseurl }}/it_IT/configuration.html)
