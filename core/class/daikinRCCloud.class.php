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

	/* * ***************************Includes********************************* */
	require_once __DIR__ . '/../../../../core/php/core.inc.php';

	class daikinRCCloud extends eqLogic
	{
		/*     * *************************Attributs****************************** */

		/*     * ***********************Methode static*************************** */


		/*     * *********************Méthodes d'instance************************* */

		public static function dependancy_info()
		{
			$return = array();
			$return['progress_file'] = jeedom::getTmpFolder(__CLASS__) . '/dependance';
			$return['state'] = 'ok';
			if (config::byKey('lastDependancyInstallTime', __CLASS__) == '') $return['state'] = 'nok';
			elseif (!file_exists(__DIR__ . '/../../resources/daikintomqtt/node_modules')) $return['state'] = 'nok';
			return $return;
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

		public static function deamon_start($_debug = false) {
			log::add('daikinRCCloud','debug','[' . __FUNCTION__ . '] '.'Inscription au plugin mqtt2');
			self::deamon_stop();
			mqtt2::addPluginTopic('daikinRCCloud', config::byKey('prefix', 'daikinRCCloud','daikinToMQTT'));
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
			log::add('daikinRCCloud', 'info','[' . __FUNCTION__ . '] '. 'Lancement démon Daikin : ' . $cmd);
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
			config::save('lastStart',time(),'zwavejs');
			message::removeAll('daikinRCCloud', 'unableStartDeamon');
			log::add('daikinRCCloud', 'info', 'Démon daikinRCCloud lancé');
			return true;
		}

		public static function deamon_stop() {
			log::add('daikinRCCloud','debug','[' . __FUNCTION__ . '] '.'Stop démon');
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
				system::kill('daikinToMQTT.js',true);
				$i =0;
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


		public function preInsert()
		{

		}

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

		public static function configureSettings($_path) {
			$file = $_path .'/settings.yml';
			$settings = array();
			if (file_exists($file)) {
				unlink($file);
			}
			$settings['system'] = array();
			$settings['daikin'] = array();
			$settings['mqtt'] = array();

			$mqttInfos = mqtt2::getFormatedInfos();
			log::add('daikinRCCloud','debug','[' . __FUNCTION__ . '] '.'Informations reçues de mqtt2 : ' . json_encode($mqttInfos));

			$settings['daikin']['clientID'] = config::byKey('daikin_clientID', 'daikinRCCloud',null);
			$settings['daikin']['clientSecret'] = config::byKey('daikin_clientSecret', 'daikinRCCloud',null);
			$settings['daikin']['clientURL'] = network::getNetworkAccess('internal');
			$settings['daikin']['clientPort'] = config::byKey('daikin_clientPort', 'daikinRCCloud',8765);

			$settings['mqtt']['host'] = $mqttInfos['ip'];
			$settings['mqtt']['port'] = $mqttInfos['port'];
			$settings['mqtt']['auth'] = true;
			$settings['mqtt']['username'] = $mqttInfos['user'];
			$settings['mqtt']['password'] = $mqttInfos['password'];
			$settings['mqtt']['connectTimeout'] = 4000;
			$settings['mqtt']['reconnectPeriod'] = 1000;
			$settings['mqtt']['topic'] = config::byKey('prefix', 'daikinRCCloud','daikinToMQTT');

			$settings['system']['logLevel'] = 'info';
			$settings['system']['jeedom'] = TRUE;

			@yaml_emit_file($file, $settings, YAML_UTF8_ENCODING, YAML_CRLN_BREAK);
		}

		/*
		* Permet de crypter/décrypter automatiquement des champs de configuration des équipements
		* Exemple avec le champ "Mot de passe" (password)
		public function decrypt() {
		  $this->setConfiguration('daikin_password', utils::decrypt($this->getConfiguration('daikin_password')));
		}

		public function encrypt() {
		  $this->setConfiguration('daikin_password', utils::encrypt($this->getConfiguration('daikin_password')));
		} */


		/*
		* Permet de modifier l'affichage du widget (également utilisable par les commandes)
		public function toHtml($_version = 'dashboard') {}
		*/

		/*
		* Permet de déclencher une action avant modification d'une variable de configuration du plugin
		*/
		public static function preConfig_daikin_password( $value ) {
			return  utils::encrypt($value);
		}

		/*
		* Permet de déclencher une action après modification d'une variable de configuration du plugin
		* Exemple avec la variable "param3"
		public static function postConfig_daikin_password($value) {
		  // no return value
		}
		*/

		/*     * **********************Getteur Setteur*************************** */

		public function publishMqttValue($_node,$_args=array()) {
			log::add('daikinRCCloud','debug','[' . __FUNCTION__ . '] '.'Publication Mqtt Value' . $_node . ' ' . json_encode($_args));
			mqtt2::publish(config::byKey('prefix', 'daikinRCCloud', 'daikinToMQTT').'/'.$_node.'/set',$_args);
		}

		public static function handleMqttMessage($_message) {
			log::add('daikinRCCloud','debug','[' . __FUNCTION__ . '] '.'Message Mqtt reçu');
			log::add('daikinRCCloud','debug', json_encode($_message));
			$events = $_message[config::byKey('prefix', 'daikinRCCloud', 'daikinToMQTT')];

			foreach ($events as $key => $event) {
				if ($key == 'system') {
					self::handleSystemEvent($event);
					continue;
				}

				log::add('daikinRCCloud','debug','[' . __FUNCTION__ . '] '."ID : ".$key." | Value : ".json_encode($event));

				$eqLogic = eqLogic::byLogicalId($key, 'daikinRCCloud');
				if (!is_object($eqLogic) || $eqLogic->getName() == $key) $eqLogic = self::createEqlogic($key, $event);

				$cmds = $eqLogic->getCmd('info');
				foreach ($cmds as $cmd) {
					$logicalID = $cmd->getLogicalId();
					if (!isset($event[$logicalID])) continue;
					$value = jeedom::evaluateExpression($event[$logicalID]);
					log::add('daikinRCCloud','debug','[' . __FUNCTION__ . '] '."Data Debug => logicalID : ".$logicalID." | Value : ".$value);
					$cmd->event($value);
				}
			}
		}

		private static function handleSystemEvent($event) {
			if (isset($event['jeedom'])) self::handleSystemJeedomEvent($event['jeedom']);
			if (isset($event['bridge'])) self::handleSystemBridgeEvent($event['bridge']);
		}

		private static function handleSystemJeedomEvent($event) {
			foreach ($event as $uid => $module) {
				$eqLogic = eqLogic::byLogicalId($uid, 'daikinRCCloud');
				if (!is_object($eqLogic))  {
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

		private static function handleSystemBridgeEvent($event) {
			$daikinStatus = $event['daikin'];
			$mqttStatus = $event['mqtt'];
			$status = $event['status'];
			if (isset($event['error'])) {
				$error = $event['error'];
				if ($error !== "No Error") {
					log::add('daikinRCCloud', 'error', '[DAEMON] ' . "Erreur : " . $error);
					plugin::byId('daikinRCCloud')->deamon_changeAutoMode(0);
				}
			}
		}

		private static function createEqlogic($key, $event) {
			$eqLogic = eqLogic::byLogicalId($key, 'daikinRCCloud');
			if (!is_object($eqLogic)) {
				$eqLogic = new eqLogic();
				$eqLogic->setEqType_name('daikinRCCloud');
				$eqLogic->setName("Daikin - ". $event['_device']['name'] ?: $key);
				$eqLogic->setLogicalId($key);
				$eqLogic->setIsEnable(1);
			}

			if ($eqLogic->getName() == $key) {
				$eqLogic->setName("Daikin - ". $event['_device']['name'] ?: $key);
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

		public static function generateCMD($eqLogics, $data) {
			//$cmdDatas = json_decode($data, TRUE);

			foreach ($data as $cmdData) {
				$cmd = $eqLogics->getCmd($cmdData['type'], $cmdData['logicalID']);
				if (!is_object($cmd)) {
					$cmd = new cmd();
					$cmd->setEqLogic_id($eqLogics->getId());
					$cmd->setLogicalId($cmdData['logicalID']);
					$cmd->setName($cmdData['name']);
					if (isset($cmdData['isHistorized'])) $cmd->setIsHistorized($cmdData['isHistorized']? 1:0);
					if (isset($cmdData['isVisible'])) $cmd->setIsVisible($cmdData['isVisible']? 1:0);
					if (isset($cmdData['generic_type'])) $cmd->setGeneric_type($cmdData['generic_type']);
					if (isset($cmdData['template'])) $cmd->setTemplate("dashboard", $cmdData['template']);
				}
				$cmd->setType($cmdData['type']);
				$cmd->setSubType($cmdData['subType']);
				if (isset($cmdData['unite'])) $cmd->setUnite($cmdData['unite']);
				if (isset($cmdData['value'])) $cmd->setValue($eqLogics->getCmd('info', $cmdData['value'])->getId());
				if (isset($cmdData['minValue'])) $cmd->setConfiguration("minValue",$cmdData['minValue']);
				if (isset($cmdData['maxValue'])) $cmd->setConfiguration("maxValue", $cmdData['maxValue']);
				if (isset($cmdData['listValue'])) $cmd->setConfiguration("listValue", $cmdData['listValue']);

				$cmd->save();
			}
		}

		public static function isRunning(): bool
		{
			if (!empty(system::ps('daikinToMQTT.js'))) {
				return true;
			}
			return false;
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
							if ($action."_ON" == $this->getLogicalId()) $actionValue = TRUE;
							else if ($action."_OFF" == $this->getLogicalId()) $actionValue = FALSE;
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
						$action=>$actionValue
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
