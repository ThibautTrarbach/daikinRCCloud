---
layout: default
title: Changelog Daikin ONECTA (Beta)
---

# Changelog Daikin ONECTA

Toutes les modifications notables de ce plugin seront documentées sur cette page.

## [Unreleased]

## [0.10.4] - 2026-09-04

> Daemon livré par la branche `release-beta` : **2.1.7** (minimum requis : 2.0.0)

### Modifié — Plugin
- Panneau « Informations API / diagnostic » masqué lorsque le support est complet ; nettoyage automatique de la config diagnostic stockée
- Alignement avec le daemon : métadonnées support MQTT uniquement en cas de manque

### Modifié — Daemon
- IP/MAC/SSID gateway et version logicielle unité intérieure : publication dans `_device` (plus de commandes MQTT dédiées)
- Publication MQTT des diagnostics support uniquement en cas de manque

### Ajouté — Daemon
- Inventaire API / mismatches settable dans le rapport de debug
- BRP069C4x : `intelligentEyeMode`, `frontPanelSetting`, `installationPosition`

### Corrigé — Daemon
- BRP069A78 : `operationMode` et consigne ECS settable comme l’API
- `climateControl/name` : lecture seule intentionnelle (identité équipement)

---

## [0.10.3] - 2026-09-01

> Daemon livré par la branche `release-beta` : **2.1.5** (minimum requis : 2.0.0)

