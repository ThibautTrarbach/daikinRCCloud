<?php
/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

// Fonction exécutée automatiquement après l'installation du plugin
function daikinRCCloud_install()
{
    $pluginVersion = daikinRCCloud::getPluginVersion();
    config::save('pluginVersion', $pluginVersion, 'daikinRCCloud');
    $deamonVersion = daikinRCCloud::getDeamonVersion();
    config::save('deamonVersion', $deamonVersion, 'daikinRCCloud');
    config::remove('daikin_modeproxy', 'daikinRCCloud');
    config::remove('daikin_proxyPort', 'daikinRCCloud');
    config::remove('daikin_proxyWebPort', 'daikinRCCloud');
    config::remove('daikin_communicationTimeout', 'daikinRCCloud');
    config::remove('daikin_communicationRetries', 'daikinRCCloud');
    config::save('topic', config::byKey('topic', 'daikinRCCloud', 'daikinToMQTT'));
    config::save('daikin_clientID', config::byKey('daikin_clientID', 'daikinRCCloud'));
    config::save('daikin_clientSecret', config::byKey('daikin_clientSecret', 'daikinRCCloud'));
    config::save('daikin_clientPort', config::byKey('daikin_clientPort', 'daikinRCCloud', 8765));
    config::save('daikin_polling_dayInterval', config::byKey('daikin_polling_dayInterval', 'daikinRCCloud', 10));
    config::save('daikin_polling_nightInterval', config::byKey('daikin_polling_nightInterval', 'daikinRCCloud', 30));
    config::save('daikin_polling_nightStart', config::byKey('daikin_polling_nightStart', 'daikinRCCloud', 21));
    config::save('daikin_polling_nightEnd', config::byKey('daikin_polling_nightEnd', 'daikinRCCloud', 8));
    config::save('daikin_actionRefreshMode', config::byKey('daikin_actionRefreshMode', 'daikinRCCloud', 3));
    config::save('daikin_actionRefreshDelaySeconds', config::byKey('daikin_actionRefreshDelaySeconds', 'daikinRCCloud', 120));


    $pathDeamon = dirname(__FILE__) . '/../resources/daikintomqtt';
    log::add('daikinRCCloud', 'debug', $pathDeamon);

    exec('sudo rm -rf ' . $pathDeamon);
    log::add('daikinRCCloud', 'info', '{{Une mise à jour des dépendances sera nécessaire}}');
}

// Fonction exécutée automatiquement après la mise à jour du plugin
function daikinRCCloud_update()
{
    $pluginVersion = daikinRCCloud::getPluginVersion();
    config::save('pluginVersion', $pluginVersion, 'daikinRCCloud');
    $deamonVersion = daikinRCCloud::getDeamonVersion();
    config::save('deamonVersion', $deamonVersion, 'daikinRCCloud');
    config::remove('daikin_modeproxy', 'daikinRCCloud');
    config::remove('daikin_proxyPort', 'daikinRCCloud');
    config::remove('daikin_proxyWebPort', 'daikinRCCloud');
    config::remove('daikin_communicationTimeout', 'daikinRCCloud');
    config::remove('daikin_communicationRetries', 'daikinRCCloud');

    config::remove('rate_lastupdate', 'daikinRCCloud');
    config::remove('rate_remainingMinute', 'daikinRCCloud');
    config::remove('rate_remainingDay', 'daikinRCCloud');

    config::save('topic', config::byKey('topic', 'daikinRCCloud', 'daikinToMQTT'));
    config::save('daikin_clientID', config::byKey('daikin_clientID', 'daikinRCCloud'));
    config::save('daikin_clientSecret', config::byKey('daikin_clientSecret', 'daikinRCCloud'));
    config::save('daikin_clientPort', config::byKey('daikin_clientPort', 'daikinRCCloud', 8765));

    config::save('daikin_polling_dayInterval', config::byKey('daikin_polling_dayInterval', 'daikinRCCloud', 10));
    config::save('daikin_polling_nightInterval', config::byKey('daikin_polling_nightInterval', 'daikinRCCloud', 20));
    config::save('daikin_polling_nightStart', config::byKey('daikin_polling_nightStart', 'daikinRCCloud', 22));
    config::save('daikin_polling_nightEnd', config::byKey('daikin_polling_nightEnd', 'daikinRCCloud', 7));

    config::save('daikin_actionRefreshMode', config::byKey('daikin_actionRefreshMode', 'daikinRCCloud', 3));
    config::save('daikin_actionRefreshDelaySeconds', config::byKey('daikin_actionRefreshDelaySeconds', 'daikinRCCloud', 120));

    $pathDeamon = dirname(__FILE__) . '/../resources/daikintomqtt';
    log::add('daikinRCCloud', 'debug', $pathDeamon);

    exec('sudo rm -rf ' . $pathDeamon);
    log::add('daikinRCCloud', 'info', '{{Une mise à jour des dépendances sera nécessaire}}');
}

// Fonction exécutée automatiquement après la suppression du plugin
function daikinRCCloud_remove()
{
    $pathDeamon = dirname(__FILE__) . '/../resources/daikintomqtt';
    log::add('daikinRCCloud', 'debug', $pathDeamon);

    exec('sudo rm -rf ' . $pathDeamon);
}