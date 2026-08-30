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
| **Developer Portal** (recommandé) | 200 |
| **Mobile App** | 3000 |

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

Dans la page de configuration (section avancée), le champ **Nombre de requêtes planifiées/jour** estime combien d'interrogations GET le daemon planifiera chaque jour, en fonction de vos intervalles jour/nuit, du mode d'authentification et du WebSocket.

| Configuration | Détail | Total planifié |
|---------------|--------|----------------|
| Developer Portal, défauts (15 min jour, 30 min nuit, nuit 22h→7h) | 60 polls jour + 18 polls nuit + 1 stats énergie | **~79 GET/jour** |
| Mobile App + WebSocket activé, mêmes intervalles | Filet de sécurité 30/60 min : 30 + 9 + 1 | **~40 GET/jour** |

Ces chiffres n'incluent pas vos commandes, les refresh post-action (selon réglages) ni le GET de démarrage du daemon (+1 à chaque redémarrage).

## Conseils pour optimiser

### Pour la plupart des utilisateurs (Developer Portal)

1. **Laissez les réglages par défaut** — ils sont conçus pour un bon équilibre avec le quota de 200 interrogations/jour.
2. **Ne réduisez pas les intervalles de synchronisation** en dessous de 15 minutes.
3. **Évitez les scénarios** qui envoient beaucoup de commandes rapprochées.

### Si vous utilisez le mode Mobile App (3000/jour)

- **Gardez le WebSocket activé** — les changements d'état arrivent en temps réel sans consommer de quota.
- Vous pouvez augmenter légèrement les intervalles de synchronisation tout en restant réactif grâce au WebSocket.

### Si vous avez beaucoup d'appareils et d'automatisations

- Augmentez légèrement les intervalles de synchronisation (ex. 20 min le jour, 45 min la nuit).
- Le mode hybride (défaut) pour le comportement après commande est le plus économique.

## Que se passe-t-il si le quota est atteint ?

Le plugin ralentit automatiquement les synchronisations quand le quota est presque épuisé. Vos commandes continuent de fonctionner, mais les mises à jour d'état peuvent être moins fréquentes jusqu'au lendemain.

En mode Developer Portal, un dépassement du quota peut bloquer toutes les interrogations jusqu'à minuit.

---

[Précédent : Utilisation]({{ site.baseurl }}/fr_FR/utilisation.html) — [Suivant : Dépannage]({{ site.baseurl }}/fr_FR/depannage.html)
