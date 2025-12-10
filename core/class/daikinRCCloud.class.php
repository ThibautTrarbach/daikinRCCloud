<?php

/* * ***************************Includes********************************* */
require_once __DIR__ . '/../../../../core/php/core.inc.php';

class daikinRCCloud extends eqLogic
{
    /**
     * Vérifie si le daemon est en version 2.0.0 ou supérieure
     * @return bool
     */
    public static function isDaemonVersion2Plus()
    {
        $deamonVersion = self::getDeamonVersion();
        if ($deamonVersion == '0.0.0') {
            // Si la version n'est pas disponible, on suppose qu'on est en version 2.0.0+
            // pour utiliser les nouvelles fonctionnalités
            return true;
        }
        return version_compare($deamonVersion, '2.0.0', '>=');
    }

    public static function additionnalDependancyCheck()
    {
        $return = array();
        $return['state'] = 'ok';

        if (config::byKey('lastDependancyInstallTime', __CLASS__) == '') {
            $return['state'] = 'nok';
        } elseif (!file_exists(__DIR__ . '/../../resources/daikintomqtt/node_modules')) {
            $return['state'] = 'nok';
        }

        return $return;
    }

    public static function deamon_start($_debug = false)
    {
        log::add('daikinRCCloud', 'debug', '[' . __FUNCTION__ . '] ' . 'Inscription au plugin mqtt2');
        self::deamon_stop();
        mqtt2::addPluginTopic('daikinRCCloud', config::byKey('prefix', 'daikinRCCloud', 'daikinToMQTT'));
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
        self::configureSettings($data_path);
        chdir($daikin_path);
        $cmd = 'STORE_DIR=' . $data_path;
        $cmd .= ' node --preserve-symlinks daikinToMQTT.js';
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
        $find = 'daikinToMQTT.js';
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
            system::kill('daikinToMQTT.js', true);
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
        return !empty(system::ps('daikinToMQTT.js'));
    }

    public static function configureSettings($_path)
    {
        if (self::isDaemonVersion2Plus()) {
            self::configureSettingsV2($_path);
        } else {
            self::configureSettingsV1($_path);
        }
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

        $settings['daikin']['clientID'] = config::byKey('daikin_clientID', 'daikinRCCloud', null);
        $settings['daikin']['clientSecret'] = config::byKey('daikin_clientSecret', 'daikinRCCloud', null);
        $settings['daikin']['clientURL'] = network::getNetworkAccess('internal', 'ip');
        $settings['daikin']['clientPort'] = intval(config::byKey('daikin_clientPort', 'daikinRCCloud', 8765) ?? 8765);

        $settings['mqtt']['host'] = $mqttInfos['ip'];
        $settings['mqtt']['port'] = intval($mqttInfos['port']);
        $settings['mqtt']['auth'] = true;
        $settings['mqtt']['username'] = $mqttInfos['user'];
        $settings['mqtt']['password'] = $mqttInfos['password'];
        $settings['mqtt']['connectTimeout'] = 4000;
        $settings['mqtt']['reconnectPeriod'] = 1000;
        $settings['mqtt']['topic'] = config::byKey('prefix', 'daikinRCCloud', 'daikinToMQTT');

        $settings['system']['logLevel'] = $logLevel;
        $settings['system']['polling']['dayInterval'] = intval(config::byKey('daikin_polling_dayInterval', 'daikinRCCloud', 10));
        $settings['system']['polling']['nightInterval'] = intval(config::byKey('daikin_polling_nightInterval', 'daikinRCCloud', 20));
        $settings['system']['polling']['nightStart'] = intval(config::byKey('daikin_polling_nightStart', 'daikinRCCloud', 22));
        $settings['system']['polling']['nightEnd'] = intval(config::byKey('daikin_polling_nightEnd', 'daikinRCCloud', 7));
        $settings['system']['actionRefreshMode'] = intval(config::byKey('daikin_actionRefreshMode', 'daikinRCCloud', 3));
        $settings['system']['actionRefreshDelaySeconds'] = intval(config::byKey('daikin_actionRefreshDelaySeconds', 'daikinRCCloud', 120));

        $settings['integration']['jeedom'] = true;
        $settings['integration']['homeassistant']['enabled'] = false;

        @yaml_emit_file($file, $settings, YAML_UTF8_ENCODING, YAML_CRLN_BREAK);
    }

