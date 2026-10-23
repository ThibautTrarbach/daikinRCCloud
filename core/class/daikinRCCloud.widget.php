<?php
/**
 * Widget dashboard / mobile / panel for daikinRCCloud.
 *
 * UI inspired by the DaikinOnlineCtrl plugin (AGPL) by Masterfion & A.Barrau.
 * Data layer rewritten for ONECTA MQTT logicalIds.
 */

trait daikinRCCloudWidget
{
	public static $_widgetPossibility = array(
		'custom' => array(
			'visibility' => array('dashboard' => true, 'plan' => true, 'view' => true, 'mobile' => true),
			'displayName' => array('dashboard' => true, 'plan' => true, 'view' => true, 'mobile' => true),
			'displayObjectName' => array('dashboard' => true, 'plan' => true, 'view' => true, 'mobile' => true),
			'background-color' => array('dashboard' => true, 'plan' => true, 'view' => true, 'mobile' => true),
			'background-opacity' => array('dashboard' => true, 'plan' => true, 'view' => true, 'mobile' => true),
			'text-color' => array('dashboard' => true, 'plan' => true, 'view' => true, 'mobile' => true),
			'border' => array('dashboard' => true, 'plan' => true, 'view' => true, 'mobile' => true),
			'border-radius' => array('dashboard' => true, 'plan' => true, 'view' => true, 'mobile' => true),
		),
	);

	/**
	 * @param string $_version
	 * @return string
	 */
	public function toHtml($_version = 'dashboard')
	{
		$isPanelM = ($_version === 'panelM');
		if ($this->getLogicalId() === self::INSTANCE_ID) {
			return parent::toHtml($isPanelM ? 'mview' : $_version);
		}
		if ($this->getConfiguration('usePluginWidget', '0') != '1') {
			return parent::toHtml($isPanelM ? 'mview' : $_version);
		}

		$replace = $this->preToHtml($_version, array(), true);
		if (!is_array($replace)) {
			return $replace;
		}

		$replace = array_merge($replace, $this->generateWidgetData());
		$version = jeedom::versionAlias($_version);
		$templateVersion = $isPanelM ? 'panelM' : $version;
		$html = $this->postToHtml($_version, template_replace($replace, getTemplate('core', $templateVersion, 'daikinRCCloud', 'daikinRCCloud')));
		return $html;
	}

	/**
	 * Detect HVAC zones from _onOffMode* info commands.
	 *
	 * @return array[] each: key, suffix, label, kind (climate|tank)
	 */
	public function resolveZones()
	{
		$zones = array();
		foreach ($this->getCmd('info') as $cmd) {
			$logicalId = $cmd->getLogicalId();
			if (!preg_match('/^_onOffMode(.*)$/', $logicalId, $m)) {
				continue;
			}
			$suffix = $m[1];
			$kind = 'climate';
			if ($suffix === 'Tank' || $suffix === '2') {
				$modeCmd = $this->getCmd('info', '_operationMode' . $suffix);
				$fanCmd = $this->getCmd('info', '_fanCurrentMode' . $suffix);
				if ($suffix === 'Tank' || (!is_object($fanCmd) && !is_object($modeCmd))) {
					$kind = 'tank';
				}
				if ($suffix === 'Tank') {
					$kind = 'tank';
				}
			}
			$label = $this->zoneLabelFromSuffix($suffix, $kind);
			$zones[] = array(
				'key' => ($suffix === '') ? 'default' : $suffix,
				'suffix' => $suffix,
				'label' => $label,
				'kind' => $kind,
			);
		}
		usort($zones, function ($a, $b) {
			$order = array('' => 0, 'Main' => 1, '1' => 2, '2' => 3, 'Tank' => 4);
			$oa = isset($order[$a['suffix']]) ? $order[$a['suffix']] : 10;
			$ob = isset($order[$b['suffix']]) ? $order[$b['suffix']] : 10;
			return $oa - $ob;
		});
		return $zones;
	}

