---
layout: default
title: Installation - Daikin ONECTA
---

# Installation

Ce guide vous accompagne pas à pas pour installer le plugin et faire apparaître vos appareils Daikin dans Jeedom.

## Avant de commencer

Assurez-vous d'avoir :

- Jeedom **4.4** ou plus récent
- Un **compte Daikin Onecta** (le même que sur l'application mobile)
- Vos appareils Daikin déjà configurés et visibles dans l'application Onecta

## Étape 1 : Installer le plugin

1. Ouvrez **Plugins → Gestion des plugins** dans Jeedom.
2. Recherchez **Daikin ONECTA**.
3. Cliquez sur **Installer**, puis **Activer**.

## Étape 2 : Installer le plugin mqtt2

Le plugin **mqtt2** est **obligatoire**. Il sert de relais interne entre le plugin Daikin et vos équipements. En général, vous n'avez rien de particulier à configurer dedans.

1. Installez le plugin **mqtt2** depuis le market Jeedom (s'il ne l'est pas déjà).
2. Activez-le.
3. Vérifiez dans **Outils → Santé** que mqtt2 est bien démarré.

## Étape 3 : Installer les dépendances

Le plugin a besoin de composants supplémentaires pour fonctionner. Jeedom les installe automatiquement :

1. Allez sur la page du plugin **Daikin ONECTA**.
2. Cliquez sur **Réinstaller les dépendances** (ou via le bouton dédié dans la gestion des plugins).
3. Attendez la fin de l'installation (cela peut prendre quelques minutes).

> **Attention :** après chaque mise à jour du plugin, relancez l'installation des dépendances si Jeedom vous le demande.

## Étape 4 : Configurer la connexion Daikin

1. Ouvrez **Plugins → Daikin ONECTA → Configuration**.
2. Choisissez le mode **Mobile App (recommandé)**.
3. Saisissez l'**email** et le **mot de passe** de votre compte Daikin Onecta (les mêmes que sur l'application mobile).
4. Cliquez sur **Sauvegarder**.

Pour plus de détails sur les modes de connexion, consultez la page [Authentification]({{ site.baseurl }}/fr_FR/authentification.html).

## Étape 5 : Démarrer le service du plugin

1. Allez dans **Outils → Santé** (ou sur la page du plugin).
2. Démarrez le **daemon** du plugin Daikin ONECTA.
3. Vérifiez que le statut indique **Démarré**.

## Étape 6 : Vérifier vos appareils

1. Retournez sur la page **Plugins → Daikin ONECTA**.
2. Vos appareils Daikin devraient apparaître sous **Mes équipements**.

Si aucun appareil n'apparaît, consultez la page [Dépannage]({{ site.baseurl }}/fr_FR/depannage.html).

## Et ensuite ?

- [Configurer les réglages du plugin]({{ site.baseurl }}/fr_FR/configuration.html)
- [Apprendre à utiliser vos équipements]({{ site.baseurl }}/fr_FR/utilisation.html)

---

[Précédent : Accueil]({{ site.baseurl }}/fr_FR/) — [Suivant : Configuration]({{ site.baseurl }}/fr_FR/configuration.html)
