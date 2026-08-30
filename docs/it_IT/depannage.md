---
layout: default
title: Risoluzione problemi - Daikin ONECTA
---

# Risoluzione problemi

Questa pagina risponde ai problemi più frequenti riscontrati con il plugin Daikin ONECTA.

## I miei dispositivi non appaiono

**Verifiche da fare nell'ordine:**

1. Il **servizio del plugin** è avviato? (Strumenti → Salute)
2. Il plugin **mqtt2** è installato, attivato e avviato?
3. Le **dipendenze** sono installate? (pulsante « Reinstalla le dipendenze »)
4. Le tue **credenziali Daikin** sono corrette? (prova sull'applicazione Onecta mobile)
5. I dispositivi sono visibili nell'applicazione **Daikin Onecta** sul telefono?

**Azioni:**

- Riavvia il servizio del plugin.
- Salva nuovamente la configurazione, poi riavvia.
- Consulta i log (vedi sotto).

## Il servizio del plugin non si avvia

**Cause frequenti:**

| Causa | Soluzione |
|-------|-----------|
| Dipendenze non installate | Clicca su « Reinstalla le dipendenze » |
| Credenziali Daikin errate | Verifica email/password o Client ID/Secret |
| mqtt2 non avviato | Avvia il plugin mqtt2 |

Dopo la correzione, riavvia il servizio del plugin.

## Errore di connessione a Daikin

### Modalità Mobile App

- Verifica di poterti connettere all'applicazione Daikin Onecta sul telefono.
- Se hai cambiato la password, aggiornala nella configurazione del plugin.
- Riavvia il servizio dopo la modifica.

### Modalità Developer Portal

- Verifica che Client ID e Client Secret siano corretti.
- Se Daikin ha invalidato la chiave, ripeti la procedura di connessione (vedi [Autenticazione]({{ site.baseurl }}/it_IT/authentification.html)).
- Verifica che la porta di autenticazione (predefinito: 8765) non sia bloccata.

## I miei comandi non reagiscono

1. Il dispositivo è **attivato** in Jeedom?
2. C'è un **codice errore** visualizzato sul dispositivo? (scheda Dispositivo)
3. Il servizio del plugin è ancora **avviato**?
4. La quota giornaliera è **esaurita**? (vedi [Limiti e buone pratiche]({{ site.baseurl }}/it_IT/quota-api.html))

Prova a inviare lo stesso comando dall'applicazione Daikin Onecta sul telefono. Se non funziona neanche lì, il problema proviene dal dispositivo o dal cloud Daikin, non da Jeedom.

## Pochi comandi sul mio dispositivo

I comandi dipendono dal modello del dispositivo. Se vedi pochi comandi:

1. Verifica che l'opzione **Modelli sconosciuti** sia attivata (Configurazione avanzata).
2. Attendi alcuni minuti dopo l'avvio del plugin — la sincronizzazione può richiedere tempo.
3. Riavvia il servizio del plugin.

Se il modello è molto recente, segnalalo sul [forum Jeedom](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55) per migliorare il supporto.

## Errori di rete ripetuti

Se il plugin mostra errori di connessione al cloud Daikin (timeout, blocchi):

1. Apri **Plugin → Daikin ONECTA → Configurazione → Configurazione avanzata**.
2. Passa il **Trasporto HTTP** da « Node.js » a « curl ».
3. Salva e riavvia il servizio.

## Consultare i log

Per diagnosticare un problema:

1. Vai in **Analisi → Log** in Jeedom.
2. Filtra per plugin: `daikinRCCloud`.
3. Cerca i messaggi di errore (in rosso).

## Chiedere aiuto

Se non trovi la soluzione, chiedi aiuto indicando:

| Informazione | Dove trovarla |
|--------------|---------------|
| Versione del plugin | Configurazione → Informazioni |
| Versione del servizio interno | Configurazione → Informazioni |
| Versione di Jeedom | Pagina principale Jeedom |
| Modalità di connessione usata | Configurazione → Modalità di autenticazione |
| Descrizione del problema | Cosa ti aspettavi vs cosa succede |
| Estratto dei log | Analisi → Log, filtro daikinRCCloud |

### Forum Jeedom

- [Thread di discussione del plugin](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55)
- Su Jeedom 4.4+, usa il pulsante **Crea un post Community** sulla pagina del plugin per precompilare un modulo di aiuto.

### GitHub

Per segnalare un bug: [Issues GitHub](https://github.com/ThibautTrarbach/daikinRCCloud/issues)

---

[Precedente: Limiti e buone pratiche]({{ site.baseurl }}/it_IT/quota-api.html) — [Torna alla home]({{ site.baseurl }}/it_IT/)
