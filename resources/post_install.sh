#!/bin/bash

set -x  # make sure each command is printed in the terminal
echo "Post installation de l'installation/mise à jour des dépendances daikinRCCloud"

BASEDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

cd $BASEDIR
cd daikintomqtt
sudo yarn install
chown -R www-data:www-data *

echo "Everything is successfully installed!"