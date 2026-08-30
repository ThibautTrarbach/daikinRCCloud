---
layout: default
title: Documentation Daikin ONECTA
---

# Documentation Daikin ONECTA

Le plugin **Daikin ONECTA** permet de piloter et de surveiller vos équipements Daikin compatibles ONECTA directement depuis Jeedom : climatiseurs, pompes à chaleur Altherma, et autres appareils connectés via le cloud Daikin.

## À quoi sert ce plugin ?

Avec ce plugin, vous pouvez intégrer vos appareils Daikin dans votre installation Jeedom comme n'importe quel autre équipement domotique :

- Afficher leur état sur votre **dashboard**
- Les piloter via des **scénarios** ou des **commandes vocales**
- Automatiser le chauffage, la climatisation ou la ventilation selon vos habitudes

Vos appareils sont **découverts automatiquement** dès que le plugin est correctement configuré. Il n'y a pas de bouton « Ajouter un équipement » : si votre climatiseur apparaît dans l'application Daikin Onecta sur votre téléphone, il peut apparaître dans Jeedom.

## Ce que vous pouvez faire

| Fonction | Description |
|----------|-------------|
| **Marche / arrêt** | Allumer ou éteindre votre appareil |
| **Modes** | Froid, chaud, automatique, séchage, ventilation seule |
| **Consigne** | Régler la température souhaitée |
| **Ventilation** | Choisir la vitesse et l'orientation du flux d'air (selon modèle) |
| **Modes spéciaux** | Eco, Powerful, Streamer (selon modèle) |
| **Températures** | Consulter la température ambiante et extérieure |
| **Humidité** | Consulter l'humidité ambiante (selon modèle) |
| **Consommation** | Suivre la consommation énergétique en kWh (jour, semaine, mois) |
| **Plannings** | Activer ou désactiver les plannings configurés dans Onecta |
| **Mode vacances** | Activer le mode absence (selon modèle) |
| **Mise à jour** | Lancer une mise à jour firmware si Daikin la propose |

> **Note :** les commandes exactes dépendent de votre modèle d'appareil. La plupart des modèles Onecta récents sont pris en charge automatiquement, même s'ils ne figurent pas dans la liste ci-dessous.

## Équipements compatibles

Le plugin supporte notamment les gammes suivantes :

| Gamme / type | Exemples |
|--------------|----------|
| Climatisation mono-zone | Daikin Perfera (FTXM), Stylish, Emura… |
| Climatisation étendue | Modèles avec modes eco, streamer, orientation du flux |
| Pompe à chaleur dual-zone | Daikin Altherma (chauffage + eau chaude) |
| Climatisation multi-zone | Installations avec plusieurs zones |

Si votre modèle n'est pas listé explicitement, le plugin tente de le prendre en charge automatiquement grâce à la **prise en charge automatique des modèles récents** (option activée par défaut dans la configuration avancée).

## Ce qu'il vous faut

| Prérequis | Détail |
|-----------|--------|
| **Jeedom** | Version 4.4 ou supérieure |
| **Plugin mqtt2** | Obligatoire — installé et actif sur votre Jeedom |
| **Compte Daikin Developer** | Application créée sur [developer.cloud.daikineurope.com](https://developer.cloud.daikineurope.com/) (mode Developer Portal recommandé) |
| **Accès Internet** | Requis pour communiquer avec le cloud Daikin |

## Sommaire

| Page | Description |
|------|-------------|
| [Installation]({{ site.baseurl }}/fr_FR/installation.html) | Installer le plugin et faire apparaître vos appareils |
| [Configuration]({{ site.baseurl }}/fr_FR/configuration.html) | Réglages du plugin |
| [Authentification]({{ site.baseurl }}/fr_FR/authentification.html) | Se connecter à votre compte Daikin |
| [Utilisation]({{ site.baseurl }}/fr_FR/utilisation.html) | Piloter vos appareils au quotidien |
| [Limites et bonnes pratiques]({{ site.baseurl }}/fr_FR/quota-api.html) | Comprendre les limites du cloud Daikin |
| [Dépannage]({{ site.baseurl }}/fr_FR/depannage.html) | Résoudre les problèmes courants |

## Liens utiles

- [Changelog]({{ site.baseurl }}/fr_FR/changelog.html)
- [Forum Jeedom](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55)
- [Dépôt GitHub](https://github.com/ThibautTrarbach/daikinRCCloud)

---

**Suite :** [Installation]({{ site.baseurl }}/fr_FR/installation.html)
