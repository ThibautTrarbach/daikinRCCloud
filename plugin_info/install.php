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
function daikinRCCloud_install() {
	config::remove('daikin_modeproxy', 'daikinRCCloud');
	config::remove('daikin_proxyPort', 'daikinRCCloud');
	config::remove('daikin_proxyWebPort', 'daikinRCCloud');
	config::remove('daikin_communicationTimeout', 'daikinRCCloud');
	config::remove('daikin_communicationRetries', 'daikinRCCloud');
	config::save('topic', config::byKey('topic', 'daikinRCCloud','daikinToMQTT'));
	config::save('daikin_clientID', config::byKey('daikin_clientID', 'daikinRCCloud'));
	config::save('daikin_clientSecret', config::byKey('daikin_clientSecret', 'daikinRCCloud'));
	config::save('daikin_clientPort', config::byKey('daikin_clientPort', 'daikinRCCloud',8765));
}

// Fonction exécutée automatiquement après la mise à jour du plugin
function daikinRCCloud_update() {
    config::remove('daikin_modeproxy', 'daikinRCCloud');
    config::remove('daikin_proxyPort', 'daikinRCCloud');
    config::remove('daikin_proxyWebPort', 'daikinRCCloud');
    config::remove('daikin_communicationTimeout', 'daikinRCCloud');
    config::remove('daikin_communicationRetries', 'daikinRCCloud');
    config::save('topic', config::byKey('topic', 'daikinRCCloud','daikinToMQTT'));
    config::save('daikin_clientID', config::byKey('daikin_clientID', 'daikinRCCloud'));
    config::save('daikin_clientSecret', config::byKey('daikin_clientSecret', 'daikinRCCloud'));
    config::save('daikin_clientPort', config::byKey('daikin_clientPort', 'daikinRCCloud',8765));
}

// Fonction exécutée automatiquement après la suppression du plugin
function daikinRCCloud_remove() {

}
