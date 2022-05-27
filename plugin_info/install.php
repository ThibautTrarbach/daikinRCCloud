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
	config::save('daikin_modeproxy', config::byKey('daikin_modeproxy', 'daikinRCCloud',0));
	config::save('daikin_proxyPort', config::byKey('daikin_proxyPort', 'daikinRCCloud',8888));
	config::save('daikin_proxyWebPort', config::byKey('daikin_proxyWebPort', 'daikinRCCloud',8889));
	config::save('daikin_communicationTimeout', config::byKey('daikin_communicationTimeout', 'daikinRCCloud',10000));
	config::save('daikin_communicationRetries', config::byKey('daikin_communicationRetries', 'daikinRCCloud',3));
	config::save('topic', config::byKey('topic', 'daikinRCCloud','daikinToMQTT'));
}

// Fonction exécutée automatiquement après la mise à jour du plugin
function daikinRCCloud_update() {
	config::save('daikin_modeproxy', config::byKey('daikin_modeproxy', 'daikinRCCloud',0));
	config::save('daikin_proxyPort', config::byKey('daikin_proxyPort', 'daikinRCCloud',8888));
	config::save('daikin_proxyWebPort', config::byKey('daikin_proxyWebPort', 'daikinRCCloud',8889));
	config::save('daikin_communicationTimeout', config::byKey('daikin_communicationTimeout', 'daikinRCCloud',10000));
	config::save('daikin_communicationRetries', config::byKey('daikin_communicationRetries', 'daikinRCCloud',3));
	config::save('topic', config::byKey('topic', 'daikinRCCloud','daikinToMQTT'));
}

// Fonction exécutée automatiquement après la suppression du plugin
function daikinRCCloud_remove() {
}
