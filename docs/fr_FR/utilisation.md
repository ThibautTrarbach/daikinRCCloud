---
layout: default
title: Utilisation - Daikin ONECTA
---

# Utilisation

Cette page explique comment utiliser vos appareils Daikin au quotidien dans Jeedom : retrouver vos équipements, comprendre les commandes disponibles, les afficher sur le dashboard et les intégrer dans des scénarios.

## Retrouver vos équipements

1. Ouvrez **Plugins → Daikin ONECTA**.
2. Vos appareils apparaissent sous **Mes équipements**, sous forme de cartes.

Ils sont ajoutés **automatiquement** lors de la première synchronisation. Il n'y a pas de bouton « Ajouter un équipement ».

### Personnaliser un équipement

Cliquez sur la carte de l'équipement pour accéder à sa configuration :

| Réglage | Description |
|---------|-------------|
| **Nom** | Renommez l'équipement (ex. « Clim salon ») |
| **Objet parent** | Rattachez-le à une pièce ou un étage de votre domotique |
| **Catégorie** | Chauffage, confort, etc. |
| **Activer** | Active ou désactive l'équipement |
| **Visible** | Affiche ou masque l'équipement dans le dashboard |

### Informations sur l'appareil

Dans l'onglet **Équipement**, un encadré affiche les informations remontées par Daikin :

| Information | Utilité |
|-------------|---------|
| **Modèle** | Référence de votre appareil |
| **Numéro de série** | Identifiant unique |
| **Version firmware** | Version du logiciel embarqué |
| **Code erreur** | Code d'alerte éventuel — consultez la notice de votre appareil ou le support Daikin en cas de code affiché |

---

## Les commandes disponibles

Les commandes sont créées automatiquement selon votre modèle d'appareil. Elles se trouvent dans l'onglet **Commandes** de chaque équipement.

> **Note :** toutes les commandes ci-dessous ne sont pas forcément disponibles sur votre appareil. Cela dépend du modèle.

### Pilotage principal

| Ce que vous voulez faire | Commande dans Jeedom |
|--------------------------|---------------------|
| Allumer / éteindre | **State** (ou Marche) |
| Changer le mode (froid, chaud, auto, sec, ventilateur) | **Operation Mode** |
| Régler la température souhaitée | **Temperature Control** |

### Ventilation

| Ce que vous voulez faire | Commande dans Jeedom |
|--------------------------|---------------------|
| Choisir le mode de ventilation | **Fan Current Mode** |
| Régler la vitesse du ventilateur | **Fan Fixed** |
| Orienter le flux d'air horizontalement | **Fan Horizontal** (selon modèle) |
| Orienter le flux d'air verticalement | **Fan Vertical** (selon modèle) |

### Modes spéciaux

| Ce que vous voulez faire | Commande dans Jeedom |
|--------------------------|---------------------|
| Activer le mode économique | **Eco Mode** |
| Activer le mode puissant | **Powerful Mode** |
| Activer le mode streamer (purification) | **Streamer Mode** |

### Informations (lecture seule)

| Ce que vous voulez consulter | Commande dans Jeedom |
|------------------------------|---------------------|
| Température ambiante | **Room Temperature** |
| Température extérieure | **Outdoor Temperature** |
| Humidité ambiante | **Room Humidity** |

### Consommation énergétique

| Ce que vous voulez consulter | Commande dans Jeedom |
|------------------------------|---------------------|
| Consommation chauffage (jour / semaine / mois) | **Heating Consumption D/W/M** |
| Consommation refroidissement (jour / semaine / mois) | **Cooling Consumption D/W/M** |

Les compteurs sont mis à jour automatiquement chaque jour (vers 23h58 par défaut).

### Plannings et modes avancés

| Ce que vous voulez faire | Commande dans Jeedom |
|--------------------------|---------------------|
| Activer / désactiver un planning Onecta | **Schedule** (selon modèle) |
| Activer le mode vacances | **Preset Away** (selon modèle) |
| Lancer une mise à jour firmware | **Firmware Update** (si proposée par Daikin) |

---

## Appareils multi-zones

Certains modèles, notamment les pompes à chaleur **Altherma**, disposent de plusieurs zones indépendantes. Dans ce cas, vous verrez des commandes séparées pour chaque zone :

- **Zone 1** : chauffage principal
- **Zone 2** : eau chaude sanitaire, ou seconde zone de chauffage

Chaque zone possède ses propres commandes de marche, mode et consigne.

---

## Afficher sur le dashboard

Pour piloter vos appareils depuis l'écran d'accueil de Jeedom :

1. Ouvrez l'équipement et allez dans l'onglet **Commandes**.
2. Rendez **visibles** les commandes que vous souhaitez afficher (case à cocher dans la colonne Options).
3. Ajoutez-les à votre dashboard via le configurateur de design Jeedom.

Les commandes binaires (marche/arrêt) s'affichent sous forme de boutons. Les consignes de température s'affichent sous forme de curseur (slider).

**Conseil :** pour un usage quotidien, rendez visibles au minimum **State**, **Operation Mode** et **Temperature Control**.

---

## Utiliser dans les scénarios

Vos appareils Daikin peuvent être intégrés dans n'importe quel scénario Jeedom, comme les autres équipements.

### Exemples

**Confort été :**
> Si température salon > 26°C → Allumer la clim en mode refroidissement, consigne 24°C

**Mode nuit :**
> À 22h00 → Activer le mode Eco sur toutes les climatisations

**Départ en vacances :**
> Quand le mode « Absence » est activé → Activer Preset Away sur la PAC

**Économie d'énergie :**
> Si personne n'est à la maison → Éteindre la climatisation

**Retour à la maison :**
> Quand la géolocalisation détecte un retour → Allumer la clim, mode auto, consigne 22°C

Pour créer un scénario, allez dans **Outils → Scénarios** et utilisez les commandes de vos équipements Daikin comme actions ou conditions.

---

## Modifier les commandes

Les commandes sont générées automatiquement par le plugin. Vous pouvez :

- Renommer une commande
- Changer sa visibilité
- Modifier son unité ou son icône

> **Attention :** évitez de supprimer des commandes générées automatiquement. Elles peuvent être recréées lors de la prochaine synchronisation.

---

[Précédent : Authentification]({{ site.baseurl }}/fr_FR/authentification.html) — [Suivant : Limites et bonnes pratiques]({{ site.baseurl }}/fr_FR/quota-api.html)
