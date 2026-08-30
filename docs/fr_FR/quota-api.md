---
layout: default
title: Limites et bonnes pratiques - Daikin ONECTA
---

# Limites et bonnes pratiques

Daikin impose une limite au nombre de fois que Jeedom peut interroger le cloud par jour. Cette page explique pourquoi cette limite existe et comment l'optimiser.

## Pourquoi une limite ?

Pour fonctionner, le plugin doit régulièrement demander à Daikin l'état de vos appareils (température, mode, marche/arrêt…). Chaque demande compte dans un **quota journalier** fixé par Daikin. Ce quota se réinitialise chaque jour à minuit.

Ce n'est pas une limitation du plugin, mais une règle imposée par le service cloud Daikin ONECTA.

## Quota selon votre mode de connexion

| Mode de connexion | Interrogations autorisées par jour |
|-------------------|-----------------------------------|
| **Mobile App** (recommandé) | 3000 |
| **Developer Portal** | 200 |

> **Recommandé :** utilisez le mode **Mobile App** pour bénéficier d'un quota bien plus confortable.

## Qu'est-ce qui consomme le quota ?

| Action | Consommation |
|--------|-------------|
| Synchronisation planifiée (toutes les 15 min par défaut) | 1 interrogation (pour tous vos appareils à la fois) |
| Commande (changer la température, allumer…) | 1 interrogation par modification |
| Vérification après une commande | 1 interrogation (selon réglages) |
| Mise à jour des compteurs kWh (chaque soir) | 1 interrogation |
| Mise à jour en temps réel (WebSocket, mode Mobile App) | **0** interrogation |

**Point important :** une synchronisation interroge **tous** vos appareils en une seule fois. Avoir 1 ou 5 climatiseurs consomme le même nombre d'interrogations.

## Estimation affichée dans la configuration

Dans la page de configuration (section avancée), le champ **Nombre de requêtes sur la journée** estime combien d'interrogations la synchronisation planifiée consommera chaque jour, en fonction de vos intervalles jour/nuit.

Avec les réglages par défaut (15 min le jour, 30 min la nuit), cela représente environ 80 interrogations par jour, auxquelles s'ajoutent vos commandes et la mise à jour des compteurs.

## Conseils pour optimiser

### Pour la plupart des utilisateurs

1. **Utilisez le mode Mobile App** — quota 15 fois plus élevé.
2. **Laissez les réglages par défaut** — ils sont conçus pour un bon équilibre.
3. **Gardez le WebSocket activé** (mode Mobile App) — les changements d'état arrivent en temps réel sans consommer de quota.

### Si vous êtes en mode Developer Portal (200/jour)

- Ne réduisez pas les intervalles de synchronisation en dessous de 15 minutes.
- Évitez les scénarios qui envoient beaucoup de commandes rapprochées.
- Envisagez de passer au mode Mobile App.

### Si vous avez beaucoup d'appareils et d'automatisations

- Augmentez légèrement les intervalles de synchronisation (ex. 20 min le jour, 45 min la nuit).
- Le mode hybride (défaut) pour le comportement après commande est le plus économique.

## Que se passe-t-il si le quota est atteint ?

Le plugin ralentit automatiquement les synchronisations quand le quota est presque épuisé. Vos commandes continuent de fonctionner, mais les mises à jour d'état peuvent être moins fréquentes jusqu'au lendemain.

En mode Developer Portal, un dépassement du quota peut bloquer toutes les interrogations jusqu'à minuit.

---

[Précédent : Utilisation]({{ site.baseurl }}/fr_FR/utilisation.html) — [Suivant : Dépannage]({{ site.baseurl }}/fr_FR/depannage.html)
