<?php

/* * ***************************Includes********************************* */
require_once __DIR__ . '/../../../../core/php/core.inc.php';

class daikinRCCloud extends eqLogic
{
    const INSTANCE_ID = '960adb71-4632-4f53-bf47-8ffa5abd7581';
    const PHANTOM_LOGICAL_IDS = array('system');

    /**
     * Vérifie si le daemon atteint une version minimale
     * @return bool
     */
    public static function isDaemonVersionAtLeast($minVersion)
    {
        $deamonVersion = self::getDeamonVersion();
        if ($deamonVersion === '0.0.0') {
            return false;
        }
        return version_compare($deamonVersion, $minVersion, '>=');
    }

    /**
     * Vérifie que le daemon installé respecte la version minimale requise
     * @throws Exception
     */
    public static function assertMinDaemonVersion($minVersion = '2.0.0')
    {
        $version = self::getDeamonVersion();
        if ($version === '0.0.0' || version_compare($version, $minVersion, '<')) {
            throw new Exception('{{Le daemon daikintomqtt >= 2.0.0 est requis. Réinstallez les dépendances du plugin.}}');
        }
    }

    public static function additionnalDependancyCheck()
    {
        $return = array();
        $return['state'] = 'ok';

        if (config::byKey('lastDependancyInstallTime', __CLASS__) == '') {
            $return['state'] = 'nok';
        } elseif (!file_exists(__DIR__ . '/../../resources/daikintomqtt/node_modules')) {
            $return['state'] = 'nok';
        } elseif (!file_exists(__DIR__ . '/../../resources/daikintomqtt/main.js')) {
            $return['state'] = 'nok';
        } elseif (!self::isDaemonVersionAtLeast('2.0.0')) {
            $return['state'] = 'nok';
        }

        return $return;
    }

    public static function deamon_start($_debug = false)
    {
        log::add('daikinRCCloud', 'debug', '[' . __FUNCTION__ . '] ' . 'Inscription au plugin mqtt2');
        self::deamon_stop();
        if (class_exists('mqtt2')) {
            mqtt2::removePluginTopicByPlugin('daikinRCCloud');
            mqtt2::addPluginTopic('daikinRCCloud', config::byKey('prefix', 'daikinRCCloud', 'daikinToMQTT'));
        }
        self::cleanupPhantomEqLogics();
        $deamon_info = self::deamon_info();
        if ($deamon_info['launchable'] != 'ok') {
            throw new Exception('{{Veuillez vérifier la configuration}}');
        }

        $daikin_path = realpath(dirname(__FILE__) . '/../../resources/daikintomqtt');
        $data_path = dirname(__FILE__) . '/../../data/deamon';
        if (!is_dir($data_path)) {
            mkdir($data_path, 0755, true);
        }
        $data_path = realpath(dirname(__FILE__) . '/../../data/deamon');
        self::assertMinDaemonVersion('2.0.0');
        self::configureSettings($data_path);
        $mainScript = $daikin_path . '/main.js';
        if (!file_exists($mainScript)) {
            throw new Exception('{{Le daemon compilé (main.js) est introuvable. Réinstallez les dépendances du plugin.}}');
        }
        $cmd = 'STORE_DIR=' . $data_path;
        $cmd .= ' node --preserve-symlinks ' . $mainScript;
        log::add('daikinRCCloud', 'info', '[' . __FUNCTION__ . '] ' . 'Lancement démon Daikin : ' . $cmd);
        exec($cmd . ' >> ' . log::getPathToLog('daikinRCCloudd') . ' 2>&1 &');
        $i = 0;
        while ($i < 10) {
            $deamon_info = self::deamon_info();
            if ($deamon_info['state'] == 'ok') {
                break;
            }
            sleep(1);
            $i++;
        }
        if ($i >= 10) {
            log::add('daikinRCCloud', 'error', '{{Impossible de lancer le démon daikinRCCloud, vérifiez la log}}', 'unableStartDeamon');
            return false;
        }
        // Mise à jour de la version du daemon
        $deamonVersion = self::getDeamonVersion();
        config::save('deamonVersion', $deamonVersion, 'daikinRCCloud');
        config::save('lastStart', time(), 'daikinRCCloud');
        message::removeAll('daikinRCCloud', 'unableStartDeamon');
        log::add('daikinRCCloud', 'info', '{{Démon daikinRCCloud lancé}}');
        return true;
    }