	/**
	 * @param string $suffix
	 * @param string $kind
	 * @return string
	 */
	protected function zoneLabelFromSuffix($suffix, $kind)
	{
		if ($suffix === '' || $suffix === 'Main') {
			return ($kind === 'tank') ? __('Ballon ECS', __FILE__) : __('Climatisation', __FILE__);
		}
		if ($suffix === 'Tank') {
			return __('Ballon ECS', __FILE__);
		}
		if ($suffix === '1') {
			return __('Zone 1', __FILE__);
		}
		if ($suffix === '2') {
			return ($kind === 'tank') ? __('Ballon ECS', __FILE__) : __('Zone 2', __FILE__);
		}
		return $suffix;
	}

	/**
	 * Build template placeholders for the active (first) zone + zone list JSON.
	 *
	 * @return array
	 */
	public function generateWidgetData()
	{
		$zones = $this->resolveZones();
		$active = (count($zones) > 0) ? $zones[0] : array(
			'key' => 'default',
			'suffix' => '',
			'label' => __('Climatisation', __FILE__),
			'kind' => 'climate',
		);

		$data = $this->buildZoneWidgetData($active);
		$data['#zones-json#'] = json_encode($zones);
		$data['#zones-count#'] = (string) count($zones);
		$data['#active-zone-key#'] = $active['key'];
		$data['#active-zone-kind#'] = $active['kind'];
		$data['#active-zone-label#'] = $active['label'];
		$data['#DRClastUpdate#'] = date('Y-m-d H:i:s');

		$allZonesPayload = array();
		foreach ($zones as $zone) {
			$allZonesPayload[$zone['key']] = $this->buildZoneWidgetPayload($zone);
		}
		$data['#zones-data-json#'] = json_encode($allZonesPayload);

		return $data;
	}

	/**
	 * Flat placeholders for template (active zone).
	 *
	 * @param array $zone
	 * @return array
	 */
	protected function buildZoneWidgetData(array $zone)
	{
		$payload = $this->buildZoneWidgetPayload($zone);
		$replace = array(
			'#txt_indoor#' => __('Intérieur', __FILE__),
			'#txt_outdoor#' => __('Extérieur', __FILE__),
			'#txt_indoor_min#' => __('Int.', __FILE__),
			'#txt_outdoor_min#' => __('Ext.', __FILE__),
			'#txt_showLinkToPanelMobil#' => __('Afficher Panel', __FILE__),
			'#showLinkToPanelMobil#' => $this->getConfiguration('showLinkToPanelMobil', '1'),
			'#temp-step#' => '0.5',
			'#power-error#' => __('État marche/arrêt indisponible', __FILE__),
			'#network-error#' => '',
			'#valeur-off#' => 'off',
			'#pow-v#' => $payload['pow'],
			'#pow-off-id#' => $payload['powOffId'],
			'#pow-on-id#' => $payload['powOnId'],
			'#mode-v#' => $payload['mode'],
			'#mode-select-id#' => $payload['modeSelectId'],
			'#stemp-v#' => $payload['stemp'],
			'#stemp-slider-id#' => $payload['stempSliderId'],
			'#stemp-txt#' => $payload['stempTxt'],
			'#fan-mode-v#' => $payload['fanMode'],
			'#fan-mode-select-id#' => $payload['fanModeSelectId'],
			'#fan-fixed-v#' => $payload['fanFixed'],
			'#fan-fixed-slider-id#' => $payload['fanFixedSliderId'],
			'#fan-h-v#' => $payload['fanH'],
			'#fan-h-select-id#' => $payload['fanHSelectId'],
			'#fan-v-v#' => $payload['fanV'],
			'#fan-v-select-id#' => $payload['fanVSelectId'],
			'#powerful-v#' => $payload['powerful'],
			'#powerful-off-id#' => $payload['powerfulOffId'],
			'#powerful-on-id#' => $payload['powerfulOnId'],
			'#econo-v#' => $payload['econo'],
			'#econo-off-id#' => $payload['econoOffId'],
			'#econo-on-id#' => $payload['econoOnId'],
			'#streamer-v#' => $payload['streamer'],
			'#streamer-off-id#' => $payload['streamerOffId'],
			'#streamer-on-id#' => $payload['streamerOnId'],
			'#refresh-id#' => $payload['refreshId'],
			'#refresh-txt#' => __('Rafraîchir', __FILE__),
			'#mode-cmd-list#' => json_encode($payload['modeList']),
			'#frate-cmd-list#' => json_encode($payload['fanRateList']),
			'#fdir-cmd-list#' => json_encode($payload['fanDirList']),
			'#extra-cmd-list#' => json_encode($payload['extraList']),
			'#sensor-info-list#' => json_encode($payload['sensors']),
			'#onmobil-cmd-list#' => json_encode($payload['onMobileList']),
		);
		return $replace;
	}

