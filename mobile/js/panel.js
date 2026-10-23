/* This file is part of Jeedom / daikinRCCloud (AGPL).
 * Panel UI inspired by DaikinOnlineCtrl (Masterfion & A.Barrau).
 */
var _showUnit = 0;

function initDaikinrccloudPanel(_eqLogId) {
	_eqLogId = (typeof _eqLogId !== 'undefined') ? parseInt(_eqLogId, 10) : 0;
	displayDRCList(_eqLogId);
}

function displayMenuPanel(_eqLogic) {
	return '<li><a href="#" class="link" data-page="panel" data-plugin="daikinRCCloud" data-title="' + _eqLogic._name + '" data-option="' + _eqLogic._id + '"><span>' + _eqLogic._objIcon + '</span> ' + _eqLogic._name + ' (' + _eqLogic._objName + ')</a></li>';
}

function displayDRCList(_eqLogId) {
	_eqLogId = (typeof _eqLogId !== 'undefined') ? parseInt(_eqLogId, 10) : 0;
	_showUnit = 0;
	$.showLoading();
	$.ajax({
		type: 'POST',
		url: 'plugins/daikinRCCloud/core/ajax/daikinRCCloud.ajax.php',
		data: { action: 'getInfoForMobilePanel' },
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) {
			if (data.state != 'ok') {
				$('#div_alert').showAlert({message: data.result, level: 'danger'});
				return;
			}
			$('#div_AppliDRC .DRC_eqLogics').empty().append('<div class="DRC_eqLogics_loading">Loading…</div>');
			var _nbActif = 0;
			for (var i in data.result.eqLogics) {
				if (data.result.eqLogics[i].html != '') {
					$('#div_AppliDRC .DRC_eqLogics').append(data.result.eqLogics[i].html).trigger('create');
					_nbActif++;
				}
			}
			$('#div_AppliDRC .DRC_PanelList .DRC_ALtxt').html(data.result.allUnitTxt);
			$('#div_AppliDRC .DRC_lastUpdate .DRC_LUtxt').html(data.result.lastUpdateTxt + ' : ');
			$('#div_AppliDRC .DRC_PanelList .DRC_ALnb').html(_nbActif);
			$('#div_AppliDRC .DRC_PanelList .DRC_AllUnit .DRC_btn-pow-on').off('click').on('click', function() { DRConClickPowAllUnit('on'); });
			$('#div_AppliDRC .DRC_PanelList .DRC_AllUnit .DRC_btn-pow-off').off('click').on('click', function() { DRConClickPowAllUnit('off'); });
			setTimeout(displayEqlogicWidthUpdate, 500);
			$.hideLoading();
			if (_eqLogId > 0) {
				displayDRCUnit(_eqLogId, true);
			}
		}
	});
}

function displayEqlogicWidthUpdate(hide) {
	if (hide) {
		$('#div_AppliDRC .eqLogic').hide();
		$('#div_AppliDRC .DRC_eqLogics_loading').show();
	} else {
		$('#div_AppliDRC .eqLogic').width($('#div_AppliDRC .DRC_AllUnit').width() + 'px');
		$('#div_AppliDRC .DRC_eqLogics_loading').hide();
		$('#div_AppliDRC .eqLogic').show();
		$('#div_AppliDRC .DRC_clickOpenCtrl').off('click').on('click', function() {
			var id = $(this).closest('.eqLogic').attr('data-eqLogic_id');
			displayDRCUnit(id, true);
		});
		$('#div_AppliDRC .DRC_back').off('click').on('click', function() {
			var id = $(this).closest('.eqLogic').attr('data-eqLogic_id');
			displayDRCUnit(id, false);
		});
	}
}

function displayDRCUnit(_eqLogId, _show) {
	_eqLogId = parseInt(_eqLogId, 10) || 0;
	if (_eqLogId <= 0) { return; }
	if (_show === true) {
		$('#div_AppliDRC .eqLogic_list').hide();
		$('#div_AppliDRC .eqLogic').hide();
		$('#div_AppliDRC .eqLogic[data-eqLogic_id=' + _eqLogId + ']').show();
		$('#div_AppliDRC .eqLogic[data-eqLogic_id=' + _eqLogId + '] .eqLogic_ctrl').show();
		_showUnit = _eqLogId;
	} else {
		$('#div_AppliDRC .eqLogic[data-eqLogic_id=' + _eqLogId + '] .eqLogic_ctrl').hide();
		$('#div_AppliDRC .eqLogic').show();
		$('#div_AppliDRC .eqLogic_list').show();
		_showUnit = 0;
	}
	displayEqlogicWidthUpdate();
}

function DRConClickPowAllUnit(_v) {
	$('.DRC_eqLogics .eqLogic').each(function() {
		$(this).find('.eqLogic_list .DRC_btn-pow-' + _v).click();
	});
}

function DRCpExec(_id, _opts) {
	if (!_id) { return; }
	var o = _opts || {};
	o.fromWidget = '1';
	jeedom.cmd.execute({id: _id, value: o, notify: true});
}

function DRCpExecItem(_item) {
	if (!_item || !_item.id) { return; }
	if (_item.exec === 'select') { DRCpExec(_item.id, {select: _item.v}); }
	else { DRCpExec(_item.id, {}); }
}