    public static function deamon_stop()
    {
        log::add('daikinRCCloud', 'debug', '[' . __FUNCTION__ . '] ' . 'Stop démon');
        $find = 'daikintomqtt/main.js';
        $findEscaped = escapeshellarg($find);
        $cmd = "(ps ax || ps w) | grep -ie " . $findEscaped . " | grep -v grep | awk '{print $1}' | xargs " . system::getCmdSudo() . "kill -15 > /dev/null 2>&1";
        exec($cmd);
        $i = 0;
        while ($i < 5) {
            $deamon_info = self::deamon_info();
            if ($deamon_info['state'] == 'nok') {
                break;
            }
            sleep(1);
            $i++;
        }
        if ($i >= 5) {
            system::kill('daikintomqtt/main.js', true);
            $i = 0;
            while ($i < 5) {
                $deamon_info = self::deamon_info();
                if ($deamon_info['state'] == 'nok') {
                    break;
                }
                sleep(1);
                $i++;
            }
        }
        if (class_exists('mqtt2')) {
            mqtt2::removePluginTopicByPlugin('daikinRCCloud');
        }
    }

    public static function deamon_info()
    {
        $return = array();
        $return['log'] = 'daikinRCCloud';
        $return['launchable'] = 'ok';
        $return['state'] = 'nok';
        if (self::isRunning()) {
            $return['state'] = 'ok';
        }
        if (!class_exists('mqtt2')) {
            $return['launchable'] = 'nok';
            $return['launchable_message'] = '{{Le plugin mqtt2 n\'est pas installé}}';
        } else {
            if (mqtt2::deamon_info()['state'] != 'ok') {
                $return['launchable'] = 'nok';
                $return['launchable_message'] = '{{Le démon mqtt2 n\'est pas demarré}}';
            }
        }
        return $return;
    }

    public static function isRunning(): bool
    {
        return !empty(system::ps('daikintomqtt/main.js'));
    }

    public static function configureSettings($_path)
    {
        self::assertMinDaemonVersion('2.0.0');
        // V3 — décommenter quand le daemon >= 3.0.0 sera disponible
        // if (self::isDaemonVersionAtLeast('3.0.0')) {
        //     self::configureSettingsV3($_path);
        //     return;
        // }
        self::configureSettingsV2($_path);
    }

