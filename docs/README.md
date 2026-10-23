# Documentation Daikin ONECTA

Cette documentation est hébergée sur GitHub Pages à l'adresse [docs.daikinrccloud.trarbach.dev](https://docs.daikinrccloud.trarbach.dev).

## Structure

- `index.md` : Page d'accueil avec sélection de langue
- `fr_FR/` : Documentation en français (complète, orientée utilisateurs)
- `en_US/` : Documentation en anglais
- `es_ES/` : Documentation en espagnol
- `de_DE/` : Documentation en allemand
- `it_IT/` : Documentation en italien

### Documentation française (`fr_FR/`)

Documentation orientée **utilisateurs** du plugin Jeedom :

| Fichier | Contenu |
|---------|---------|
| `index.md` | Accueil : à quoi sert le plugin, ce qu'on peut faire |
| `installation.md` | Premiers pas : installer le plugin et faire apparaître les appareils |
| `configuration.md` | Réglages du plugin en langage accessible |
| `authentification.md` | Se connecter à son compte Daikin Onecta |
| `utilisation.md` | Usage quotidien : commandes, dashboard, scénarios |
| `quota-api.md` | Limites cloud Daikin et bonnes pratiques |
| `depannage.md` | Problèmes courants et aide communautaire |
| `changelog.md` | Historique des modifications |
| `changelog_beta.md` | Changelog version beta |
| `index_beta.md` | Sommaire documentation beta |

Chaque dossier de langue (autres que `fr_FR`) contient actuellement :
- `index.md` : Documentation principale (à traduire)
- `changelog.md` : Historique des modifications

## Activation de GitHub Pages

1. Allez dans les **Settings** de votre dépôt GitHub
2. Dans la section **Pages**
3. Sous **Source**, sélectionnez :
   - **Branch** : `main` (ou `master`)
   - **Folder** : `/docs`
4. Cliquez sur **Save**

La documentation sera accessible à l'adresse configurée dans `docs/CNAME`.

## Mise à jour

Pour mettre à jour la documentation, modifiez les fichiers Markdown dans ce dossier et poussez les changements sur GitHub. GitHub Pages se mettra à jour automatiquement.
