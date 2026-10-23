---
layout: default
title: Authentification - Daikin ONECTA
---

# Authentification

Pour piloter vos appareils Daikin depuis Jeedom, choisissez un mode de connexion : cloud Onecta, ou **local uniquement** (LAN, sans compte Daikin).

## Quel mode choisir ?

**Developer Portal** est le mode de fonctionnement **officiel** proposé par Daikin (API publique du portail développeur).

**Mobile App** n’est **pas pris en charge officiellement** par Daikin. Il s’appuie sur une API non publique dédiée à l’application mobile officielle : ce mode peut **cesser de fonctionner à tout moment** sans préavis.

| | Developer Portal (recommandé) | Mobile App | Local uniquement |
|---|-------------------------------|------------|------------------|
| **Statut** | Officiel Daikin | Non officiel — peut casser à tout moment | LAN HTTP (Online Controller) |
| **Identifiants** | Client ID + Client Secret | Email + mot de passe Onecta | Aucun (IPs LAN) |
| **Même compte que l'app mobile ?** | Non (compte développeur séparé) | Oui | Non |
| **Quota journalier** | 200 interrogations | 3000 interrogations | Aucun |
| **Mises à jour en temps réel** | Non | Oui | Polling LAN |
| **Configuration** | Procédure OAuth | Simple (2 champs) | Liste d’IP |

### Mode Local uniquement (LAN)

1. Sélectionnez **Local uniquement (LAN)**.
2. Renseignez les **IPv4** des modules Online Controller (`BRP069A42` / `A43` / `B41`).
3. Sauvegardez et redémarrez le daemon.

Aucun compte Onecta n’est requis : le client cloud n’est pas démarré.

---

## Mode Developer Portal

Ce mode utilise une application créée sur le [portail développeur Daikin](https://developer.cloud.daikineurope.com/). C’est le fonctionnement **officiel** de Daikin. Quota : 200 interrogations/jour. La procédure OAuth est nécessaire pour la première connexion.

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

Cliquez sur **New App**, donnez-lui un nom (ex. « Jeedom »), choisissez **Onecta OIDC** comme *Auth Strategy*, puis validez.

![Création d'une application sur le portail développeur Daikin](../assets/images/authentification/01-daikin-new-app.png)

#### Étape 4 : Copier les identifiants

Copiez le **Client ID** et le **Client Secret** dans la configuration du plugin, puis sauvegardez.

#### Étape 5 : Démarrer le plugin et récupérer l'URL

1. Démarrez le service du plugin.
2. Ouvrez les **logs** du plugin (**Analyse → Logs**, filtre `daikinRCCloud`).
3. Copiez l'**URL d'authentification** affichée dans les logs (ex. `https://IP_JEEDOM:8765`).

![URL d'authentification dans les logs](../assets/images/authentification/02-auth-url-logs.png)

#### Étape 6 : Configurer l'URL de redirection

1. Retournez sur le portail développeur Daikin.
2. Éditez votre application.
3. Collez l'URL copiée dans le champ **Redirect URI**.
4. Cliquez sur **Update**.

![Configuration du Redirect URI sur le portail Daikin](../assets/images/authentification/03-redirect-uri.png)

#### Étape 7 : Autoriser l'accès

1. Ouvrez l'URL d'authentification dans un navigateur.
2. Acceptez le certificat si votre navigateur l'affiche.
3. Suivez la procédure d'autorisation Daikin.

#### Étape 8 : Vérifier

Un message de succès confirme que la connexion est établie. Vos appareils devraient apparaître dans Jeedom.

![Autorisation réussie](../assets/images/authentification/04-auth-success.png)

### En cas de problème

- Si Daikin invalide votre clé API, recommencez la procédure de connexion depuis l'étape 5.
- Vérifiez que le port d'authentification (défaut : 8765) n'est pas bloqué par un pare-feu.
- Consultez le [tutoriel sur la communauté Jeedom](https://community.jeedom.com/t/onecta-cloud-api-key-invalidation-action-required/127311/33?u=thibaut_t) en cas d'invalidation de clé.

---

## Mode Mobile App

> **Attention :** ce mode n’est **pas pris en charge officiellement** par Daikin. Il repose sur une API non publique dédiée à l’application Onecta et peut **cesser de fonctionner à tout moment**. Préférez le [mode Developer Portal](#mode-developer-portal) pour un usage pérenne.

Ce mode utilise le même compte que l'application **Daikin Onecta** ou **Daikin Residential Controller** sur votre téléphone.

### Configuration

1. Ouvrez **Plugins → Daikin ONECTA → Configuration**.
2. Sélectionnez **Mobile App**.
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

[Précédent : Configuration]({{ site.baseurl }}/fr_FR/configuration.html) — [Suivant : Utilisation]({{ site.baseurl }}/fr_FR/utilisation.html)