    /**
     * Configuration pour daemon version 2.0.0+
     */
    private static function configureSettingsV2($_path)
    {
        $file = $_path . '/settings.yml';
        $settings = array();
        if (file_exists($file)) {
            unlink($file);
        }

        $lvlConfig = config::byKey('log::level::daikinRCCloud', 'core', '{"100":"0","200":"0","300":"0","400":"0","1000":"0","default":"1"}');
        if ($lvlConfig['100'] == "1") {
            $logLevel = "debug";
        } elseif ($lvlConfig['200'] == "1") {
            $logLevel = "info";
        } elseif ($lvlConfig['300'] == "1") {
            $logLevel = "warn";
        } elseif ($lvlConfig['400'] == "1") {
            $logLevel = "danger";
        } elseif ($lvlConfig['1000'] == "1") {
            $logLevel = "error";
        } else {
            $logLevel = "info";
        }

        $settings['system'] = array();
        $settings['daikin'] = array();
        $settings['mqtt'] = array();
        $settings['integration'] = array();
        $settings['system']['polling'] = array();
        $settings['integration']['homeassistant'] = array();

        $mqttInfos = mqtt2::getFormatedInfos();
        log::add('daikinRCCloud', 'debug', '[' . __FUNCTION__ . '] ' . 'Informations reçues de mqtt2 : ' . json_encode($mqttInfos));

        $authMode = config::byKey('daikin_authMode', 'daikinRCCloud', 'developer_portal');
        $settings['daikin']['authMode'] = $authMode;
        $settings['daikin']['clientID'] = config::byKey('daikin_clientID', 'daikinRCCloud', null);
        $settings['daikin']['clientSecret'] = config::byKey('daikin_clientSecret', 'daikinRCCloud', null);
        $settings['daikin']['clientURL'] = network::getNetworkAccess('internal', 'ip');
        $settings['daikin']['clientPort'] = intval(config::byKey('daikin_clientPort', 'daikinRCCloud', 8765) ?? 8765);
        $settings['daikin']['email'] = config::byKey('daikin_onectaEmail', 'daikinRCCloud', null);
        $onectaPassword = config::byKey('daikin_onectaPassword', 'daikinRCCloud', null);
        if ($onectaPassword !== null && $onectaPassword !== '') {
            $decryptedPassword = utils::decrypt($onectaPassword);
            $settings['daikin']['password'] = ($decryptedPassword !== false && $decryptedPassword !== '') ? $decryptedPassword : $onectaPassword;
        } else {
            $settings['daikin']['password'] = null;
        }
        $settings['daikin']['enableWebSocket'] = (bool) config::byKey('daikin_enableWebSocket', 'daikinRCCloud', 1);
        $settings['daikin']['httpTransport'] = config::byKey('daikin_httpTransport', 'daikinRCCloud', 'node');

        $settings['mqtt']['host'] = $mqttInfos['ip'];
        $settings['mqtt']['port'] = intval($mqttInfos['port']);
        $settings['mqtt']['auth'] = true;
        $settings['mqtt']['username'] = $mqttInfos['user'];
        $settings['mqtt']['password'] = $mqttInfos['password'];
        $settings['mqtt']['connectTimeout'] = 4000;
        $settings['mqtt']['reconnectPeriod'] = 1000;
        $settings['mqtt']['topic'] = config::byKey('prefix', 'daikinRCCloud', 'daikinToMQTT');

        $settings['system']['logLevel'] = $logLevel;
        $settings['system']['polling']['dayInterval'] = intval(config::byKey('daikin_polling_dayInterval', 'daikinRCCloud', 15));
        $settings['system']['polling']['nightInterval'] = intval(config::byKey('daikin_polling_nightInterval', 'daikinRCCloud', 30));
        $settings['system']['polling']['nightStart'] = intval(config::byKey('daikin_polling_nightStart', 'daikinRCCloud', 22));
        $settings['system']['polling']['nightEnd'] = intval(config::byKey('daikin_polling_nightEnd', 'daikinRCCloud', 7));
        $settings['system']['actionRefreshMode'] = intval(config::byKey('daikin_actionRefreshMode', 'daikinRCCloud', 3));
        $settings['system']['actionRefreshDelaySeconds'] = intval(config::byKey('daikin_actionRefreshDelaySeconds', 'daikinRCCloud', 60));
        $settings['system']['actionRefreshStrategy'] = config::byKey('daikin_actionRefreshStrategy', 'daikinRCCloud', 'merge_with_poll');
        $settings['system']['mergeWithPollWindowMinutes'] = intval(config::byKey('daikin_mergeWithPollWindowMinutes', 'daikinRCCloud', 5));
        $settings['system']['commandCoalesceMs'] = intval(config::byKey('daikin_commandCoalesceMs', 'daikinRCCloud', 400));
        $settings['system']['energyStatsRefreshTime'] = config::byKey('daikin_energyStatsRefreshTime', 'daikinRCCloud', '23:58');
        $settings['system']['dynamicFallback'] = (bool) config::byKey('daikin_dynamicFallback', 'daikinRCCloud', 1);
        $settings['system']['exposeReadOnly'] = (bool) config::byKey('daikin_exposeReadOnly', 'daikinRCCloud', 1);
        $settings['system']['publishOnDelta'] = (bool) config::byKey('daikin_publishOnDelta', 'daikinRCCloud', 1);

        $settings['integration']['jeedom'] = true;
        $settings['integration']['homeassistant']['enabled'] = false;

        @yaml_emit_file($file, $settings, YAML_UTF8_ENCODING, YAML_CRLN_BREAK);
    }

    public static function preConfig_daikin_password($value)
    {
        return utils::encrypt($value);
    }

    public static function preConfig_daikin_onectaPassword($value)
    {
        if ($value === '' || $value === null) {
            $values = array(
                'plugin' => 'daikinRCCloud',
                'key' => 'daikin_onectaPassword',
            );
            $sql = 'SELECT `value` FROM config WHERE `key`=:key AND plugin=:plugin';
            $result = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
            return (is_array($result) && isset($result['value'])) ? $result['value'] : '';
        }
        return utils::encrypt($value);
    }

    public static function handleMqttMessage($_message)
    {
        // V3 — décommenter quand le daemon >= 3.0.0 sera disponible
        // if (self::isDaemonVersionAtLeast('3.0.0')) {
        //     self::handleMqttMessageV3($_message);
        //     return;
        // }
        self::handleMqttMessageV2($_message);
    }

