---
layout: default
title: Limiti e buone pratiche - Daikin ONECTA
---

# Limiti e buone pratiche

Daikin limita il numero di volte in cui Jeedom può interrogare il cloud al giorno. Questa pagina spiega perché esiste questo limite e come ottimizzarlo.

## Perché un limite?

Per funzionare, il plugin deve chiedere regolarmente a Daikin lo stato dei dispositivi (temperatura, modalità, on/off, ecc.). Ogni richiesta conta verso una **quota giornaliera** impostata da Daikin. Questa quota si azzera ogni giorno a mezzanotte.

Non è una limitazione del plugin, ma una regola imposta dal servizio cloud Daikin ONECTA.

## Quota per modalità di connessione

| Modalità di connessione | Richieste consentite al giorno |
|-------------------------|--------------------------------|
| **Developer Portal** | 200 |
| **Mobile App** | 3000 |

## Cosa consuma la quota?

| Azione | Consumo |
|--------|---------|
| Sincronizzazione pianificata (ogni 15 min per impostazione predefinita) | 1 richiesta (per tutti i dispositivi contemporaneamente) |
| Comando (cambio temperatura, accensione, ecc.) | 1 richiesta per modifica |
| Verifica dopo un comando | 1 richiesta (a seconda delle impostazioni) |
| Aggiornamento contatori kWh (ogni sera) | 1 richiesta |
| Aggiornamento in tempo reale (WebSocket, modalità Mobile App) | **0** richieste |

**Importante:** una sincronizzazione interroga **tutti** i dispositivi contemporaneamente. Avere 1 o 5 condizionatori consuma lo stesso numero di richieste.

## Stima mostrata nella configurazione

Nella pagina di configurazione (sezione avanzata), il campo **Richieste pianificate/giorno** stima quante richieste GET il daemon programmerà ogni giorno, in base agli intervalli giorno/notte, alla modalità di autenticazione e al WebSocket.

| Configurazione | Dettaglio | Totale pianificato |
|----------------|-----------|-------------------|
| Developer Portal, predefiniti (15 min giorno, 30 min notte, notte 22:00→07:00) | 60 polling diurni + 18 notturni + 1 statistiche energetiche | **~79 GET/giorno** |
| Mobile App + WebSocket attivato, stessi intervalli | Rete di sicurezza 30/60 min: 30 + 9 + 1 | **~40 GET/giorno** |

Queste cifre non includono i comandi, l'aggiornamento post-azione (a seconda delle impostazioni) né la GET di avvio del daemon (+1 a ogni riavvio).

## Suggerimenti per l'ottimizzazione

### Modalità Developer Portal (200/giorno)

1. **Mantieni le impostazioni predefinite** — sono progettate per un buon equilibrio con la quota di 200 richieste/giorno.
2. **Non ridurre gli intervalli di sincronizzazione** al di sotto di 15 minuti.
3. **Evita scenari** che inviano molti comandi in rapida successione.

### Se usi la modalità Mobile App (3000/giorno)

- **Mantieni il WebSocket attivato** — i cambiamenti di stato arrivano in tempo reale senza consumare quota.
- Puoi aumentare leggermente gli intervalli di sincronizzazione restando reattivo grazie al WebSocket.

### Se hai molti dispositivi e automazioni

- Aumenta leggermente gli intervalli di sincronizzazione (ad es. 20 min giorno, 45 min notte).
- La modalità ibrida (predefinita) per il comportamento post-comando è la più economica.

## Cosa succede quando la quota è esaurita?

Il plugin rallenta automaticamente la sincronizzazione quando la quota è quasi esaurita. I comandi continuano a funzionare, ma gli aggiornamenti di stato possono essere meno frequenti fino al giorno successivo.

In modalità Developer Portal, superare la quota può bloccare tutte le richieste fino a mezzanotte.

---

[Precedente: Utilizzo]({{ site.baseurl }}/it_IT/utilisation.html) — [Successivo: Risoluzione problemi]({{ site.baseurl }}/it_IT/depannage.html)
