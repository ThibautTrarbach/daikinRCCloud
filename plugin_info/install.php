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

// Fonction helper pour récupérer une config ou la valeur par défaut si vide
function daikinRCCloud_getConfigValue($key, $default = null) {
    $value = config::byKey($key, 'daikinRCCloud', $default);
    return ($value === '' || $value === null) ? $default : $value;
}

// Fonction commune pour la configuration du plugin (install et update)
function daikinRCCloud_configurePlugin($isUpdate = false) {
    try {
        $pluginVersion = daikinRCCloud::getPluginVersion();
        config::save('pluginVersion', $pluginVersion, 'daikinRCCloud');
        $deamonVersion = daikinRCCloud::getDeamonVersion();
        config::save('deamonVersion', $deamonVersion, 'daikinRCCloud');
    } catch (\Exception $e) {
        log::add('daikinRCCloud', 'error', '{{Erreur lors de la récupération de la version du plugin : }} ' . $e->getMessage());
    }
    
    // Suppression des anciennes configurations
    config::remove('daikin_modeproxy', 'daikinRCCloud');
    config::remove('daikin_proxyPort', 'daikinRCCloud');
    config::remove('daikin_proxyWebPort', 'daikinRCCloud');
    config::remove('daikin_communicationTimeout', 'daikinRCCloud');
    config::remove('daikin_communicationRetries', 'daikinRCCloud');
    config::remove('rate_lastupdate', 'daikinRCCloud');
    config::remove('rate_remainingMinute', 'daikinRCCloud');
    config::remove('rate_remainingDay', 'daikinRCCloud');
    
    // Sauvegarde des configurations avec gestion des valeurs vides
    config::save('topic', daikinRCCloud_getConfigValue('topic', 'daikinToMQTT'), 'daikinRCCloud');
    
    $clientID = daikinRCCloud_getConfigValue('daikin_clientID', '');
    if ($clientID !== '') config::save('daikin_clientID', $clientID, 'daikinRCCloud');
    
    $clientSecret = daikinRCCloud_getConfigValue('daikin_clientSecret', '');
    if ($clientSecret !== '') config::save('daikin_clientSecret', $clientSecret, 'daikinRCCloud');
    
    config::save('daikin_clientPort', daikinRCCloud_getConfigValue('daikin_clientPort', 8765), 'daikinRCCloud');
    config::save('daikin_polling_dayInterval', daikinRCCloud_getConfigValue('daikin_polling_dayInterval', 10), 'daikinRCCloud');
    config::save('daikin_polling_nightInterval', daikinRCCloud_getConfigValue('daikin_polling_nightInterval', 20), 'daikinRCCloud');
    config::save('daikin_polling_nightStart', daikinRCCloud_getConfigValue('daikin_polling_nightStart', 22), 'daikinRCCloud');
    config::save('daikin_polling_nightEnd', daikinRCCloud_getConfigValue('daikin_polling_nightEnd', 7), 'daikinRCCloud');
    config::save('daikin_actionRefreshMode', daikinRCCloud_getConfigValue('daikin_actionRefreshMode', 3), 'daikinRCCloud');
    config::save('daikin_actionRefreshDelaySeconds', daikinRCCloud_getConfigValue('daikin_actionRefreshDelaySeconds', 120), 'daikinRCCloud');
    config::save('daikin_dependency_type', daikinRCCloud_getConfigValue('daikin_dependency_type', 'branch'), 'daikinRCCloud');

    $dependencyRef = daikinRCCloud_getConfigValue('daikin_dependency_ref', 'release-beta');
    $v1Branches = array('release-stable', 'release-dev', 'dev', 'stable');
    if ($isUpdate && in_array($dependencyRef, $v1Branches, true)) {
        $dependencyRef = 'release-beta';
        log::add('daikinRCCloud', 'info', '{{Migration daemon V1 → V2 : branche mise à jour vers release-beta}}');
    }
    config::save('daikin_dependency_ref', $dependencyRef, 'daikinRCCloud');
    
    // Sauvegarde de la configuration des dépendances
    daikinRCCloud::saveDependencyConfig();
}

// Fonction commune pour la suppression du dossier daemon
function daikinRCCloud_removeDaemonFolder() {
    $pathDeamon = dirname(__FILE__) . '/../resources/daikintomqtt';
    log::add('daikinRCCloud', 'debug', $pathDeamon);
    
    if (is_dir($pathDeamon)) {
        $pathDeamonEscaped = escapeshellarg($pathDeamon);
        exec('sudo rm -rf ' . $pathDeamonEscaped . ' 2>&1', $output, $return_var);
        if ($return_var !== 0) {
            log::add('daikinRCCloud', 'error', '{{Erreur lors de la suppression du dossier daemon : }} ' . implode("\n", $output));
        }
    }
}

// Fonction exécutée automatiquement après l'installation du plugin
function daikinRCCloud_install()
{
    daikinRCCloud_configurePlugin(false);
    daikinRCCloud_removeDaemonFolder();
    log::add('daikinRCCloud', 'info', '{{Une mise à jour des dépendances sera nécessaire}}');
}

// Fonction exécutée automatiquement après la mise à jour du plugin
function daikinRCCloud_update()
{
    daikinRCCloud_configurePlugin(true);
    daikinRCCloud_removeDaemonFolder();
    log::add('daikinRCCloud', 'info', '{{Une mise à jour des dépendances sera nécessaire}}');
}

// Fonction exécutée automatiquement après la suppression du plugin
function daikinRCCloud_remove()
{
    daikinRCCloud_removeDaemonFolder();
}