	/**
	 * Structured zone payload for JS zone switching.
	 *
	 * @param array $zone
	 * @return array
	 */
	protected function buildZoneWidgetPayload(array $zone)
	{
		$suffix = $zone['suffix'];
		$kind = $zone['kind'];

		$powInfo = $this->cmdInfo('_onOffMode' . $suffix);
		$powVal = $this->cmdBoolValue($powInfo);
		$powOn = $this->cmdAction('_onOffMode' . $suffix . '_ON');
		$powOff = $this->cmdAction('_onOffMode' . $suffix . '_OFF');

		$modeInfo = $this->cmdInfo('_operationMode' . $suffix);
		$modeVal = is_object($modeInfo) ? (string) $modeInfo->execCmd() : '';
		$modeSelect = $this->cmdAction('_operationMode' . $suffix . '_Select');
		$modeList = $this->buildSelectOptionList($modeSelect, $modeInfo, 'mode');

		$stempInfo = $this->findFirstInfo(array(
			'_temperatureControl' . $suffix,
			'_temperatureControlWater' . $suffix,
			'_temperatureControlWaterOffset' . $suffix,
		));
		$stempVal = is_object($stempInfo) ? $stempInfo->execCmd() : '';
		$stempSlider = is_object($stempInfo) ? $this->cmdAction($stempInfo->getLogicalId() . '_Slider') : null;
		$stempStep = '0.5';
		if (is_object($stempInfo)) {
			$min = $stempInfo->getConfiguration('minValue');
			$max = $stempInfo->getConfiguration('maxValue');
			if ($min !== '' && $max !== '' && floatval($max) - floatval($min) <= 15) {
				$stempStep = '1';
			}
		}

		$fanModeInfo = $this->cmdInfo('_fanCurrentMode' . $suffix);
		if (!is_object($fanModeInfo) && $suffix === '') {
			$fanModeInfo = $this->cmdInfo('_fanCurrentMode');
		}
		$fanModeSelect = is_object($fanModeInfo) ? $this->cmdAction($fanModeInfo->getLogicalId() . '_Select') : null;
		$fanModeList = ($kind === 'climate') ? $this->buildSelectOptionList($fanModeSelect, $fanModeInfo, 'frate') : array();

		$fanFixedInfo = $this->cmdInfo('_fanFixed' . $suffix);
		if (!is_object($fanFixedInfo) && $suffix === '') {
			$fanFixedInfo = $this->cmdInfo('_fanFixed');
		}
		$fanFixedSlider = is_object($fanFixedInfo) ? $this->cmdAction($fanFixedInfo->getLogicalId() . '_Slider') : null;

		$fanHInfo = $this->cmdInfo('_fanHorizontal' . $suffix);
		if (!is_object($fanHInfo) && $suffix === '') {
			$fanHInfo = $this->cmdInfo('_fanHorizontal');
		}
		$fanVInfo = $this->cmdInfo('_fanVertical' . $suffix);
		if (!is_object($fanVInfo) && $suffix === '') {
			$fanVInfo = $this->cmdInfo('_fanVertical');
		}
		$fanHSelect = is_object($fanHInfo) ? $this->cmdAction($fanHInfo->getLogicalId() . '_Select') : null;
		$fanVSelect = is_object($fanVInfo) ? $this->cmdAction($fanVInfo->getLogicalId() . '_Select') : null;
		$fanDirList = array();
		if ($kind === 'climate') {
			$fanDirList = array_merge(
				$this->buildSelectOptionList($fanHSelect, $fanHInfo, 'fdir'),
				$this->buildSelectOptionList($fanVSelect, $fanVInfo, 'fdir')
			);
		}

		$powerfulInfo = $this->findFirstInfo(array(
			'_powerfulMode' . $suffix,
			'_isPowerfulModeActive' . $suffix,
			'_powerfulMode',
			'_isPowerfulModeActive',
		));
		$powerfulVal = $this->cmdBoolValue($powerfulInfo);
		if (is_object($powerfulInfo) && strpos($powerfulInfo->getLogicalId(), '_isPowerfulModeActive') === 0) {
			$powerfulOn = $this->cmdAction('_powerfulMode' . $suffix . '_ON');
			if (!is_object($powerfulOn)) {
				$powerfulOn = $this->cmdAction('_powerfulMode_ON');
			}
			$powerfulOff = $this->cmdAction('_powerfulMode' . $suffix . '_OFF');
			if (!is_object($powerfulOff)) {
				$powerfulOff = $this->cmdAction('_powerfulMode_OFF');
			}
		} else {
			$base = is_object($powerfulInfo) ? $powerfulInfo->getLogicalId() : ('_powerfulMode' . $suffix);
			$powerfulOn = $this->cmdAction($base . '_ON');
			$powerfulOff = $this->cmdAction($base . '_OFF');
		}

		$econoInfo = $this->cmdInfo('_econoMode' . $suffix);
		if (!is_object($econoInfo)) {
			$econoInfo = $this->cmdInfo('_econoMode');
		}
		$streamerInfo = $this->cmdInfo('_streamerMode' . $suffix);
		if (!is_object($streamerInfo)) {
			$streamerInfo = $this->cmdInfo('_streamerMode');
		}

		$extraList = array();
		$onMobileList = array();
		if (is_object($powerfulOn) && is_object($powerfulOff)) {
			$extraList[] = array(
				'id' => ($powerfulVal === '1') ? $powerfulOff->getId() : $powerfulOn->getId(),
				'txt' => __('Powerful', __FILE__),
				'icone' => '<i class="loisir loisir-runner5"></i>',
				'v' => $powerfulVal,
				't' => 'powerful',
				'exec' => 'other',
				'onId' => $powerfulOn->getId(),
				'offId' => $powerfulOff->getId(),
			);
			$onMobileList[] = end($extraList);
		}
		if (is_object($econoInfo)) {
			$econoOn = $this->cmdAction($econoInfo->getLogicalId() . '_ON');
			$econoOff = $this->cmdAction($econoInfo->getLogicalId() . '_OFF');
			if (is_object($econoOn) && is_object($econoOff)) {
				$ev = $this->cmdBoolValue($econoInfo);
				$extraList[] = array(
					'id' => ($ev === '1') ? $econoOff->getId() : $econoOn->getId(),
					'txt' => __('Eco', __FILE__),
					'icone' => '<i class="fas fa-leaf"></i>',
					'v' => $ev,
					't' => 'econo',
					'exec' => 'other',
					'onId' => $econoOn->getId(),
					'offId' => $econoOff->getId(),
				);
				$onMobileList[] = end($extraList);
			}
		} else {
			$econoOn = null;
			$econoOff = null;
		}
		if (is_object($streamerInfo)) {
			$streamerOn = $this->cmdAction($streamerInfo->getLogicalId() . '_ON');
			$streamerOff = $this->cmdAction($streamerInfo->getLogicalId() . '_OFF');
			if (is_object($streamerOn) && is_object($streamerOff)) {
				$sv = $this->cmdBoolValue($streamerInfo);
				$extraList[] = array(
					'id' => ($sv === '1') ? $streamerOff->getId() : $streamerOn->getId(),
					'txt' => __('Streamer', __FILE__),
					'icone' => '<i class="fas fa-wind"></i>',
					'v' => $sv,
					't' => 'streamer',
					'exec' => 'other',
					'onId' => $streamerOn->getId(),
					'offId' => $streamerOff->getId(),
				);
				$onMobileList[] = end($extraList);
			}
		} else {
			$streamerOn = null;
			$streamerOff = null;
		}

		foreach ($modeList as $m) {
			$onMobileList[] = $m;
		}

		$roomTemp = $this->findFirstInfo(array('_roomTemperature' . $suffix, '_roomTemperature'));
		$outdoorTemp = $this->findFirstInfo(array('_outdoorTemperature' . $suffix, '_outdoorTemperature', '_outdoorTemperatureMain', '_outdoorTemperature1'));
		$humidity = $this->findFirstInfo(array('_roomHumidity' . $suffix, '_roomHumidity'));
		$heatConso = $this->findFirstInfo(array('_heatingConsumptionD' . $suffix, '_heatingConsumptionD'));
		$coolConso = $this->findFirstInfo(array('_coolingConsumptionD' . $suffix, '_coolingConsumptionD'));

		$sensors = array();
		$sensors['htemp'] = $this->sensorEntry('htemp', $roomTemp);
		$sensors['otemp'] = $this->sensorEntry('otemp', $outdoorTemp);
		$sensors['hhum'] = $this->sensorEntry('hhum', $humidity);
		$sensors['curr_day_heat'] = $this->sensorEntry('curr_day_heat', $heatConso);
		$sensors['curr_day_cool'] = $this->sensorEntry('curr_day_cool', $coolConso);

		$refresh = $this->getCmd('action', 'refresh');

		return array(
			'kind' => $kind,
			'label' => $zone['label'],
			'pow' => $powVal,
			'powOffId' => is_object($powOff) ? (string) $powOff->getId() : '',
			'powOnId' => is_object($powOn) ? (string) $powOn->getId() : '',
			'mode' => $modeVal,
			'modeSelectId' => is_object($modeSelect) ? (string) $modeSelect->getId() : '',
			'stemp' => (string) $stempVal,
			'stempSliderId' => is_object($stempSlider) ? (string) $stempSlider->getId() : '',
			'stempTxt' => is_object($stempInfo) ? $stempInfo->getName() : '',
			'stempStep' => $stempStep,
			'fanMode' => is_object($fanModeInfo) ? (string) $fanModeInfo->execCmd() : '',
			'fanModeSelectId' => is_object($fanModeSelect) ? (string) $fanModeSelect->getId() : '',
			'fanFixed' => is_object($fanFixedInfo) ? (string) $fanFixedInfo->execCmd() : '',
			'fanFixedSliderId' => is_object($fanFixedSlider) ? (string) $fanFixedSlider->getId() : '',
			'fanH' => is_object($fanHInfo) ? (string) $fanHInfo->execCmd() : '',
			'fanHSelectId' => is_object($fanHSelect) ? (string) $fanHSelect->getId() : '',
			'fanV' => is_object($fanVInfo) ? (string) $fanVInfo->execCmd() : '',
			'fanVSelectId' => is_object($fanVSelect) ? (string) $fanVSelect->getId() : '',
			'powerful' => $powerfulVal,
			'powerfulOffId' => is_object($powerfulOff) ? (string) $powerfulOff->getId() : '',
			'powerfulOnId' => is_object($powerfulOn) ? (string) $powerfulOn->getId() : '',
			'econo' => is_object($econoInfo) ? $this->cmdBoolValue($econoInfo) : '-1',
			'econoOffId' => is_object($econoOff) ? (string) $econoOff->getId() : '',
			'econoOnId' => is_object($econoOn) ? (string) $econoOn->getId() : '',
			'streamer' => is_object($streamerInfo) ? $this->cmdBoolValue($streamerInfo) : '-1',
			'streamerOffId' => is_object($streamerOff) ? (string) $streamerOff->getId() : '',
			'streamerOnId' => is_object($streamerOn) ? (string) $streamerOn->getId() : '',
			'refreshId' => is_object($refresh) ? (string) $refresh->getId() : '',
			'modeList' => $modeList,
			'fanRateList' => $fanModeList,
			'fanDirList' => $fanDirList,
			'extraList' => $extraList,
			'sensors' => $sensors,
			'onMobileList' => $onMobileList,
		);
	}