    /**
     * Configuration pour daemon version < 2.0.0 (ancienne version)
     */
    private static function configureSettingsV1($_path)
    {
        $file = $_path . '/settings.yml';
        $settings = array();
        if (file_exists($file)) {
            unlink($file);
        }

        $lvlConfig = config::byKey('log::level::daikinRCCloud', 'core', '{"100":"0","200":"0","300":"0","400":"0","1000":"0","default":"1"}');
        $logLevel = "info";
        if ($lvlConfig['100'] == "1") $logLevel = "debug";
        elseif ($lvlConfig['200'] == "1") $logLevel = "info";
        elseif ($lvlConfig['300'] == "1") $logLevel = "warn";
        elseif ($lvlConfig['400'] == "1") $logLevel = "danger";
        elseif ($lvlConfig['1000'] == "1") $logLevel = "error";

        $settings['system'] = array();
        $settings['daikin'] = array();
        $settings['mqtt'] = array();

        $mqttInfos = mqtt2::getFormatedInfos();
        log::add('daikinRCCloud', 'debug', '[' . __FUNCTION__ . '] ' . 'Informations reçues de mqtt2 : ' . json_encode($mqttInfos));

        $settings['daikin']['clientID'] = config::byKey('daikin_clientID', 'daikinRCCloud', null);
        $settings['daikin']['clientSecret'] = config::byKey('daikin_clientSecret', 'daikinRCCloud', null);
        $settings['daikin']['clientURL'] = network::getNetworkAccess('internal', 'ip');
        $settings['daikin']['clientPort'] = config::byKey('daikin_clientPort', 'daikinRCCloud', 8765) ?? 8765;

        $settings['mqtt']['host'] = $mqttInfos['ip'];
        $settings['mqtt']['port'] = $mqttInfos['port'];
        $settings['mqtt']['auth'] = true;
        $settings['mqtt']['username'] = $mqttInfos['user'];
        $settings['mqtt']['password'] = $mqttInfos['password'];
        $settings['mqtt']['connectTimeout'] = 4000;
        $settings['mqtt']['reconnectPeriod'] = 1000;
        $settings['mqtt']['topic'] = config::byKey('prefix', 'daikinRCCloud', 'daikinToMQTT');

        $settings['system']['logLevel'] = $logLevel;
        $settings['system']['jeedom'] = TRUE;

        @yaml_emit_file($file, $settings, YAML_UTF8_ENCODING, YAML_CRLN_BREAK);
    }

    public static function preConfig_daikin_password($value)
    {
        return utils::encrypt($value);
    }

