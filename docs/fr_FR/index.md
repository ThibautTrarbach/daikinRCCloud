---
layout: default
title: Documentation Daikin ONECTA
---

# Documentation Daikin ONECTA

## Description

Le plugin **Daikin ONECTA** pilote vos équipements Daikin compatibles ONECTA (climatisation, pompes à chaleur Altherma, etc.) via l'API cloud officielle. Il embarque le daemon **daikintomqtt** et communique avec Jeedom via **mqtt2**.

## Fonctionnalités

- Synchronisation automatique des équipements Daikin
- Contrôle complet : mode, consigne, ventilation, eco/powerful/streamer, consommation kWh
- **DynamicGateway** : prise en charge automatique des modèles non listés
- Planification : activation/désactivation des schedules Onecta
- Mode vacances (preset away) via MQTT
- Mise à jour firmware OTA (si proposée par le cloud)
- Gestion du quota API : 200 req/jour (Developer Portal) ou 3000 req/jour (Mobile App)
- **Mode Mobile App** (recommandé) : authentification Onecta + WebSocket temps réel
- Refresh optimiste, fusion avec polling, coalescence
- Polling adaptatif jour/nuit

## Modèles gateway supportés (statiques)

- BRP069A4x, BRP069A61, BRP069A62, BRP069A78
- BRP069B4x, BRP069C4x, BRP069C41, BRP069C8x

Les autres modèles sont gérés via **DynamicGateway** si l'option est activée (défaut : oui).

## Quota API Daikin

| Mode | Quota journalier |
|------|------------------|
| Developer Portal (OAuth) | 200 requêtes/jour |
| Mobile App (recommandé) | 3000 requêtes/jour + WebSocket |

| Opération | Coût |
|-----------|------|
| Polling / refresh | 1 GET (tous les équipements) |
| Refresh partiel post-action | 1 GET (un équipement) |
| Commande | 1 PATCH par propriété |
| Stats énergie (23:58) | 1 GET prioritaire |

Le daemon réserve 1 requête/jour pour le refresh énergie et réduit le polling quand le quota est bas.

## Configuration Jeedom

- **Mode d'authentification** : Developer Portal (OAuth) ou Mobile App (email/mot de passe Onecta)
- **WebSocket** : mises à jour temps réel (mode Mobile App uniquement)
- **Polling** : intervalles jour/nuit, heures de la période nuit
- **Refresh post-action** : mode 1/2/3, délai, stratégie `merge_with_poll`
- **DynamicGateway** : fallback automatique pour modèles inconnus
- **OAuth Developer Portal** : Client ID / Secret depuis [developer.cloud.daikineurope.com](https://developer.cloud.daikineurope.com/)

## Topics MQTT (préfixe par défaut `daikinToMQTT`)

| Topic | Rôle |
|-------|------|
| `{prefix}/{deviceId}` | État JSON de l'équipement |
| `{prefix}/{deviceId}/set` | Commandes |
| `{prefix}/jeedom/{deviceId}` | Définition des commandes Jeedom |

## Prérequis

- Jeedom 4.4+
- Plugin **mqtt2**
- Node.js 20+ (installé par le plugin)