	/**
	 * @param string $name
	 * @param cmd|null $cmd
	 * @return array
	 */
	protected function sensorEntry($name, $cmd)
	{
		if (!is_object($cmd)) {
			return array('n' => $name, 'id' => '', 'txt' => '', 'v' => false, 'kcmd' => '0', 'h' => '0', 's' => '0');
		}
		$val = $cmd->execCmd();
		if ($val === '' || $val === null) {
			$val = false;
		} elseif (is_numeric($val)) {
			$val = round(floatval($val), 1) . $cmd->getUnite();
		}
		return array(
			'n' => $name,
			'id' => $cmd->getId(),
			'txt' => $cmd->getName(),
			'v' => $val,
			'kcmd' => '1',
			'h' => $cmd->getIsHistorized() ? '1' : '0',
			's' => '1',
		);
	}

	/**
	 * @param cmd|null $selectCmd
	 * @param cmd|null $infoCmd
	 * @param string $type
	 * @return array
	 */
	protected function buildSelectOptionList($selectCmd, $infoCmd, $type)
	{
		$list = array();
		if (!is_object($selectCmd) || !is_object($infoCmd)) {
			return $list;
		}
		$listValue = $selectCmd->getConfiguration('listValue');
		if ($listValue === null || $listValue === '') {
			$listValue = $infoCmd->getConfiguration('listValue');
		}
		if (!is_string($listValue) || $listValue === '') {
			return $list;
		}
		foreach (explode(';', $listValue) as $pair) {
			$parts = explode('|', $pair);
			$value = isset($parts[0]) ? $parts[0] : '';
			$label = isset($parts[1]) ? $parts[1] : $value;
			if ($value === '') {
				continue;
			}
			$list[] = array(
				'id' => $selectCmd->getId(),
				'txt' => $label,
				'icone' => $this->iconForValue($type, $value),
				'v' => $value,
				't' => $type,
				'exec' => 'select',
			);
		}
		return $list;
	}

