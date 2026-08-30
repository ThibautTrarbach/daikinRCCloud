---
layout: default
title: Risoluzione problemi - Daikin ONECTA
---

# Risoluzione problemi

Questa pagina risponde ai problemi più comuni del plugin Daikin ONECTA.

## I miei dispositivi non compaiono

**Verifiche da eseguire nell'ordine:**

1. Il **servizio del plugin** è in esecuzione? (Strumenti → Salute)
2. Il plugin **mqtt2** è installato, attivo e in esecuzione?
3. Le **dipendenze** sono installate? (pulsante « Reinstalla dipendenze »)
4. Le **credenziali Daikin** sono corrette? (verifica sull'app mobile Onecta)
5. I dispositivi sono visibili nell'app **Daikin Onecta** sul telefono?

**Azioni:**

- Riavvia il servizio del plugin.
- Salva nuovamente la configurazione, quindi riavvia.
- Controlla i log (vedi sotto).

## Il servizio del plugin non si avvia

**Cause comuni:**

| Causa | Soluzione |
|-------|-----------|
| Dipendenze non installate | Fai clic su « Reinstalla dipendenze » |
| Credenziali Daikin errate | Verifica email/password o Client ID/Secret |
| mqtt2 non in esecuzione | Avvia il plugin mqtt2 |

Dopo la correzione, riavvia il servizio del plugin.

## Errore di connessione Daikin

### Modalità Mobile App

- Verifica di poter accedere all'app Daikin Onecta sul telefono.
- Se hai cambiato la password, aggiornala nella configurazione del plugin.
- Riavvia il servizio dopo ogni modifica.

### Modalità Developer Portal

- Verifica che Client ID e Client Secret siano corretti.
- Se Daikin ha invalidato la tua chiave, riavvia la procedura di connessione (vedi [Autenticazione]({{ site.baseurl }}/it_IT/authentification.html)).
- Verifica che la porta di autenticazione (predefinito: 8765) non sia bloccata.

## I miei comandi non rispondono

1. Il dispositivo è **attivato** in Jeedom?
2. È presente un **codice errore** sul dispositivo? (scheda Dispositivo)
3. Il servizio del plugin è ancora **in esecuzione**?
4. La **quota giornaliera è esaurita**? (vedi [Limiti e buone pratiche]({{ site.baseurl }}/it_IT/quota-api.html))

Prova a inviare lo stesso comando dall'app Daikin Onecta sul telefono. Se non funziona neanche lì, il problema riguarda il dispositivo o il cloud Daikin, non Jeedom.

## Pochi comandi sul mio dispositivo

I comandi dipendono dal modello del dispositivo. Se ne vedi pochi:

1. Verifica che **Modelli sconosciuti** sia attivato (Configurazione avanzata).
2. Attendi alcuni minuti dopo l'avvio del plugin — la sincronizzazione può richiedere un momento.
3. Riavvia il servizio del plugin.

Se il tuo modello è molto recente, segnalalo sul [forum Jeedom](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55) per migliorare il supporto.

## Errori di rete ripetuti

Se il plugin mostra errori di connessione al cloud (timeout, blocchi):

1. Apri **Plugin → Daikin ONECTA → Configurazione → Configurazione avanzata**.
2. Cambia il **trasporto HTTP** da « Node.js » a « curl ».
3. Salva e riavvia il servizio.

## Controllare i log

Per diagnosticare un problema:

1. Vai su **Analisi → Log** in Jeedom.
2. Filtra per plugin: `daikinRCCloud`.
3. Cerca messaggi di errore (in rosso).

## Chiedere aiuto

Se non trovi una soluzione, chiedi aiuto includendo:

| Informazione | Dove trovarla |
|--------------|---------------|
| Versione del plugin | Configurazione → Informazioni |
| Versione del servizio interno | Configurazione → Informazioni |
| Versione Jeedom | Pagina principale Jeedom |
| Modalità di connessione utilizzata | Configurazione → Modalità di autenticazione |
| Descrizione del problema | Cosa ti aspettavi rispetto a cosa è successo |
| Estratto dei log | Analisi → Log, filtro daikinRCCloud |

### Forum Jeedom

- [Thread di discussione del plugin](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55)
- Su Jeedom 4.4+, usa il pulsante **Crea post Community** sulla pagina del plugin per precompilare un modulo di aiuto.

### GitHub

Per segnalare un bug: [GitHub Issues](https://github.com/ThibautTrarbach/daikinRCCloud/issues)

---

[Precedente: Limiti e buone pratiche]({{ site.baseurl }}/it_IT/quota-api.html) — [Torna alla home]({{ site.baseurl }}/it_IT/)
