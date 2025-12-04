#!/bin/bash

set -x  # make sure each command is printed in the terminal
echo "Pre installation de l'installation/mise à jour des dépendances de daikinRCCloud"

echo "##### Install yaml php and restart apache"
last_check=`php -m | grep 'yaml'`
if [ "${last_check}" = "yaml" ]; then
  echo "Yaml is already installed, nothing to do"
else
  sudo apt-get install -y php-yaml
  sudo service apache2 restart
fi

BASEDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

cd $BASEDIR

# Lecture de la configuration des dépendances
CONFIG_FILE="$BASEDIR/dependency_config.json"
DEPENDENCY_TYPE="branch"
DEPENDENCY_REF="release-stable"
REPOSITORY_URL="https://github.com/ThibautTrarbach/daikintomqtt.git"

if [ -f "$CONFIG_FILE" ]; then
  echo "Lecture de la configuration depuis $CONFIG_FILE"
  # Utilisation de jq si disponible, sinon parsing basique avec grep/sed
  if command -v jq &> /dev/null; then
    DEPENDENCY_TYPE=$(jq -r '.type // "branch"' "$CONFIG_FILE")
    DEPENDENCY_REF=$(jq -r '.ref // "release-stable"' "$CONFIG_FILE")
    REPOSITORY_URL=$(jq -r '.repository // "https://github.com/ThibautTrarbach/daikintomqtt.git"' "$CONFIG_FILE")
  else
    # Parsing basique sans jq
    DEPENDENCY_TYPE=$(grep -o '"type"[[:space:]]*:[[:space:]]*"[^"]*"' "$CONFIG_FILE" | sed 's/.*"type"[[:space:]]*:[[:space:]]*"\([^"]*\)".*/\1/' || echo "branch")
    DEPENDENCY_REF=$(grep -o '"ref"[[:space:]]*:[[:space:]]*"[^"]*"' "$CONFIG_FILE" | sed 's/.*"ref"[[:space:]]*:[[:space:]]*"\([^"]*\)".*/\1/' || echo "release-stable")
    REPOSITORY_URL=$(grep -o '"repository"[[:space:]]*:[[:space:]]*"[^"]*"' "$CONFIG_FILE" | sed 's/.*"repository"[[:space:]]*:[[:space:]]*"\([^"]*\)".*/\1/' || echo "https://github.com/ThibautTrarbach/daikintomqtt.git")
  fi
  echo "Configuration lue - Type: $DEPENDENCY_TYPE, Référence: $DEPENDENCY_REF"
else
  echo "Fichier de configuration non trouvé, utilisation des valeurs par défaut"
fi

# Nettoyage de l'ancien répertoire
rm -rf daikintomqtt

# Clone du dépôt selon le type de référence
if [ "$DEPENDENCY_TYPE" = "commit" ]; then
  echo "Clonage du dépôt et checkout du commit: $DEPENDENCY_REF"
  git clone "$REPOSITORY_URL" daikintomqtt
  cd daikintomqtt
  git checkout "$DEPENDENCY_REF"
  if [ $? -ne 0 ]; then
    echo "Erreur: Impossible de checkout le commit $DEPENDENCY_REF"
    exit 1
  fi
  cd ..
else
  echo "Clonage de la branche: $DEPENDENCY_REF"
  git clone -b "$DEPENDENCY_REF" "$REPOSITORY_URL" daikintomqtt
  if [ $? -ne 0 ]; then
    echo "Erreur: Impossible de cloner la branche $DEPENDENCY_REF"
    exit 1
  fi
fi

echo "Pre install finished"