    /**
     * Gestion des messages MQTT pour daemon version 2.0.0+
     */
    private static function handleMqttMessageV2($_message)
    {
        log::add('daikinRCCloud_mqtt', 'info', '[' . __FUNCTION__ . '] ' . 'Message Mqtt reçu');
        log::add('daikinRCCloud_mqtt', 'debug', json_encode($_message));
        
        $prefix = config::byKey('prefix', 'daikinRCCloud', 'daikinToMQTT');
        if (!isset($_message[$prefix]) || !is_array($_message[$prefix])) {
            log::add('daikinRCCloud_mqtt', 'warning', '[' . __FUNCTION__ . '] ' . '{{Format de message MQTT invalide ou préfixe incorrect}}');
            return;
        }
        
        $events = $_message[$prefix];

        foreach ($events as $key => $event) {
            if ($key == 'jeedom') {
                self::handleSystemJeedomEventV2($event);
                continue;
            }

            if ($key == 'system') {
                continue;
            }

            if ($key === self::INSTANCE_ID) {
                continue;
            }

            if (!is_array($event)) {
                log::add('daikinRCCloud_mqtt', 'warning', '[' . __FUNCTION__ . '] ' . "{{Événement invalide pour la clé : }} " . $key);
                continue;
            }

            log::add('daikinRCCloud_mqtt', 'debug', '[' . __FUNCTION__ . '] ' . "ID : " . $key . " | Value : " . json_encode($event));

            $eqLogic = eqLogic::byLogicalId($key, 'daikinRCCloud');
            if (!is_object($eqLogic) || ($eqLogic->getName() == $key)) {
                $eqLogic = self::createEqlogic($key, $event);
            }

            if (!is_object($eqLogic)) {
                log::add('daikinRCCloud_mqtt', 'error', '[' . __FUNCTION__ . '] ' . "{{Impossible de créer ou récupérer l'équipement pour : }} " . $key);
                continue;
            }

            if (isset($event['_device']) && is_array($event['_device'])) {
                foreach (array('supportStatus', 'configCoverage', 'configCoverageDetail', 'gatewayModelRaw', 'gatewayModelResolved', 'unitModels', 'unmappedDatapoints', 'debugReport') as $configKey) {
                    if (isset($event['_device'][$configKey])) {
                        $eqLogic->setConfiguration($configKey, $event['_device'][$configKey]);
                    }
                }
                self::notifySupportStatusIfNeeded($eqLogic, $event['_device']);
                $eqLogic->save();
            }

            $cmds = $eqLogic->getCmd('info');
            foreach ($cmds as $cmd) {
                $logicalID = $cmd->getLogicalId();
                if (!isset($event[$logicalID])) {
                    continue;
                }
                try {
                    $value = is_bool($event[$logicalID]) ? ($event[$logicalID] ? 1 : 0) : jeedom::evaluateExpression($event[$logicalID]);
                    log::add('daikinRCCloud_mqtt', 'debug', '[' . __FUNCTION__ . '] ' . "Data Debug => logicalID : " . $logicalID . " | Value : " . json_encode($value));
                    $cmd->event($value);
                } catch (Exception $e) {
                    log::add('daikinRCCloud_mqtt', 'error', '[' . __FUNCTION__ . '] ' . "{{Erreur lors de l'évaluation de la valeur pour }} " . $logicalID . " : " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Supprime les équipements fantômes créés par erreur à partir de topics MQTT internes.
     */
    public static function cleanupPhantomEqLogics()
    {
        foreach (self::PHANTOM_LOGICAL_IDS as $logicalId) {
            if ($logicalId === self::INSTANCE_ID) {
                continue;
            }
            $eqLogic = eqLogic::byLogicalId($logicalId, 'daikinRCCloud');
            if (!is_object($eqLogic)) {
                continue;
            }
            $name = $eqLogic->getName();
            $eqLogic->remove();
            log::add('daikinRCCloud', 'info', '[' . __FUNCTION__ . '] ' . '{{Équipement fantôme supprimé : }}' . $name . ' (' . $logicalId . ')');
        }
    }

    /**
     * Gestion des événements système Jeedom pour daemon version 2.0.0+
     */
    private static function handleSystemJeedomEventV2($event)
    {
        foreach ($event as $uid => $module) {
            $eqLogic = eqLogic::byLogicalId($uid, 'daikinRCCloud');
            if (!is_object($eqLogic)) {
                $eqLogic = new eqLogic();
                $eqLogic->setEqType_name('daikinRCCloud');
                $eqLogic->setName($uid);
                $eqLogic->setLogicalId($uid);
                $eqLogic->setIsEnable(0);
                $eqLogic->save();
            }
            if (is_object($eqLogic)) {
                log::add('daikinRCCloud', 'debug', '[' . __FUNCTION__ . '] ' . "uid : " . $uid . " | Value : " . json_encode($module));
                self::generateCMD($eqLogic, $module);
            }
        }

    }

    public static function generateCMD($eqLogics, $data)
    {
        //$cmdDatas = json_decode($data, TRUE);

        foreach ($data as $cmdData) {
            $cmd = $eqLogics->getCmd($cmdData['type'], $cmdData['logicalID']);
            if (!is_object($cmd)) {
                $cmd = new cmd();
                $cmd->setEqLogic_id($eqLogics->getId());
                $cmd->setLogicalId($cmdData['logicalID']);
                $cmd->setName($cmdData['name']);
                $cmd->setType($cmdData['type']);
                $cmd->setSubType($cmdData['subType']);
                if (isset($cmdData['isHistorized'])) $cmd->setIsHistorized($cmdData['isHistorized'] ? 1 : 0);
                if (isset($cmdData['isVisible'])) $cmd->setIsVisible($cmdData['isVisible'] ? 1 : 0);
                if (isset($cmdData['generic_type'])) $cmd->setGeneric_type($cmdData['generic_type']);
                if (isset($cmdData['template'])) $cmd->setTemplate("dashboard", $cmdData['template']);
                if (isset($cmdData['minValue'])) $cmd->setConfiguration("minValue", $cmdData['minValue']);
                if (isset($cmdData['maxValue'])) $cmd->setConfiguration("maxValue", $cmdData['maxValue']);
                if (isset($cmdData['unite'])) $cmd->setUnite($cmdData['unite']);
                if (isset($cmdData['listValue'])) $cmd->setConfiguration("listValue", $cmdData['listValue']);
            }
            if (isset($cmdData['value'])) {
                $valueCmd = $eqLogics->getCmd('info', $cmdData['value']);
                if (is_object($valueCmd)) {
                    $cmd->setValue($valueCmd->getId());
                }
            }
            $cmd->save();
        }
    }

    private static function needsSupportReporting($deviceInfo)
    {
        if (!is_array($deviceInfo)) {
            return false;
        }
        $supportStatus = isset($deviceInfo['supportStatus']) ? $deviceInfo['supportStatus'] : 'full';
        $configCoverage = isset($deviceInfo['configCoverage']) ? $deviceInfo['configCoverage'] : 'complete';
        return ($supportStatus !== 'full') || ($configCoverage === 'incomplete');
    }

    private static function notifySupportStatusIfNeeded($eqLogic, $deviceInfo)
    {
        if (!self::needsSupportReporting($deviceInfo)) {
            return;
        }
        if ($eqLogic->getConfiguration('supportNotified', 0) == 1) {
            return;
        }

        $supportStatus = $deviceInfo['supportStatus'];
        $configCoverage = isset($deviceInfo['configCoverage']) ? $deviceInfo['configCoverage'] : 'complete';
        $deviceName = $eqLogic->getName();

        if ($supportStatus === 'unsupported') {
            $message = '{{Appareil non supporté détecté : }}' . $deviceName . '. {{Créez un post sur la communauté Jeedom avec les informations de debug de l\'équipement.}}';
            $level = 'danger';
        } elseif ($supportStatus === 'partial') {
            $message = '{{Support partiel détecté : }}' . $deviceName . '. {{Créez un post sur la communauté Jeedom pour améliorer la prise en charge.}}';
            $level = 'warning';
        } else {
            $message = '{{Configuration incomplète détectée : }}' . $deviceName . '. {{Signalez-le sur la communauté Jeedom avec le rapport de debug.}}';
            $level = 'warning';
        }

        message::add('daikinRCCloud', $message, '', $level);
        $eqLogic->setConfiguration('supportNotified', 1);
        $eqLogic->save();
    }

    private static function createEqlogic($key, $event)
    {
        $eqLogic = eqLogic::byLogicalId($key, 'daikinRCCloud');
        $deviceName = isset($event['_device']['name']) && !empty($event['_device']['name']) ? $event['_device']['name'] : $key;
        
        if (!is_object($eqLogic)) {
            $eqLogic = new eqLogic();
            $eqLogic->setEqType_name('daikinRCCloud');
            $eqLogic->setName("Daikin - " . $deviceName);
            $eqLogic->setLogicalId($key);
            $eqLogic->setIsEnable(1);
        }

        if ($eqLogic->getName() == $key) {
            $eqLogic->setName("Daikin - " . $deviceName);
            $eqLogic->setIsEnable(1);
        }

        $deviceConfigKeys = array(
            'timeZone', 'errorCode', 'modelInfo', 'serialNumber',
            'firmwareVersion', 'wifiConnectionSSID', 'wifiConnectionStrength',
            'supportStatus', 'configCoverage', 'configCoverageDetail',
            'gatewayModelRaw', 'gatewayModelResolved', 'unitModels',
            'unmappedDatapoints', 'debugReport'
        );

        foreach ($deviceConfigKeys as $configKey) {
            if (isset($event['_device'][$configKey])) {
                $eqLogic->setConfiguration($configKey, $event['_device'][$configKey]);
            }
        }

        if (isset($event['_device']) && is_array($event['_device'])) {
            self::notifySupportStatusIfNeeded($eqLogic, $event['_device']);
        }

        $eqLogic->save();
        return $eqLogic;
    }

    public function preInsert() {}

    /*
    * Permet de déclencher une action après modification d'une variable de configuration du plugin
    * Exemple avec la variable "param3"
    public static function postConfig_daikin_password($value) {
      // no return value
    }
    */

    /*     * **********************Getteur Setteur*************************** */

    public function postInsert() {}

    public function preUpdate() {}

    public function postUpdate() {}

    public function preSave() {}

    public function postSave() {}

    public function preRemove() {}

    public function postRemove() {}

    public function publishMqttValue($_node, $_args = array())
    {
        log::add('daikinRCCloud_mqtt', 'debug', '[' . __FUNCTION__ . '] ' . 'Publication Mqtt Value' . $_node . ' ' . json_encode($_args));
        mqtt2::publish(config::byKey('prefix', 'daikinRCCloud', 'daikinToMQTT') . '/' . $_node . '/set', $_args);
    }

    public static function getPluginVersion()
    {
        $pluginVersion = '0.0.0';
        try {
            if (!file_exists(dirname(__FILE__) . '/../../plugin_info/info.json')) {
                log::add('daikinRCCloud', "warning", '[Plugin-Version] fichier info.json manquant');
            }
            $data = json_decode(file_get_contents(dirname(__FILE__) . '/../../plugin_info/info.json'), true);
            if (!is_array($data)) {
                log::add('daikinRCCloud', "warning", '[Plugin-Version] Impossible de décoder le fichier info.json');
            }
            try {
                $pluginVersion = $data['pluginVersion'];
            } catch (\Exception $e) {
                log::add('daikinRCCloud', "warning", '[Plugin-Version] Impossible de récupérer la version du plugin');
            }
        } catch (\Exception $e) {
            log::add('daikinRCCloud', 'debug', '[Plugin-Version] Get ERROR :: ' . $e->getMessage());
        }
        log::add('daikinRCCloud', 'info', '[Plugin-Version] PluginVersion :: ' . $pluginVersion);
        return $pluginVersion;
    }

    public static function getDeamonVersion()
    {
        $deamonVersion = '0.0.0';
        try {
            $packageJsonPath = dirname(__FILE__) . '/../../resources/daikintomqtt/package.json';
            if (!file_exists($packageJsonPath)) {
                log::add('daikinRCCloud', "warning", '[Deamon-Version] fichier package.json manquant : ' . $packageJsonPath);
                return $deamonVersion;
            }
            $data = json_decode(file_get_contents($packageJsonPath), true);
            if (!is_array($data)) {
                log::add('daikinRCCloud', "warning", '[Deamon-Version] Impossible de décoder le fichier package.json');
                return $deamonVersion;
            }
            if (isset($data['version'])) {
                $deamonVersion = $data['version'];
            } else {
                log::add('daikinRCCloud', "warning", '[Deamon-Version] Clé "version" introuvable dans package.json');
            }
        } catch (\Exception $e) {
            log::add('daikinRCCloud', 'debug', '[Deamon-Version] Get ERROR :: ' . $e->getMessage());
        }
        log::add('daikinRCCloud', 'info', '[Deamon-Version] DeamonVersion :: ' . $deamonVersion);
        return $deamonVersion;
    }

    /**
     * Sauvegarde la configuration des dépendances dans un fichier JSON
     * pour que le script pre_install.sh puisse l'utiliser
     */
    public static function saveDependencyConfig()
    {
        $configPath = dirname(__FILE__) . '/../../resources/dependency_config.json';
        $configDir = dirname($configPath);
        
        // Créer le répertoire s'il n'existe pas
        if (!is_dir($configDir)) {
            mkdir($configDir, 0755, true);
        }
        
        $dependencyType = config::byKey('daikin_dependency_type', 'daikinRCCloud', 'branch');
        $dependencyRef = config::byKey('daikin_dependency_ref', 'daikinRCCloud', 'release-beta');
        
        // Si aucune référence n'est définie, utiliser la valeur par défaut
        if (empty($dependencyRef)) {
            $dependencyRef = 'release-beta';
            config::save('daikin_dependency_ref', $dependencyRef, 'daikinRCCloud');
        }
        
        $config = array(
            'type' => $dependencyType,
            'ref' => $dependencyRef,
            'repository' => 'https://github.com/ThibautTrarbach/daikintomqtt.git'
        );
        
        $jsonContent = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        file_put_contents($configPath, $jsonContent);
        chmod($configPath, 0644);
        
        log::add('daikinRCCloud', 'debug', '[Dependency-Config] Configuration sauvegardée : ' . $jsonContent);
    }

    /**
     * Hook appelé après la modification d'une configuration
     */
    public static function postConfig_daikin_dependency_type($value)
    {
        self::saveDependencyConfig();
    }

    public static function postConfig_daikin_dependency_ref($value)
    {
        self::saveDependencyConfig();
    }

    /**
     * Estime le nombre de requêtes GET planifiées par jour (miroir daikintomqtt cron.ts).
     *
     * @param array $params dayInterval, nightInterval, nightStart, nightEnd, authMode, enableWebSocket, dailyQuotaLimit
     * @return array
     */
    public static function computePollingEstimate(array $params = array())
    {
        $dayInterval = isset($params['dayInterval']) ? intval($params['dayInterval']) : 15;
        $nightInterval = isset($params['nightInterval']) ? intval($params['nightInterval']) : 30;
        $nightStart = isset($params['nightStart']) ? intval($params['nightStart']) : 22;
        $nightEnd = isset($params['nightEnd']) ? intval($params['nightEnd']) : 7;
        $authMode = isset($params['authMode']) ? $params['authMode'] : 'developer_portal';
        $enableWebSocket = array_key_exists('enableWebSocket', $params) ? (bool) $params['enableWebSocket'] : true;
        $dailyQuotaLimit = isset($params['dailyQuotaLimit']) ? intval($params['dailyQuotaLimit']) : 200;

        $invalid = ($dayInterval <= 0 || $nightInterval <= 0 || $nightStart < 0 || $nightStart > 23 || $nightEnd < 0 || $nightEnd > 23);
        if ($invalid) {
            return array(
                'valid' => false,
                'pollsDay' => 0,
                'pollsNight' => 0,
                'pollsCron' => 0,
                'energyStats' => 1,
                'total' => $dailyQuotaLimit,
                'effectiveDayInterval' => $dayInterval,
                'effectiveNightInterval' => $nightInterval,
                'wsSafetyNetApplied' => false,
                'dayHours' => 0,
                'nightHours' => 0,
            );
        }

        if ($nightStart > $nightEnd) {
            $nightHours = (24 - $nightStart) + $nightEnd;
        } else {
            $nightHours = max(0, $nightEnd - $nightStart);
        }
        $dayHours = 24 - $nightHours;

        $effectiveDayInterval = $dayInterval;
        $effectiveNightInterval = $nightInterval;
        $wsSafetyNetApplied = false;
        if ($authMode === 'mobile_app' && $enableWebSocket) {
            $newDayInterval = max($dayInterval, 30);
            $newNightInterval = max($nightInterval, 60);
            $wsSafetyNetApplied = ($newDayInterval !== $dayInterval || $newNightInterval !== $nightInterval);
            $effectiveDayInterval = $newDayInterval;
            $effectiveNightInterval = $newNightInterval;
        }

        $pollsDay = (int) ceil($dayHours * 60 / $effectiveDayInterval);
        $pollsNight = (int) ceil($nightHours * 60 / $effectiveNightInterval);
        $pollsCron = $pollsDay + $pollsNight;
        $energyStats = 1;

        return array(
            'valid' => true,
            'pollsDay' => $pollsDay,
            'pollsNight' => $pollsNight,
            'pollsCron' => $pollsCron,
            'energyStats' => $energyStats,
            'total' => $pollsCron + $energyStats,
            'effectiveDayInterval' => $effectiveDayInterval,
            'effectiveNightInterval' => $effectiveNightInterval,
            'wsSafetyNetApplied' => $wsSafetyNetApplied,
            'dayHours' => $dayHours,
            'nightHours' => $nightHours,
        );
    }

    /**
     * Texte descriptif de l'estimation polling pour l'UI de configuration.
     *
     * @param array $estimate Résultat de computePollingEstimate()
     * @return string
     */
    public static function formatPollingEstimateDetail(array $estimate)
    {
        if (empty($estimate['valid'])) {
            return '{{Intervalles invalides — estimation indisponible}}';
        }

        $detail = $estimate['pollsDay'] . ' {{polls jour (intervalle}} ' . $estimate['effectiveDayInterval'] . ' {{min)}} + '
            . $estimate['pollsNight'] . ' {{polls nuit (intervalle}} ' . $estimate['effectiveNightInterval'] . ' {{min)}} + '
            . $estimate['energyStats'] . ' {{stats énergie =}} ' . $estimate['total'] . ' {{GET planifiés/jour}}';

        if (!empty($estimate['wsSafetyNetApplied'])) {
            $detail .= ' — {{Filet WebSocket actif (Mobile App)}}';
        }

        $detail .= '. {{Hors commandes, refresh post-action et redémarrage du daemon (+1 GET).}}';

        return $detail;
    }

    // --- Daemon V3 (à implémenter quand daikintomqtt >= 3.0.0) ---
    // private static function configureSettingsV3($_path) { ... }
    // private static function handleMqttMessageV3($_message) { ... }
    // private static function handleSystemJeedomEventV3($event) { ... }

}

class daikinRCCloudCmd extends cmd
{
    /*     * *************************Attributs****************************** */

    /*
    public static $_widgetPossibility = array();
    */

    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
    * Permet d'empêcher la suppression des commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
    public function dontRemoveCmd() {
      return true;
    }
    */

    // Exécution d'une commande
    public function execute($_options = array())
    {
        if ($this->getLogicalId() == 'refresh') {
            $this->getEqLogic()->refresh();
            return true;
        }
        
        log::add('daikinRCCloud', 'debug', '[' . __FUNCTION__ . "] | Options : " . json_encode($_options));

        $deamon = daikinRCCloud::deamon_info();
        if ($deamon['state'] != 'ok') {
            return false;
        }

        $valueCmd = cmd::byId($this->getValue());
        if (!is_object($valueCmd)) {
            log::add('daikinRCCloud', 'error', '[' . __FUNCTION__ . "] | {{Commande info liée introuvable (ID: }} " . $this->getValue() . ")");
            return false;
        }
        $action = $valueCmd->getLogicalId();

        $actionValue = null;
        switch ($this->getSubType()) {
            case 'other':
                if ($action . "_ON" == $this->getLogicalId()) {
                    $actionValue = true;
                } elseif ($action . "_OFF" == $this->getLogicalId()) {
                    $actionValue = false;
                } else {
                    log::add('daikinRCCloud', 'warning', '[' . __FUNCTION__ . "] | {{Type 'other' non reconnu pour l'action : }} " . $action);
                    return false;
                }
                break;
            case 'slider':
                if (!is_array($_options) || !isset($_options['slider'])) {
                    log::add('daikinRCCloud', 'warning', '[' . __FUNCTION__ . "] | {{Options invalides pour slider : }} " . json_encode($_options));
                    return false;
                }
                $actionValue = $_options['slider'];
                break;
            case 'select':
                if (!is_array($_options) || !isset($_options['select'])) {
                    log::add('daikinRCCloud', 'warning', '[' . __FUNCTION__ . "] | {{Options invalides pour select : }} " . json_encode($_options));
                    return false;
                }
                $actionValue = $_options['select'];
                break;
            default:
                log::add('daikinRCCloud', 'warning', '[' . __FUNCTION__ . "] | {{Sous-type non géré : }} " . $this->getSubType());
                return false;
        }

        if ($actionValue === null) {
            log::add('daikinRCCloud', 'error', '[' . __FUNCTION__ . "] | {{Impossible de déterminer la valeur de l'action}}");
            return false;
        }

        $logicalID = $this->getEqLogic()->getLogicalId();
        $data = array(
            $action => $actionValue
        );
        $this->getEqLogic()->publishMqttValue($logicalID, $data);
        return true;
    }

    /*     * **********************Getteur Setteur*************************** */
}
