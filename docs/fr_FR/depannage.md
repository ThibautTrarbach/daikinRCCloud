---
layout: default
title: Dépannage - Daikin ONECTA
---

# Dépannage

Cette page répond aux problèmes les plus fréquents rencontrés avec le plugin Daikin ONECTA.

## Mes équipements n'apparaissent pas

**Vérifications à faire dans l'ordre :**

1. Le **service du plugin** est-il démarré ? (Outils → Santé)
2. Le plugin **mqtt2** est-il installé, activé et démarré ?
3. Les **dépendances** sont-elles installées ? (bouton « Réinstaller les dépendances »)
4. Vos **identifiants Daikin** sont-ils corrects ? (testez sur l'application Onecta mobile)
5. Vos appareils sont-ils bien visibles dans l'application **Daikin Onecta** sur votre téléphone ?

**Actions :**

- Redémarrez le service du plugin.
- Sauvegardez à nouveau la configuration, puis redémarrez.
- Consultez les logs (voir ci-dessous).

## Le service du plugin ne démarre pas

**Causes fréquentes :**

| Cause | Solution |
|-------|----------|
| Dépendances non installées | Cliquez sur « Réinstaller les dépendances » |
| Identifiants Daikin incorrects | Vérifiez email/mot de passe ou Client ID/Secret |
| mqtt2 non démarré | Démarrez le plugin mqtt2 |

Après correction, redémarrez le service du plugin.

## Erreur de connexion à Daikin

### Mode Mobile App

- Vérifiez que vous pouvez vous connecter à l'application Daikin Onecta sur votre téléphone.
- Si vous avez changé votre mot de passe, mettez-le à jour dans la configuration du plugin.
- Redémarrez le service après modification.

### Mode Developer Portal

- Vérifiez que le Client ID et le Client Secret sont corrects.
- Si Daikin a invalidé votre clé, recommencez la procédure de connexion (voir [Authentification]({{ site.baseurl }}/fr_FR/authentification.html)).
- Vérifiez que le port d'authentification (défaut : 8765) n'est pas bloqué.

## Mes commandes ne réagissent pas

1. L'équipement est-il **activé** dans Jeedom ?
2. Y a-t-il un **code erreur** affiché sur l'équipement ? (onglet Équipement)
3. Le service du plugin est-il toujours **démarré** ?
4. Le quota journalier est-il **épuisé** ? (voir [Limites et bonnes pratiques]({{ site.baseurl }}/fr_FR/quota-api.html))

Essayez d'envoyer la même commande depuis l'application Daikin Onecta sur votre téléphone. Si elle ne fonctionne pas non plus, le problème vient de l'appareil ou du cloud Daikin, pas de Jeedom.

## Peu de commandes sur mon appareil

Les commandes dépendent de votre modèle d'appareil. Si vous voyez peu de commandes :

1. Vérifiez que l'option **Modèles inconnus** est activée (Configuration avancée).
2. Attendez quelques minutes après le démarrage du plugin — la synchronisation peut prendre un moment.
3. Redémarrez le service du plugin.

Si votre modèle est très récent, signalez-le sur le [forum Jeedom](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55) pour améliorer la prise en charge.

## Erreurs réseau répétées

Si le plugin affiche des erreurs de connexion au cloud Daikin (timeouts, blocages) :

1. Ouvrez **Plugins → Daikin ONECTA → Configuration → Configuration avancée**.
2. Passez le **Transport HTTP** de « Node.js » à « curl ».
3. Sauvegardez et redémarrez le service.

## Consulter les logs

Pour diagnostiquer un problème :

1. Allez dans **Analyse → Logs** dans Jeedom.
2. Filtrez par plugin : `daikinRCCloud`.
3. Recherchez les messages d'erreur (en rouge).

## Demander de l'aide

Si vous ne trouvez pas la solution, demandez de l'aide en indiquant :

| Information | Où la trouver |
|-------------|---------------|
| Version du plugin | Configuration → Informations |
| Version du service interne | Configuration → Informations |
| Version de Jeedom | Page d'accueil Jeedom |
| Mode de connexion utilisé | Configuration → Mode d'authentification |
| Description du problème | Ce que vous attendiez vs ce qui se passe |
| Extrait des logs | Analyse → Logs, filtre daikinRCCloud |

### Forum Jeedom

- [Fil de discussion du plugin](https://community.jeedom.com/t/pilotage-nouvelle-gamme-pac-daikin-perfera-ftxm-r/45187/55)
- Sur Jeedom 4.4+, utilisez le bouton **Créer un post Community** sur la page du plugin pour pré-remplir un formulaire d'aide.

### GitHub

Pour signaler un bug : [Issues GitHub](https://github.com/ThibautTrarbach/daikinRCCloud/issues)

---

[Précédent : Limites et bonnes pratiques]({{ site.baseurl }}/fr_FR/quota-api.html) — [Retour à l'accueil]({{ site.baseurl }}/fr_FR/)
