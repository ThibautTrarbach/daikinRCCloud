#!/bin/bash

set -euo pipefail
set -x

echo "Post installation de l'installation/mise à jour des dépendances daikinRCCloud"

BASEDIR=$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)
DAEMON_DIR="$BASEDIR/daikintomqtt"

if [ ! -d "$DAEMON_DIR" ]; then
	echo "ERROR: daikintomqtt absent — le téléchargement a échoué dans pre_install.sh"
	echo "ERROR: Consultez le log daikinRCCloud_packages et vérifiez l'accès à GitHub"
	exit 1
fi

cd "$DAEMON_DIR"
sudo yarn install

if [ ! -f "$DAEMON_DIR/main.js" ]; then
	echo "ERROR: main.js is missing (compiled daemon not found in branch)"
	exit 1
fi

chown -R www-data:www-data "$DAEMON_DIR"

echo "Everything is successfully installed!"
