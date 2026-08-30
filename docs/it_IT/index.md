---
layout: default
title: Documentazione Daikin ONECTA
---

# Documentazione Daikin ONECTA

Il plugin **Daikin ONECTA** consente di controllare e monitorare i dispositivi Daikin compatibili con ONECTA direttamente da Jeedom: condizionatori, pompe di calore Altherma e altri apparecchi connessi tramite il cloud Daikin.

## A cosa serve questo plugin?

Con questo plugin, puoi integrare i tuoi dispositivi Daikin nella tua installazione Jeedom come qualsiasi altro dispositivo domotico:

- Visualizzare il loro stato sulla tua **dashboard**
- Controllarli tramite **scenari** o **comandi vocali**
- Automatizzare riscaldamento, climatizzazione o ventilazione in base alle tue abitudini

I tuoi dispositivi vengono **scoperti automaticamente** non appena il plugin è configurato correttamente. Non c'è un pulsante « Aggiungi dispositivo »: se il tuo condizionatore appare nell'applicazione Daikin Onecta sul telefono, può apparire in Jeedom.

## Cosa puoi fare

| Funzione | Descrizione |
|----------|-------------|
| **Accensione / spegnimento** | Accendere o spegnere il dispositivo |
| **Modalità** | Freddo, caldo, automatico, deumidificazione, sola ventilazione |
| **Setpoint** | Impostare la temperatura desiderata |
| **Ventilazione** | Scegliere velocità e orientamento del flusso d'aria (a seconda del modello) |
| **Modalità speciali** | Eco, Powerful, Streamer (a seconda del modello) |
| **Temperature** | Consultare la temperatura ambiente ed esterna |
| **Umidità** | Consultare l'umidità ambiente (a seconda del modello) |
| **Consumo** | Monitorare il consumo energetico in kWh (giorno, settimana, mese) |
| **Programmazioni** | Attivare o disattivare le programmazioni configurate in Onecta |
| **Modalità vacanza** | Attivare la modalità assenza (a seconda del modello) |
| **Aggiornamento** | Avviare un aggiornamento firmware se proposto da Daikin |

> **Nota:** i comandi esatti dipendono dal modello del dispositivo. La maggior parte dei modelli Onecta recenti è supportata automaticamente, anche se non compaiono nell'elenco seguente.

## Dispositivi compatibili

Il plugin supporta in particolare le seguenti gamme:

| Gamma / tipo | Esempi |
|--------------|--------|
| Climatizzazione monozona | Daikin Perfera (FTXM), Stylish, Emura… |
| Climatizzazione estesa | Modelli con modalità eco, streamer, orientamento del flusso |
| Pompa di calore dual-zone | Daikin Altherma (riscaldamento + acqua calda) |
| Climatizzazione multizona | Installazioni con più zone |

Se il tuo modello non è elencato esplicitamente, il plugin tenta di supportarlo automaticamente grazie al **supporto automatico dei modelli recenti** (opzione attivata per impostazione predefinita nella configurazione avanzata).

## Cosa ti serve

| Prerequisito | Dettaglio |
|--------------|-----------|
| **Jeedom** | Versione 4.4 o superiore |
| **Plugin mqtt2** | Obbligatorio — installato e attivo su Jeedom |
| **Account Daikin** | Account dell'applicazione **Daikin Onecta** sul telefono (consigliato) |
| **Accesso Internet** | Necessario per comunicare con il cloud Daikin |

## Sommario

| Pagina | Descrizione |
|--------|-------------|
| [Installazione]({{ site.baseurl }}/it_IT/installation.html) | Installare il plugin e far apparire i dispositivi |
| [Configurazione]({{ site.baseurl }}/it_IT/configuration.html) | Impostazioni del plugin |
| [Autenticazione]({{ site.baseurl }}/it_IT/authentification.html) | Connettersi al proprio account Daikin |
| [Utilizzo]({{ site.baseurl }}/it_IT/utilisation.html) | Controllare i dispositivi quotidianamente |
| [Limiti e buone pratiche]({{ site.baseurl }}/it_IT/quota-api.html) | Comprendere i limiti del cloud Daikin |
| [Risoluzione problemi]({{ site.baseurl }}/it_IT/depannage.html) | Risolvere i problemi più comuni |

## Link utili

- [Changelog]({{ site.baseurl }}/it_IT/changelog.html)
- [Forum Jeedom](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55)
- [Repository GitHub](https://github.com/ThibautTrarbach/daikinRCCloud)

---

**Segue:** [Installazione]({{ site.baseurl }}/it_IT/installation.html)
