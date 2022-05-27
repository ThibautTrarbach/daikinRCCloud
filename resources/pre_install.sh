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
rm -R daikintomqtt
git clone https://github.com/ThibautTrarbach/daikintomqtt
echo "Pre install finished"