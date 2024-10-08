<?php

/* * ***************************Includes********************************* */
require_once __DIR__ . '/../../../../core/php/core.inc.php';

class daikinRCCloud extends eqLogic
{
    public static function additionnalDependancyCheck()
    {
        $return = array();
        $return['state'] = 'ok';

        if (config::byKey('lastDependancyInstallTime', __CLASS__) == '') $return['state'] = 'nok';
        elseif (!file_exists(__DIR__ . '/../../resources/daikintomqtt/node_modules')) $return['state'] = 'nok';

        return $return;
    }

    public static function deamon_start($_debug = false)
    {
        log::add('daikinRCCloud', 'debug', '[' . __FUNCTION__ . '] ' . 'Inscription au plugin mqtt2');
        self::deamon_stop();
        mqtt2::addPluginTopic('daikinRCCloud', config::byKey('prefix', 'daikinRCCloud', 'daikinToMQTT'));
        $deamon_info = self::deamon_info();
        if ($deamon_info['launchable'] != 'ok') {
            throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
        }

        $daikin_path = realpath(dirname(__FILE__) . '/../../resources/daikintomqtt');
        $data_path = dirname(__FILE__) . '/../../data/deamon';
        if (!is_dir($data_path)) mkdir($data_path, 0777, true);
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
            log::add('daikinRCCloud', 'error', 'Impossible de lancer le démon daikinRCCloud, vérifiez la log', 'unableStartDeamon');
            return false;
        }
        config::save('lastStart', time(), 'daikinRCCloud');
        message::removeAll('daikinRCCloud', 'unableStartDeamon');
        log::add('daikinRCCloud', 'info', 'Démon daikinRCCloud lancé');
        return true;
    }

    public static function deamon_stop()
    {
        log::add('daikinRCCloud', 'debug', '[' . __FUNCTION__ . '] ' . 'Stop démon');
        $find = 'daikinToMQTT.js';
        $cmd = "(ps ax || ps w) | grep -ie '" . $find . "' | grep -v grep | awk '{print $1}' | xargs " . system::getCmdSudo() . "kill -15 > /dev/null 2>&1";
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
            $return['launchable_message'] = __('Le plugin mqtt2 n\'est pas installé', __FILE__);
        } else {
            if (mqtt2::deamon_info()['state'] != 'ok') {
                $return['launchable'] = 'nok';
                $return['launchable_message'] = __('Le démon mqtt2 n\'est pas demarré', __FILE__);
            }
        }
        return $return;
    }

    public static function isRunning(): bool
    {
        if (!empty(system::ps('daikinToMQTT.js'))) {
            return true;
        }
        return false;
    }

    public static function configureSettings($_path)
    {
        $file = $_path . '/settings.yml';
        $settings = array();
        if (file_exists($file)) {
            unlink($file);
        }

        $lvlConfig = config::byKey('log::level::daikinRCCloud', 'core', '{"100":"0","200":"0","300":"0","400":"0","1000":"0","default":"1"}');
        $logLevel= "info";
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
        log::add('daikinRCCloud', 'debug', '[' . __FUNCTION__ . '] ' . 'Message Mqtt reçu');
        log::add('daikinRCCloud', 'debug', json_encode($_message));
        $events = $_message[config::byKey('prefix', 'daikinRCCloud', 'daikinToMQTT')];

        foreach ($events as $key => $event) {
            if ($key == 'system') {
                self::handleSystemEvent($event);
                continue;
            }

            log::add('daikinRCCloud', 'debug', '[' . __FUNCTION__ . '] ' . "ID : " . $key . " | Value : " . json_encode($event));

            $eqLogic = eqLogic::byLogicalId($key, 'daikinRCCloud');
            if (!is_object($eqLogic) || $eqLogic->getName() == $key) $eqLogic = self::createEqlogic($key, $event);

            $cmds = $eqLogic->getCmd('info');
            foreach ($cmds as $cmd) {
                $logicalID = $cmd->getLogicalId();
                if (!isset($event[$logicalID])) continue;
                $value = jeedom::evaluateExpression($event[$logicalID]);
                log::add('daikinRCCloud', 'debug', '[' . __FUNCTION__ . '] ' . "Data Debug => logicalID : " . $logicalID . " | Value : " . $value);
                $cmd->event($value);
            }
        }
    }

    private static function handleSystemEvent($event)
    {
        if (isset($event['jeedom'])) self::handleSystemJeedomEvent($event['jeedom']);
        if (isset($event['bridge'])) self::handleSystemBridgeEvent($event['bridge']);
    }

    private static function handleSystemJeedomEvent($event)
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
                if (isset($cmdData['isHistorized'])) $cmd->setIsHistorized($cmdData['isHistorized'] ? 1 : 0);
                if (isset($cmdData['isVisible'])) $cmd->setIsVisible($cmdData['isVisible'] ? 1 : 0);
                if (isset($cmdData['generic_type'])) $cmd->setGeneric_type($cmdData['generic_type']);
                if (isset($cmdData['template'])) $cmd->setTemplate("dashboard", $cmdData['template']);
            }
            $cmd->setType($cmdData['type']);
            $cmd->setSubType($cmdData['subType']);
            if (isset($cmdData['unite'])) $cmd->setUnite($cmdData['unite']);
            if (isset($cmdData['value'])) $cmd->setValue($eqLogics->getCmd('info', $cmdData['value'])->getId());
            if (isset($cmdData['minValue'])) $cmd->setConfiguration("minValue", $cmdData['minValue']);
            if (isset($cmdData['maxValue'])) $cmd->setConfiguration("maxValue", $cmdData['maxValue']);
            if (isset($cmdData['listValue'])) $cmd->setConfiguration("listValue", $cmdData['listValue']);

            $cmd->save();
        }
    }

    private static function handleSystemBridgeEvent($event)
    {
        if (isset($event['error'])) {
            $error = $event['error'];
            if ($error !== "No Error") {
                log::add('daikinRCCloud', 'error', '[DAEMON] ' . "Erreur : " . $error);
                plugin::byId('daikinRCCloud')->deamon_changeAutoMode(0);
            }
        }

        if (isset($event['authorization_request']) && $event['authorization_request']) {
            config::save('rate_limitMinute', 0, 'daikinRCCloud');
            config::save('rate_remainingMinute', 0, 'daikinRCCloud');
            config::save('rate_limitDay', 0, 'daikinRCCloud');
            config::save('rate_remainingDay', 0, 'daikinRCCloud');
            log::add('daikinRCCloud', 'info', __('Une authentication est necesaire, voici l\'url : ' . $event['url'], __FILE__));
            message::add('daikinRCCloud', __('Une authentication est necesaire, voici l\'url : <a href="' . $event['url'] . '" target="_blank"> Authentication </a>', __FILE__), null, null);
        }

        if (isset($event['authorization_timeout']) && $event['authorization_timeout']) {
            config::save('rate_limitMinute', 0, 'daikinRCCloud');
            config::save('rate_remainingMinute', 0, 'daikinRCCloud');
            config::save('rate_limitDay', 0, 'daikinRCCloud');
            config::save('rate_remainingDay', 0, 'daikinRCCloud');
            log::add('daikinRCCloud', 'info', __('L\'authentification c\'est coupée au bout de 120 secondes. Merci de relancer le deamon pour essayer à nouveau', __FILE__));
            message::add('daikinRCCloud', __('L\'authentification c\'est coupée au bout de 120 secondes. Merci de relancer le deamon pour essayer à nouveau', __FILE__), null, null);
        }

        if (isset($event['rate']) && $event['rate']) {
            config::save('rate_limitMinute', $event['rate']['limitMinute'], 'daikinRCCloud');
            config::save('rate_remainingMinute', $event['rate']['remainingMinute'], 'daikinRCCloud');
            config::save('rate_limitDay', $event['rate']['limitDay'], 'daikinRCCloud');
            config::save('rate_remainingDay', $event['rate']['remainingDay'], 'daikinRCCloud');
            config::save('rate_lastupdate', date('d-m-Y H:i:s', time()), 'daikinRCCloud');
            log::add('daikinRCCloud', 'debug', __('Rate limite : ' . json_encode($event['rate']), __FILE__));
        }
    }

    private static function createEqlogic($key, $event)
    {
        $eqLogic = eqLogic::byLogicalId($key, 'daikinRCCloud');
        if (!is_object($eqLogic)) {
            $eqLogic = new eqLogic();
            $eqLogic->setEqType_name('daikinRCCloud');
            $eqLogic->setName("Daikin - " . $event['_device']['name'] ?: $key);
            $eqLogic->setLogicalId($key);
            $eqLogic->setIsEnable(1);
        }

        if ($eqLogic->getName() == $key) {
            $eqLogic->setName("Daikin - " . $event['_device']['name'] ?: $key);
            $eqLogic->setIsEnable(1);
        }

        if (isset($event['_device']['timeZone'])) $eqLogic->setConfiguration("timeZone", $event['_device']['timeZone']);
        if (isset($event['_device']['errorCode'])) $eqLogic->setConfiguration("errorCode", $event['_device']['errorCode']);
        if (isset($event['_device']['modelInfo'])) $eqLogic->setConfiguration("modelInfo", $event['_device']['modelInfo']);
        if (isset($event['_device']['serialNumber'])) $eqLogic->setConfiguration("serialNumber", $event['_device']['serialNumber']);
        if (isset($event['_device']['firmwareVersion'])) $eqLogic->setConfiguration("firmwareVersion", $event['_device']['firmwareVersion']);
        if (isset($event['_device']['wifiConnectionSSID'])) $eqLogic->setConfiguration("wifiConnectionSSID", $event['_device']['wifiConnectionSSID']);
        if (isset($event['_device']['wifiConnectionStrength'])) $eqLogic->setConfiguration("wifiConnectionStrength", $event['_device']['wifiConnectionStrength']);
        $eqLogic->save();
        return $eqLogic;
    }

    public function preInsert()
    {

    }

    /*
    * Permet de déclencher une action après modification d'une variable de configuration du plugin
    * Exemple avec la variable "param3"
    public static function postConfig_daikin_password($value) {
      // no return value
    }
    */

    /*     * **********************Getteur Setteur*************************** */

    public function postInsert()
    {

    }

    public function preUpdate()
    {

    }

    public function postUpdate()
    {

    }

    public function preSave()
    {

    }

    public function postSave()
    {

    }

    public function preRemove()
    {

    }

    public function postRemove()
    {

    }

    public function publishMqttValue($_node, $_args = array())
    {
        log::add('daikinRCCloud', 'debug', '[' . __FUNCTION__ . '] ' . 'Publication Mqtt Value' . $_node . ' ' . json_encode($_args));
        mqtt2::publish(config::byKey('prefix', 'daikinRCCloud', 'daikinToMQTT') . '/' . $_node . '/set', $_args);
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
        if ($this->getLogicalId() == 'refresh') $this->getEqLogic()->refresh();
        else {

            log::add('daikinRCCloud', 'debug', '[' . __FUNCTION__ . "] | Options : " . json_encode($_options));

            $deamon = daikinRCCloud::deamon_info();
            if ($deamon['state'] == 'ok') {
                $action = cmd::byId($this->getValue())->getLogicalId();

                switch ($this->getSubType()) {
                    case 'other':
                        if ($action . "_ON" == $this->getLogicalId()) $actionValue = TRUE;
                        else if ($action . "_OFF" == $this->getLogicalId()) $actionValue = FALSE;
                        break;
                    case 'slider':
                        $actionValue = $_options['slider'];
                        break;
                    case 'select':
                        $actionValue = $_options['select'];
                        break;
                    default:
                        return FALSE;
                }

                $logicalID = $this->getEqLogic()->getLogicalId();

                $data = array(
                    $action => $actionValue
                );

                $this->getEqLogic()->publishMqttValue($logicalID, $data);
                return true;
            }
            return false;
        }
        return false;
    }

    /*     * **********************Getteur Setteur*************************** */

}
