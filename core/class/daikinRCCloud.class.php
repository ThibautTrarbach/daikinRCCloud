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

		/*
		* Permet de définir les possibilités de personnalisation du widget (en cas d'utilisation de la fonction 'toHtml' par exemple)
		* Tableau multidimensionnel - exemple: array('custom' => true, 'custom::layout' => false)
		public static $_widgetPossibility = array();
		*/

		/*
		* Permet de crypter/décrypter automatiquement des champs de configuration du plugin
		* Exemple : "param1" & "param2" seront cryptés mais pas "param3"
		public static $_encryptConfigKey = array('param1', 'param2');
		*/

		/*     * ***********************Methode static*************************** */

		/*
		* Fonction exécutée automatiquement toutes les minutes par Jeedom
		public static function cron() {}
		*/

		/*
		* Fonction exécutée automatiquement toutes les 5 minutes par Jeedom
		public static function cron5() {}
		*/

		/*
		* Fonction exécutée automatiquement toutes les 10 minutes par Jeedom
		public static function cron10() {}
		*/

		/*
		* Fonction exécutée automatiquement toutes les 15 minutes par Jeedom
		public static function cron15() {}
		*/

		/*
		* Fonction exécutée automatiquement toutes les 30 minutes par Jeedom
		public static function cron30() {}
		*/

		/*
		* Fonction exécutée automatiquement toutes les heures par Jeedom
		public static function cronHourly() {}
		*/

		/*
		* Fonction exécutée automatiquement tous les jours par Jeedom
		public static function cronDaily() {}
		*/

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
			if (!is_dir($data_path)) {
				mkdir($data_path, 0777, true);
			}
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
			self::generateCMD();
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

			$settings['daikin']['modeproxy'] = config::byKey('daikin_modeproxy', 'daikinRCCloud',0) == 1;
			$settings['daikin']['username'] = config::byKey('daikin_username', 'daikinRCCloud',null);
			$settings['daikin']['password'] = config::byKey('daikin_password', 'daikinRCCloud',null);
			$settings['daikin']['proxyPort'] = config::byKey('daikin_proxyPort', 'daikinRCCloud',8888);
			$settings['daikin']['proxyWebPort'] = config::byKey('daikin_proxyWebPort', 'daikinRCCloud',8889);
			$settings['daikin']['communicationTimeout'] = config::byKey('daikin_communicationTimeout', 'daikinRCCloud',10000);
			$settings['daikin']['communicationRetries'] = config::byKey('daikin_communicationRetries', 'daikinRCCloud',3);


			$settings['mqtt']['host'] = $mqttInfos['ip'];
			$settings['mqtt']['port'] = $mqttInfos['port'];
			$settings['mqtt']['auth'] = true;
			$settings['mqtt']['username'] = $mqttInfos['user'];
			$settings['mqtt']['password'] = $mqttInfos['password'];
			$settings['mqtt']['connectTimeout'] = 4000;
			$settings['mqtt']['reconnectPeriod'] = 1000;
			$settings['mqtt']['topic'] = config::byKey('prefix', 'daikinRCCloud','daikinToMQTT');

			$settings['system']['logLevel'] = 'error';

			 @yaml_emit_file($file, $settings, YAML_UTF8_ENCODING, YAML_CRLN_BREAK);
		}

		/*
		* Permet de crypter/décrypter automatiquement des champs de configuration des équipements
		* Exemple avec le champ "Mot de passe" (password)
		public function decrypt() {
		  $this->setConfiguration('password', utils::decrypt($this->getConfiguration('password')));
		}
		public function encrypt() {
		  $this->setConfiguration('password', utils::encrypt($this->getConfiguration('password')));
		}
		*/

		/*
		* Permet de modifier l'affichage du widget (également utilisable par les commandes)
		public function toHtml($_version = 'dashboard') {}
		*/

		/*
		* Permet de déclencher une action avant modification d'une variable de configuration du plugin
		* Exemple avec la variable "param3"
		public static function preConfig_param3( $value ) {
		  // do some checks or modify on $value
		  return $value;
		}
		*/

		/*
		* Permet de déclencher une action après modification d'une variable de configuration du plugin
		* Exemple avec la variable "param3"
		public static function postConfig_param3($value) {
		  // no return value
		}
		*/

		/*     * **********************Getteur Setteur*************************** */

		public static function publishMqttValue($_node,$_path,$_args=array()) {
			log::add('daikinRCCloud','debug','[' . __FUNCTION__ . '] '.'Publication Mqtt Value' . $_node . ' ' . $_path . ' ' . json_encode($_args));
			mqtt2::publish(config::byKey('prefix', 'daikinRCCloud', 'daikinToMQTT').'/'.$_node.'/'.$_path.'/set',$_args);
		}

		public static function handleMqttMessage($_message) {
			log::add('daikinRCCloud','debug','[' . __FUNCTION__ . '] '.'Message Mqtt reçu');
			log::add('daikinRCCloud','debug', json_encode($_message));
			$events = $_message[config::byKey('prefix', 'daikinRCCloud', 'daikinToMQTT')];

			foreach ($events as $key => $event) {
				log::add('daikinRCCloud','debug','[' . __FUNCTION__ . '] '."ID : ".$key." | Value : ".json_encode($event));
				$eqLogic = eqLogic::byLogicalId($key, 'daikinRCCloud');
				if (!is_object($eqLogic)) {
					$eqLogic = new eqLogic();
					$eqLogic->setEqType_name('daikinRCCloud');
					$eqLogic->setName($event['device']['name'] ?: $key);
					$eqLogic->setLogicalId($key);
					$eqLogic->setIsEnable(1);
				}
				if (isset($event['device']['timeZone'])) $eqLogic->setConfiguration("timeZone", $event['device']['timeZone']);
				if (isset($event['device']['errorCode'])) $eqLogic->setConfiguration("errorCode", $event['device']['errorCode']);
				if (isset($event['device']['modelInfo'])) $eqLogic->setConfiguration("modelInfo", $event['device']['modelInfo']);
				if (isset($event['device']['serialNumber'])) $eqLogic->setConfiguration("serialNumber", $event['device']['serialNumber']);
				if (isset($event['device']['firmwareVersion'])) $eqLogic->setConfiguration("firmwareVersion", $event['device']['firmwareVersion']);
				if (isset($event['device']['wifiConnectionSSID'])) $eqLogic->setConfiguration("wifiConnectionSSID", $event['device']['wifiConnectionSSID']);
				if (isset($event['device']['wifiConnectionStrength'])) $eqLogic->setConfiguration("wifiConnectionStrength", $event['device']['wifiConnectionStrength']);
				$eqLogic->save();

				$cmds = $eqLogic->getCmd('info');
				foreach ($cmds as $cmd) {
					$logicalID = $cmd->getLogicalId();
					if (!isset($event[$logicalID])) continue;
					$value = $event[$logicalID];

					if (is_bool($value)) $cmd->event($value ? 1:0);
					else $cmd->event(jeedom::evaluateExpression($value));
				}
			}
		}

		public function generateCMD() {
			$model = $this->getConfiguration('modelInfo', FALSE);
			if ($model === FALSE) return;

			$path = __DIR__ . "/../config/".$model.".json";
			log::add('daikinRCCloud','debug','[' . __FUNCTION__ . '] '.$path);
			if (!file_exists($path)) return;
			$data = file_get_contents($path);
			if (!$data) return;
			$cmdDatas = json_decode($data, TRUE);

			foreach ($cmdDatas as $key => $cmdData) {
				$cmd = $this->getCmd($cmdData['type'], $key);
				if (!is_object($cmd)) {
					$cmd = new cmd();
					$cmd->setEqLogic_id($this->getId());
					$cmd->setLogicalId($key);
					$cmd->setName($cmdData['name']);
					$cmd->setIsHistorized($cmdData['isHistorized']? 1:0);
					$cmd->setIsVisible($cmdData['isVisible']? 1:0);
					if (isset($cmdData['generic_type'])) $cmd->setGeneric_type($cmdData['generic_type']);
				}
				$cmd->setType($cmdData['type']);
				$cmd->setSubType($cmdData['subType']);
				if (isset($cmdData['unite'])) $cmd->setUnite($cmdData['unite']);
				if (isset($cmdData['value'])) $cmd->setValue($cmdData['value']);
				if (isset($cmdData['minValue'])) $cmd->setConfiguration("minValue",$cmdData['minValue']);
				if (isset($cmdData['maxValue'])) $cmd->setConfiguration("maxValue", $cmdData['maxValue']);
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
		}

		/*     * **********************Getteur Setteur*************************** */

	}