### Corrigé — Daemon
- BRP069B4x / BRP069A4x : couverture statique complète (`gatewayDiagnosticsPack`, `auxiliaryUnitPack`) — [#42](https://github.com/ThibautTrarbach/daikinRCCloud/issues/42)
- BRP069A78 : diagnostics gateway, offset eau de départ, ballon ECS, unités hydro/outdoor/UI — [#41](https://github.com/ThibautTrarbach/daikinRCCloud/issues/41)
- Rapport de debug : masquage du numéro de série (`serialNumber`)

---

## [0.10.2] - 2026-09-01

> Daemon livré par la branche `release-beta` : **2.1.3** (minimum requis : 2.0.0)

### Ajouté — Plugin
- Panneau de diagnostic support enrichi : message de support, points de gestion, unités détectées, datapoints non mappés, rapport de debug avec bouton copier
- Lien GitHub pour signaler un appareil non supporté (`githubIssueUrl`)
- Synchronisation des métadonnées support daemon → configuration équipement
- Nettoyage automatique des commandes support obsolètes (logical IDs `_supportStatus`, `_configCoverage`, etc.)

### Ajouté — Daemon
- `gatewayDiagnosticsPack` et `auxiliaryUnitPack` : capteurs réseau/diagnostic gateway et unités indoor/outdoor (BRP069C4x)
- Mapping read-only `isPowerfulModeActive` (API firmware 2.6.x)
- Module métadonnées support : rapport de debug, URL issue GitHub, commandes support synchronisées

### Corrigé — Daemon
- Audit de couverture API : normalisation des chemins datapoint, prise en compte metadata `_device`
- Preset mode Home Assistant : fallback sur `_isPowerfulModeActive` si `_powerfulMode` est absent

### Modifié — Daemon
- Enrichissement `_device` : `ipAddress`, `macAddress`, alias `ssid`
- Rapport de debug : message de support, indication de troncature des datapoints non mappés

---

## [0.10.1] - 2026-09-01

> Daemon livré par la branche `release-beta` : **2.1.2** (minimum requis : 2.0.0)

### Ajouté — Plugin
- Alertes de statut de support : notification en cas de support partiel ou de configuration incomplète d'un appareil Daikin (bandeau UI + informations de diagnostic)
- Nettoyage des équipements fantômes : suppression automatique des IDs logiques créés depuis des topics MQTT internes (pont système)
- Notifications d'authentification : demande de validation OAuth avec message utilisateur et gestion de l'expiration du délai

### Ajouté — Daemon
- Timeout d'autorisation OAuth configurable (`authorizationTimeoutSeconds`, 60–3600 s)
- Nettoyage au démarrage des topics MQTT retenus obsolètes
- WebSocket : prise en charge des caractéristiques imbriquées et des références dans les mises à jour push

### Modifié
- Clarification de la documentation : distinction entre le pont Daikin2MQTT et les appareils Daikin réels
- Arrêt propre du daemon renforcé (skip des opérations pendant l'arrêt)
- Gestion des rate-limit améliorée : fusion des mises à jour partielles, conservation des valeurs précédentes

---

## [0.10.0] - 2026-08-30

> Daemon livré par la branche `release-beta` : **2.1.0** (minimum requis : 2.0.0)

### Changement important
- Le daemon V1 (< 2.0.0) n'est plus supporté — migration obligatoire vers le daemon V2
- **Réinstallation des dépendances requise** après mise à jour du plugin
- Branche par défaut des dépendances : `release-beta`
- Migration automatique des branches V1 (`release-stable`, `dev`, `release-dev`, etc.) vers `release-beta`
- Prérequis : Jeedom 4.4+, plugin **mqtt2** installé et démarré

### Ajouté — Plugin
- Refonte complète de la page de configuration (sections repliables, mode « Configuration avancée » mémorisé)
- Affichage des versions plugin et daemon (utile pour le support communauté)
- Mode **Mobile App** : connexion avec email/mot de passe Onecta, quota ~3000 req/j
- Mode **Developer Portal** : OAuth Client ID/Secret, quota ~200 req/j
- Estimation automatique du quota API et des requêtes/jour selon le mode d'authentification
- Nouveaux réglages avancés : stratégie refresh post-action, fusion avec polling, coalescence commandes, refresh stats énergie, WebSocket, DynamicGateway, capteurs lecture seule, publication si delta, transport HTTP curl, préfixe MQTT
- Mot de passe Onecta stocké chiffré
- Vérifications mqtt2 avec messages d'erreur explicites si absent ou non démarré
- Première version de la documentation en ligne : installation, configuration, authentification, utilisation, quotas API, dépannage. Contenu généré rapidement par une IA avant publication — **pas encore relu ni validé**

### Ajouté — Daemon (via le plugin)
- Connexion simplifiée avec le même compte que l'application Onecta (mode Mobile App)
- Mises à jour temps réel via WebSocket (réactivité sans consommer le quota API)
- Support automatique des modèles Daikin non listés (DynamicGateway)
- Compteurs d'énergie kWh rafraîchis quotidiennement à heure configurable
- Réponse immédiate dans Jeedom après une commande (publication optimiste MQTT)
- Économie de quota : fusion refresh/polling, polling adaptatif selon budget API, skip si WebSocket confirme le changement
- Contournement des blocages réseau/WAF Daikin (transport HTTP curl)
- Statut du budget API visible sur le pont système MQTT

### Modifié
- Interface d'authentification : bascule dynamique Mobile App / Developer Portal avec quotas affichés
- Défauts polling : 15 min (jour) / 30 min (nuit) ; délai refresh post-action : 60 s (au lieu de 120 s)
- Installation daemon plus fiable : exécution via `main.js` compilé, vérifications à l'installation
- Messages d'erreur explicites (mqtt2 absent, daemon trop ancien, `main.js` introuvable)
- ~90 nouvelles traductions UI (FR, EN, ES, DE, IT)
- Branchement versionné conservé avec stubs V3 commentés pour les futures évolutions

### Supprimé
- Support du daemon V1 (< 2.0.0) et authentification OAuth via MQTT
- Code V1 du plugin (configuration, messages MQTT legacy)
- Branches de dépendances obsolètes : `release-stable`, `dev`, `release-dev`

---

## [0.9.3] - 2025-12-10

### Amélioration
- Amélioration de la mise à jour du plugin
- Améliorations des logs du plugin avec de la traduction 
- Correction de valeur pouvant être vide dans la page de configuration
- Meilleure gestion des erreurs dans la page de configuration et dans les tâches exécutées lors de l'installation du plugin ou de sa mise à jour


---
## [0.9.2] - 2025-12-09

### Correction 
- Corrections d'un bug de mise à jour de la configuration

### Breaking change
- Le plugin nécessite maintenant un Jeedom 4.4 au minimum 

---

## [0.9.1] - 2025-12-05

### Infos 
- Le Deamon 2.0.x n'est pas encore disponible correctement. Dans les prochains jours, on vous proposera de le tester en mode alpha pour éviter d'impacter vos productions pendant la période de chauffe

### Ajouté
- Configuration des dépendances : possibilité de choisir la branche ou le commit à installer
- Support des paramètres de polling (jour/nuit) pour le daemon 2.0.0+
- Support des modes de rafraîchissement après action pour le daemon 2.0.0+
- Compatibilité avec les versions V1 et V2 du daemon
- Internationalisation complète (FR, EN, ES, DE, IT)
- Lien vers la future documentation et changelog

### Modifié
- Amélioration de la gestion des messages MQTT
- Optimisation de la création des équipements

### Corrigé
- Corrections diverses de bugs

---

## [0.9.0] - En l'an 2022 (Plus de souvenir de la date)

### Ajouté
- Version initiale du plugin

