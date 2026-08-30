---
layout: default
title: Limiti e buone pratiche - Daikin ONECTA
---

# Limiti e buone pratiche

Daikin impone un limite al numero di volte in cui Jeedom può interrogare il cloud al giorno. Questa pagina spiega perché esiste questo limite e come ottimizzarlo.

## Perché un limite?

Per funzionare, il plugin deve chiedere regolarmente a Daikin lo stato dei dispositivi (temperatura, modalità, accensione/spegnimento…). Ogni richiesta conta in una **quota giornaliera** fissata da Daikin. Questa quota si azzera ogni giorno a mezzanotte.

Non è una limitazione del plugin, ma una regola imposta dal servizio cloud Daikin ONECTA.

## Quota in base alla modalità di connessione

| Modalità di connessione | Interrogazioni autorizzate al giorno |
|-------------------------|--------------------------------------|
| **Mobile App** (consigliata) | 3000 |
| **Developer Portal** | 200 |

> **Consigliato:** usa la modalità **Mobile App** per beneficiare di una quota molto più ampia.

## Cosa consuma la quota?

| Azione | Consumo |
|--------|---------|
| Sincronizzazione pianificata (ogni 15 min per impostazione predefinita) | 1 interrogazione (per tutti i dispositivi insieme) |
| Comando (cambiare temperatura, accendere…) | 1 interrogazione per modifica |
| Verifica dopo un comando | 1 interrogazione (a seconda delle impostazioni) |
| Aggiornamento contatori kWh (ogni sera) | 1 interrogazione |
| Aggiornamento in tempo reale (WebSocket, modalità Mobile App) | **0** interrogazioni |

**Punto importante:** una sincronizzazione interroga **tutti** i dispositivi in una sola volta. Avere 1 o 5 condizionatori consuma lo stesso numero di interrogazioni.

## Stima visualizzata nella configurazione

Nella pagina di configurazione (sezione avanzata), il campo **Numero di richieste pianificate/giorno** stima quante interrogazioni GET il daemon pianificherà ogni giorno, in base agli intervalli giorno/notte, alla modalità di autenticazione e al WebSocket.

| Configurazione | Dettaglio | Totale pianificato |
|----------------|-----------|-------------------|
| Developer Portal, predefiniti (15 min giorno, 30 min notte, notte 22:00→7:00) | 60 poll giorno + 18 poll notte + 1 stats energia | **~79 GET/giorno** |
| Mobile App + WebSocket attivato, stessi intervalli | Rete di sicurezza 30/60 min: 30 + 9 + 1 | **~40 GET/giorno** |

Queste cifre non includono i comandi, i refresh post-azione (a seconda delle impostazioni) né il GET di avvio del daemon (+1 a ogni riavvio).

## Consigli per ottimizzare

### Per la maggior parte degli utenti

1. **Usa la modalità Mobile App** — quota 15 volte superiore.
2. **Lascia le impostazioni predefinite** — sono progettate per un buon equilibrio.
3. **Mantieni il WebSocket attivato** (modalità Mobile App) — i cambiamenti di stato arrivano in tempo reale senza consumare quota.

### Se sei in modalità Developer Portal (200/giorno)

- Non ridurre gli intervalli di sincronizzazione sotto i 15 minuti.
- Evita scenari che inviano molti comandi ravvicinati.
- Valuta il passaggio alla modalità Mobile App.

### Se hai molti dispositivi e automazioni

- Aumenta leggermente gli intervalli di sincronizzazione (es. 20 min di giorno, 45 min di notte).
- La modalità ibrida (predefinita) per il comportamento dopo comando è la più economica.

## Cosa succede se la quota è raggiunta?

Il plugin rallenta automaticamente le sincronizzazioni quando la quota è quasi esaurita. I comandi continuano a funzionare, ma gli aggiornamenti di stato possono essere meno frequenti fino al giorno successivo.

In modalità Developer Portal, un superamento della quota può bloccare tutte le interrogazioni fino a mezzanotte.

---

[Precedente: Utilizzo]({{ site.baseurl }}/it_IT/utilisation.html) — [Successivo: Risoluzione problemi]({{ site.baseurl }}/it_IT/depannage.html)