function DRCpSetPow(_eqLogId, _pow, _powId0, _powId1) {
	var $eq = $('.eqLogic[data-eqLogic_id=' + _eqLogId + ']');
	$eq.find('.DRC_btn-pow-off').attr('data-cmd_id', _powId0).off('click').on('click', function() {
		DRCpExec($(this).attr('data-cmd_id'), {});
	});
	$eq.find('.DRC_btn-pow-on').attr('data-cmd_id', _powId1).off('click').on('click', function() {
		DRCpExec($(this).attr('data-cmd_id'), {});
	});
	if (_pow == '1') {
		$eq.find('.DRC_btn-pow-on').off('click');
		$eq.find('.DRC_zPow').removeClass('disabled');
	} else {
		$eq.find('.DRC_btn-pow-off').off('click');
		$eq.find('.DRC_zPow, .DRC_zInfos, .DRC_zImg').addClass('disabled');
	}
}

function DRCpSetMode(_eqLogId, _pow, _mode, _aCmdList) {
	var $z = $('.eqLogic[data-eqLogic_id=' + _eqLogId + '] .eqLogic_ctrl .DRC_zMode');
	$z.empty();
	if (!_aCmdList || !_aCmdList.length) { return; }
	var html = '';
	for (var i = 0; i < _aCmdList.length; i++) {
		var obj = _aCmdList[i];
		var cls = (String(obj.v) === String(_mode)) ? 'DRC_btn-bleu' : '';
		html += "<span class='separ'>&nbsp;</span>";
		html += "<span data-cmd_id='" + obj.id + "' class='cmd action ui-btn ui-mini ui-btn-inline " + cls + "'>" + obj.icone + '</span>';
	}
	$z.append(html);
	$z.find('.cmd').each(function(i) {
		$(this).data('drcItem', _aCmdList[i]);
	});
	if (_pow == 1) {
		$z.find('.cmd').on('click', function() { DRCpExecItem($(this).data('drcItem')); });
	} else {
		$z.addClass('disabled');
	}
}

function DRCpSetMenu(_eqLogId, _pow, _value, _classBtn, _classMenu, _aCmdList) {
	var $eq = $('.eqLogic[data-eqLogic_id=' + _eqLogId + '] .eqLogic_ctrl');
	if (!_aCmdList || !_aCmdList.length) {
		$eq.find(_classBtn).hide();
		return;
	}
	var zMenu = "<span class='cmd action ui-btn ui-mini ui-btn-inline DRC_back'><i class='fa fa-chevron-left'></i></span>";
	var current = null;
	for (var i = 0; i < _aCmdList.length; i++) {
		var obj = _aCmdList[i];
		var cls = (String(_value) === String(obj.v)) ? 'DRC_btn-bleu' : '';
		if (String(_value) === String(obj.v)) { current = obj; }
		zMenu += "<span data-idx='" + i + "' class='cmd action ui-btn ui-mini ui-btn-inline " + cls + "'>" + obj.icone + '</span>';
	}
	$eq.find(_classMenu).html(zMenu).hide();
	$eq.find(_classMenu + ' .cmd').not('.DRC_back').on('click', function() {
		var idx = parseInt($(this).attr('data-idx'), 10);
		DRCpExecItem(_aCmdList[idx]);
	});
	$eq.find(_classMenu + ' .DRC_back').on('click', function() {
		$eq.find(_classMenu).hide();
		$eq.find('.DRC_zMenus').show();
	});
	if (current) {
		$eq.find(_classBtn).html(current.icone);
	}
	if (_pow == 1) {
		$eq.find(_classBtn).off('click').on('click', function() {
			if ($eq.find(_classMenu).is(':hidden')) {
				$eq.find('.DRC_zMenus').hide();
				$eq.find(_classMenu).show();
			} else {
				$eq.find(_classMenu).hide();
				$eq.find('.DRC_zMenus').show();
			}
		});
	}
}

function DRCpSetTemperature(_eqLogId, _pow, _stempId, _stempVal, _aSensorInfo, _step) {
	var $eq = $('.eqLogic[data-eqLogic_id=' + _eqLogId + ']');
	$eq.find('.DRC_ConsigneTemp, .DRC_ConsigneBig').html(_stempVal + '°');
	var h = (_aSensorInfo && _aSensorInfo.htemp) ? _aSensorInfo.htemp.v : '-';
	var o = (_aSensorInfo && _aSensorInfo.otemp) ? _aSensorInfo.otemp.v : '-';
	$eq.find('.DRC_HomeTemp').html((h === false || h === undefined) ? '-' : h);
	$eq.find('.DRC_ExtTemp').html((o === false || o === undefined) ? 'OFF' : o);
	var step = parseFloat(_step) || 0.5;
	$eq.find('.DRC_btn-temp').off('click');
	if (_pow == 1 && _stempId) {
		$eq.find('.DRC_btn-temp[drc-val="+"]').on('click', function() {
			DRCpExec(_stempId, {slider: parseFloat(_stempVal) + step});
		});
		$eq.find('.DRC_btn-temp[drc-val="-"]').on('click', function() {
			DRCpExec(_stempId, {slider: parseFloat(_stempVal) - step});
		});
	}
}

function DRCpSetOther(_eqLogId, _dateLastUpdate) {
	if (_dateLastUpdate > $('#div_AppliDRC .DRC_LUv').html()) {
		$('#div_AppliDRC .DRC_LUv').html(_dateLastUpdate);
	}
	var _nbActif = 0;
	$('.DRC_eqLogics .eqLogic').each(function() {
		if (!$(this).find('.eqLogic_list .DRC_zPow').hasClass('disabled')) { _nbActif++; }
	});
	$('#div_AppliDRC .DRC_PanelList .DRC_ALnb').html(_nbActif);
	if (_nbActif > 0) {
		$('.DRC_PanelList .DRC_AllUnit .DRC_zPow').removeClass('disabled');
	}
	setTimeout(displayEqlogicWidthUpdate, 500);
}