    public static function handleMqttMessage($_message)
    {
        if (self::isDaemonVersion2Plus()) {
            self::handleMqttMessageV2($_message);
        } else {
            self::handleMqttMessageV1($_message);
        }
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
     * Gestion des messages MQTT pour daemon version < 2.0.0 (ancienne version)
     */
    private static function handleMqttMessageV1($_message)
    {
        log::add('daikinRCCloud_mqtt', 'debug', '[' . __FUNCTION__ . '] ' . 'Message Mqtt reçu');
        log::add('daikinRCCloud_mqtt', 'debug', json_encode($_message));
        $events = $_message[config::byKey('prefix', 'daikinRCCloud', 'daikinToMQTT')];

        foreach ($events as $key => $event) {
            if ($key == 'system') {
                self::handleSystemEventV1($event);
                continue;
            }

            log::add('daikinRCCloud_mqtt', 'debug', '[' . __FUNCTION__ . '] ' . "ID : " . $key . " | Value : " . json_encode($event));

            $eqLogic = eqLogic::byLogicalId($key, 'daikinRCCloud');
            if (!is_object($eqLogic) || $eqLogic->getName() == $key) {
                $eqLogic = self::createEqlogic($key, $event);
            }

            $cmds = $eqLogic->getCmd('info');
            foreach ($cmds as $cmd) {
                $logicalID = $cmd->getLogicalId();
                if (!isset($event[$logicalID])) continue;
                $value = is_bool($event[$logicalID]) ? ($event[$logicalID] ? 1 : 0) : jeedom::evaluateExpression($event[$logicalID]);
                log::add('daikinRCCloud_mqtt', 'debug', '[' . __FUNCTION__ . '] ' . "Data Debug => logicalID : " . $logicalID . " | Value : " . json_encode($value));
                $cmd->event($value);
            }
        }
    }

    /**
     * Gestion des événements système pour daemon version < 2.0.0 (ancienne version)
     */
    private static function handleSystemEventV1($event)
    {
        if (isset($event['jeedom'])) self::handleSystemJeedomEventV1($event['jeedom']);
        if (isset($event['bridge'])) self::handleSystemBridgeEventV1($event['bridge']);
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

    private static function handleSystemJeedomEventV1($event)
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

    private static function handleSystemBridgeEventV1($event)
    {
        if (isset($event['error'])) {
            $error = $event['error'];
            if ($error !== "No Error") {
                log::add('daikinRCCloud', 'error', '[DAEMON] ' . "{{Erreur : }} " . $error);
                plugin::byId('daikinRCCloud')->deamon_changeAutoMode(0);
            }
        }

        if (isset($event['authorization_request']) && $event['authorization_request']) {
            config::save('rate_remainingMinute', 0, 'daikinRCCloud');
            config::save('rate_remainingDay', 0, 'daikinRCCloud');
            log::add('daikinRCCloud', 'info', __('Une authentication est necesaire, voici l\'url : ' . $event['url'], __FILE__));
            message::add('daikinRCCloud', __('Une authentication est necesaire, voici l\'url : <a href="' . $event['url'] . '" target="_blank"> Authentication </a>', __FILE__), null, null);
        }

        if (isset($event['authorization_timeout']) && $event['authorization_timeout']) {
            config::save('rate_remainingMinute', 0, 'daikinRCCloud');
            config::save('rate_remainingDay', 0, 'daikinRCCloud');
            log::add('daikinRCCloud', 'info', __('L\'authentification c\'est coupée au bout de 120 secondes. Merci de relancer le deamon pour essayer à nouveau', __FILE__));
            message::add('daikinRCCloud', __('L\'authentification c\'est coupée au bout de 120 secondes. Merci de relancer le deamon pour essayer à nouveau', __FILE__), null, null);
        }

        if (isset($event['rate']) && $event['rate']) {
            if (isset($event['rate']['remainingMinute'])) {
                config::save('rate_remainingMinute', $event['rate']['remainingMinute'], 'daikinRCCloud');
            }
            if (isset($event['rate']['remainingDay'])) {
                config::save('rate_remainingDay', $event['rate']['remainingDay'], 'daikinRCCloud');
            }

            config::save('rate_lastupdate', date('d-m-Y H:i:s', time()), 'daikinRCCloud');
            log::add('daikinRCCloud', 'debug', __('Rate limite : ' . json_encode($event['rate']), __FILE__));
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
            'firmwareVersion', 'wifiConnectionSSID', 'wifiConnectionStrength'
        );
        
        foreach ($deviceConfigKeys as $configKey) {
            if (isset($event['_device'][$configKey])) {
                $eqLogic->setConfiguration($configKey, $event['_device'][$configKey]);
            }
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
        $dependencyRef = config::byKey('daikin_dependency_ref', 'daikinRCCloud', 'release-stable');
        
        // Si aucune référence n'est définie, utiliser la valeur par défaut
        if (empty($dependencyRef)) {
            $dependencyRef = 'release-stable';
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