	/**
	 * @param string $type
	 * @param string $value
	 * @return string
	 */
	protected function iconForValue($type, $value)
	{
		$value = strtolower((string) $value);
		if ($type === 'mode') {
			$map = array(
				'cooling' => '<i class="jeedom jeedom-thermo-froid"></i>',
				'heating' => '<i class="jeedom jeedom-thermo-chaud"></i>',
				'auto' => '<i class="fas fa-magic"></i>',
				'dry' => '<i class="fas fa-tint"></i>',
				'fanonly' => '<i class="fas fa-fan"></i>',
				'fan_only' => '<i class="fas fa-fan"></i>',
				'humidification' => '<i class="fas fa-cloud"></i>',
			);
			return isset($map[$value]) ? $map[$value] : '<i class="fas fa-circle"></i>';
		}
		if ($type === 'frate') {
			if ($value === 'auto') {
				return '<i class="fas fa-magic"></i>';
			}
			if ($value === 'quiet') {
				return '<i class="fas fa-volume-mute"></i>';
			}
			return '<i class="fas fa-wind"></i><span style="font-size:10px;">' . htmlspecialchars($value) . '</span>';
		}
		if ($type === 'fdir') {
			return '<i class="fas fa-arrows-alt"></i><span style="font-size:9px;">' . htmlspecialchars($value) . '</span>';
		}
		return '<i class="fas fa-circle"></i>';
	}

	/**
	 * @param string $logicalId
	 * @return cmd|null
	 */
	protected function cmdInfo($logicalId)
	{
		$cmd = $this->getCmd('info', $logicalId);
		return is_object($cmd) ? $cmd : null;
	}

	/**
	 * @param string $logicalId
	 * @return cmd|null
	 */
	protected function cmdAction($logicalId)
	{
		$cmd = $this->getCmd('action', $logicalId);
		return is_object($cmd) ? $cmd : null;
	}

	/**
	 * @param array $logicalIds
	 * @return cmd|null
	 */
	protected function findFirstInfo(array $logicalIds)
	{
		foreach ($logicalIds as $id) {
			$cmd = $this->cmdInfo($id);
			if (is_object($cmd)) {
				return $cmd;
			}
		}
		return null;
	}

	/**
	 * @param cmd|null $cmd
	 * @return string 1|0|-1
	 */
	protected function cmdBoolValue($cmd)
	{
		if (!is_object($cmd)) {
			return '-1';
		}
		$val = $cmd->execCmd();
		if ($val === '' || $val === null) {
			return '-1';
		}
		if ($val === true || $val === 1 || $val === '1' || $val === 'true' || $val === 'on') {
			return '1';
		}
		return '0';
	}
}
