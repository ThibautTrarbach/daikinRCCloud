#!/bin/bash

set -euo pipefail
set -x

echo "Pre installation de l'installation/mise à jour des dépendances de daikinRCCloud"

echo "##### Install yaml php and restart apache"
last_check=$(php -m | grep 'yaml' || true)
if [ "${last_check}" = "yaml" ]; then
	echo "Yaml is already installed, nothing to do"
else
	sudo apt-get install -y php-yaml
	sudo service apache2 restart
fi

BASEDIR=$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)
cd "$BASEDIR"

CONFIG_FILE="$BASEDIR/dependency_config.json"
DEPENDENCY_TYPE="branch"
DEPENDENCY_REF="release-beta"
REPOSITORY_URL="https://github.com/ThibautTrarbach/daikintomqtt.git"

if [ -f "$CONFIG_FILE" ]; then
	echo "Lecture de la configuration depuis $CONFIG_FILE"
	if command -v jq &> /dev/null; then
		DEPENDENCY_TYPE=$(jq -r '.type // "branch"' "$CONFIG_FILE")
		DEPENDENCY_REF=$(jq -r '.ref // "release-beta"' "$CONFIG_FILE")
		REPOSITORY_URL=$(jq -r '.repository // "https://github.com/ThibautTrarbach/daikintomqtt.git"' "$CONFIG_FILE")
	else
		DEPENDENCY_TYPE=$(grep -o '"type"[[:space:]]*:[[:space:]]*"[^"]*"' "$CONFIG_FILE" | sed 's/.*"type"[[:space:]]*:[[:space:]]*"\([^"]*\)".*/\1/' || echo "branch")
		DEPENDENCY_REF=$(grep -o '"ref"[[:space:]]*:[[:space:]]*"[^"]*"' "$CONFIG_FILE" | sed 's/.*"ref"[[:space:]]*:[[:space:]]*"\([^"]*\)".*/\1/' || echo "release-beta")
		REPOSITORY_URL=$(grep -o '"repository"[[:space:]]*:[[:space:]]*"[^"]*"' "$CONFIG_FILE" | sed 's/.*"repository"[[:space:]]*:[[:space:]]*"\([^"]*\)".*/\1/' || echo "https://github.com/ThibautTrarbach/daikintomqtt.git")
	fi
	echo "Configuration lue - Type: $DEPENDENCY_TYPE, Référence: $DEPENDENCY_REF"
else
	echo "Fichier de configuration non trouvé, utilisation des valeurs par défaut"
fi

export GIT_TERMINAL_PROMPT=0
GIT="git -c credential.helper= -c http.version=HTTP/1.1"

clone_with_git() {
	if [ "$DEPENDENCY_TYPE" = "commit" ]; then
		echo "Clonage du dépôt et checkout du commit: $DEPENDENCY_REF"
		$GIT clone --depth 1 "$REPOSITORY_URL" daikintomqtt
		cd daikintomqtt
		$GIT fetch --depth 1 origin "$DEPENDENCY_REF"
		$GIT checkout "$DEPENDENCY_REF"
		cd ..
	else
		echo "Clonage de la branche: $DEPENDENCY_REF"
		$GIT clone --depth 1 -b "$DEPENDENCY_REF" "$REPOSITORY_URL" daikintomqtt
	fi
}

download_with_tarball() {
	local tarball_url="$1"
	local tmpdir
	rm -rf "$BASEDIR/daikintomqtt"
	tmpdir=$(mktemp -d)
	echo "Téléchargement tarball: $tarball_url"
	curl -fsSL -o "$tmpdir/archive.tar.gz" "$tarball_url"
	tar xzf "$tmpdir/archive.tar.gz" -C "$tmpdir"
	local extracted
	extracted=$(find "$tmpdir" -mindepth 1 -maxdepth 1 -type d | head -1)
	if [ -z "$extracted" ] || [ ! -d "$extracted" ]; then
		rm -rf "$tmpdir"
		return 1
	fi
	mv "$extracted" "$BASEDIR/daikintomqtt"
	rm -rf "$tmpdir"
}

tarball_url_for_ref() {
	if [ "$DEPENDENCY_TYPE" = "commit" ]; then
		echo "https://github.com/ThibautTrarbach/daikintomqtt/archive/${DEPENDENCY_REF}.tar.gz"
	else
		echo "https://github.com/ThibautTrarbach/daikintomqtt/archive/refs/heads/${DEPENDENCY_REF}.tar.gz"
	fi
}

rm -rf daikintomqtt

if clone_with_git; then
	echo "Clone git réussi"
elif download_with_tarball "$(tarball_url_for_ref)"; then
	echo "Téléchargement tarball réussi (fallback)"
else
	echo "ERROR: Impossible de récupérer daikintomqtt (git clone et tarball ont échoué)"
	echo "ERROR: Vérifiez l'accès réseau à GitHub et la branche/commit: $DEPENDENCY_REF"
	exit 1
fi

if [ ! -f "$BASEDIR/daikintomqtt/main.js" ]; then
	echo "ERROR: main.js absent après téléchargement (branche $DEPENDENCY_REF invalide ou build CI manquant)"
	exit 1
fi

echo "Pre install finished"
