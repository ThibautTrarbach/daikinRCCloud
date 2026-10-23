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

try {
	require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
	include_file('core', 'authentification', 'php');

	if (!isConnect()) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}

	ajax::init();

	if (init('action') == 'getInfoForMobilePanel') {
		$return = array('eqLogics' => array());
		foreach (eqLogic::byType('daikinRCCloud') as $eq) {
			if ($eq->getLogicalId() === daikinRCCloud::INSTANCE_ID) {
				continue;
			}
			if (!$eq->getIsEnable() || !$eq->getIsVisible()) {
				continue;
			}
			if ($eq->getConfiguration('usePluginWidget', '0') != '1') {
				continue;
			}
			$return['eqLogics'][] = array('html' => $eq->toHtml('panelM'));
		}
		$return['lastUpdateTxt'] = __('Dernière mise à jour', __FILE__);
		$return['allUnitTxt'] = __('Toutes les unités', __FILE__);
		ajax::success($return);
	}

	if (!isConnect('admin')) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}

	throw new Exception(__('Aucune méthode correspondante à', __FILE__) . ' : ' . init('action'));
} catch (Exception $e) {
	ajax::error(displayException($e), $e->getCode());
}
