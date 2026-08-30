---
layout: default
title: Configuration - Daikin ONECTA
---

# Configuration

La page de configuration se trouve dans **Plugins → Daikin ONECTA → Configuration**.

La plupart des utilisateurs n'ont besoin de modifier que la section **Connexion Daikin**. Les autres réglages sont accessibles via la case **Configuration avancée**.

---

## Connexion Daikin

C'est la section la plus importante. Elle permet de lier Jeedom à votre compte Daikin.

### Mode d'authentification

Deux modes sont disponibles :

| Mode | Pour qui ? | Quota journalier |
|------|-----------|------------------|
| **Developer Portal** (recommandé) | Tous les utilisateurs | 200 interrogations/jour |
| **Mobile App** | Utilisateurs avancés avec identifiants Onecta | 3000 interrogations/jour |

> **Recommandé :** choisissez **Developer Portal** et créez une application sur le [portail développeur Daikin](https://developer.cloud.daikineurope.com/).

Consultez la page [Authentification]({{ site.baseurl }}/fr_FR/authentification.html) pour le détail de chaque mode.

### Identifiants

Selon le mode choisi :

- **Mobile App :** saisissez votre **email Onecta** et votre **mot de passe Onecta**.
- **Developer Portal :** saisissez le **Client ID** et le **Client Secret** de votre application Daikin Developer.

### Quota API journalier

Ce champ affiche le nombre maximum d'interrogations cloud autorisées par jour selon votre mode de connexion. Il est calculé automatiquement et n'est pas modifiable.

Pour comprendre ce que cela implique concrètement, consultez [Limites et bonnes pratiques]({{ site.baseurl }}/fr_FR/quota-api.html).

### Versions

Les versions du plugin et du service interne sont affichées en lecture seule. Indiquez-les si vous demandez de l'aide sur le forum.

---

## Configuration avancée

Cochez **Configuration avancée** pour afficher les réglages supplémentaires. **La plupart des utilisateurs peuvent laisser les valeurs par défaut.**

### Fréquence de mise à jour

Ces réglages déterminent à quelle fréquence Jeedom interroge le cloud Daikin pour connaître l'état de vos appareils.

| Réglage | Défaut | Description |
|---------|--------|-------------|
| **Intervalle en journée** | 15 min | Fréquence de vérification entre le matin et le soir |
| **Intervalle la nuit** | 30 min | Fréquence de vérification pendant la nuit (économise le quota) |
| **Début de la nuit** | 22h | Heure à laquelle commence la période nuit |
| **Fin de la nuit** | 7h | Heure à laquelle se termine la période nuit |

> **Conseil :** avec le mode Mobile App et les mises à jour en temps réel activées, vous pouvez augmenter ces intervalles sans perdre en réactivité.

Le champ **Nombre de requêtes planifiées/jour** estime combien d'interrogations GET le daemon planifiera chaque jour (polling + stats énergie), en tenant compte du mode d'authentification et du WebSocket. La mise à jour est automatique lorsque vous modifiez les réglages. Les commandes et refresh post-action s'ajoutent à cette estimation.

### Comportement après une commande

Quand vous actionnez une commande (changer la température, allumer la clim…), le plugin peut réagir de trois façons :

| Mode | Comportement | Quand l'utiliser |
|------|--------------|------------------|
| **1 — Rafraîchissement complet différé** | Attend puis vérifie l'état réel auprès de Daikin | Si vous voulez une confirmation systématique du cloud |
| **2 — Mise à jour immédiate** | Met à jour Jeedom tout de suite, sans vérifier Daikin | Pour économiser le quota, si la réactivité suffit |
| **3 — Hybride** (défaut) | Mise à jour immédiate + vérification Daikin après un délai | **Recommandé** — bon équilibre réactivité / fiabilité |

**Délai de rafraîchissement :** en mode 1 et 3, temps d'attente avant la vérification auprès de Daikin (défaut : 60 secondes). Laissez ce délai si vos appareils mettent un peu de temps à réagir.

**Stratégie de vérification :**

| Stratégie | Description |
|-----------|-------------|
| **Fusion avec la synchronisation** (défaut) | Si une synchronisation planifiée arrive bientôt, le plugin attend plutôt que de faire une requête supplémentaire |
| **Vérification dédiée** | Le plugin interroge Daikin spécifiquement après chaque commande |
| **Pas de vérification** | Aucune interrogation cloud après une commande |

**Refresh des statistiques énergie :** heure quotidienne (défaut 23h58) à laquelle le plugin met à jour les compteurs de consommation kWh.

### Prise en charge automatique des modèles

| Option | Défaut | Description |
|--------|--------|-------------|
| **Modèles inconnus** | Activé | Permet de piloter automatiquement les modèles Daikin non listés explicitement |
| **Capteurs en lecture seule** | Activé | Affiche les températures extérieures, diagnostics, etc. |
| **Publication si changement** | Activé | Ne met à jour Jeedom que lorsque une valeur a réellement changé |

Désactivez **Modèles inconnus** uniquement si vous rencontrez un comportement anormal avec un appareil non reconnu.

### Options supplémentaires

| Option | Défaut | Description |
|--------|--------|-------------|
| **WebSocket temps réel** | Activé | Reçoit les changements d'état en direct (mode Mobile App uniquement) |
| **Port d'authentification** | 8765 | Port local pour la connexion Developer Portal uniquement |
| **Préfixe MQTT** | daikinToMQTT | Laisser par défaut sauf conflit avec un autre plugin |

---

## Réglages experts

> **Ne modifiez ces réglages que si le support vous le demande ou si vous savez pourquoi.**

### Transport HTTP

Si le plugin n'arrive pas à communiquer avec Daikin (erreurs réseau répétées, blocage par un pare-feu), passez de **Node.js** à **curl**. Cela utilise un autre moteur réseau qui contourne parfois les blocages.

### Configuration des dépendances

Permet de choisir quelle version du service interne du plugin est installée (branche ou version précise). **Laissez les valeurs par défaut** (`release-beta`) sauf instruction contraire du support.

Après toute modification, relancez l'installation des dépendances.

---

[Précédent : Installation]({{ site.baseurl }}/fr_FR/installation.html) — [Suivant : Authentification]({{ site.baseurl }}/fr_FR/authentification.html)
