---
layout: default
title: Authentification - Daikin ONECTA
---

# Authentification

Pour que Jeedom puisse piloter vos appareils Daikin, le plugin doit se connecter à votre compte cloud Daikin ONECTA. Deux modes de connexion sont disponibles.

## Quel mode choisir ?

| | Mobile App | Developer Portal |
|---|-----------|------------------|
| **Recommandé pour** | Tous les utilisateurs | Développeurs, tests avancés |
| **Identifiants** | Email + mot de passe Onecta | Client ID + Client Secret |
| **Même compte que l'app mobile ?** | Oui | Non (compte développeur séparé) |
| **Quota journalier** | 3000 interrogations | 200 interrogations |
| **Mises à jour en temps réel** | Oui | Non |
| **Configuration** | Simple (2 champs) | Complexe (procédure OAuth) |

> **Recommandé :** utilisez le mode **Mobile App** avec les identifiants de votre application Daikin Onecta.

---

## Mode Mobile App (recommandé)

C'est le mode le plus simple. Il utilise le même compte que l'application **Daikin Onecta** ou **Daikin Residential Controller** sur votre téléphone.

### Configuration

1. Ouvrez **Plugins → Daikin ONECTA → Configuration**.
2. Sélectionnez **Mobile App (recommandé)**.
3. Saisissez votre **email Onecta**.
4. Saisissez votre **mot de passe Onecta**.
5. Cliquez sur **Sauvegarder**.
6. Démarrez ou redémarrez le service du plugin.

### Fonctionnement

- La connexion se fait automatiquement au démarrage du plugin.
- Votre mot de passe est stocké de manière sécurisée dans Jeedom.
- Les mises à jour en temps réel sont activées par défaut, ce qui rend le plugin très réactif sans consommer beaucoup de quota.

### En cas de problème

- Vérifiez que vous pouvez vous connecter à l'application Daikin Onecta sur votre téléphone avec les mêmes identifiants.
- Si vous avez changé votre mot de passe Onecta, mettez-le à jour dans la configuration du plugin.
- Redémarrez le service du plugin après toute modification.

---

## Mode Developer Portal

Ce mode s'adresse aux utilisateurs ayant créé une application sur le [portail développeur Daikin](https://developer.cloud.daikineurope.com/). Il offre un quota plus limité (200 interrogations/jour) et nécessite une procédure de connexion plus longue.

### Configuration initiale

1. Ouvrez **Plugins → Daikin ONECTA → Configuration**.
2. Sélectionnez **Developer Portal (OAuth)**.
3. Saisissez le **Client ID** et le **Client Secret** de votre application.
4. Cliquez sur **Sauvegarder**.

### Tutoriel de première connexion

#### Étape 1 : Créer un compte développeur

Rendez-vous sur [developer.cloud.daikineurope.com](https://developer.cloud.daikineurope.com/) et connectez-vous.

#### Étape 2 : Accéder à vos applications

Cliquez sur votre adresse email en haut à droite, puis choisissez **My Apps**.

#### Étape 3 : Créer une application

Cliquez sur **New App**, donnez-lui un nom (ex. « Jeedom ») et validez.

#### Étape 4 : Copier les identifiants

Copiez le **Client ID** et le **Client Secret** dans la configuration du plugin, puis sauvegardez.

#### Étape 5 : Démarrer le plugin et récupérer l'URL

1. Démarrez le service du plugin.
2. Ouvrez les **logs** du plugin (**Analyse → Logs**, filtre `daikinRCCloud`).
3. Copiez l'**URL d'authentification** affichée dans les logs.

#### Étape 6 : Configurer l'URL de redirection

1. Retournez sur le portail développeur Daikin.
2. Éditez votre application.
3. Collez l'URL copiée dans le champ **Redirect URI**.
4. Cliquez sur **Update**.

#### Étape 7 : Autoriser l'accès

1. Ouvrez l'URL d'authentification dans un navigateur.
2. Acceptez le certificat si votre navigateur l'affiche.
3. Suivez la procédure d'autorisation Daikin.

#### Étape 8 : Vérifier

Un message de succès confirme que la connexion est établie. Vos appareils devraient apparaître dans Jeedom.

### En cas de problème

- Si Daikin invalide votre clé API, recommencez la procédure de connexion depuis l'étape 5.
- Vérifiez que le port d'authentification (défaut : 8765) n'est pas bloqué par un pare-feu.
- Consultez le [tutoriel sur la communauté Jeedom](https://community.jeedom.com/t/onecta-cloud-api-key-invalidation-action-required/127311/33?u=thibaut_t) en cas d'invalidation de clé.

---

[Précédent : Configuration]({{ site.baseurl }}/fr_FR/configuration.html) — [Suivant : Utilisation]({{ site.baseurl }}/fr_FR/utilisation.html)
