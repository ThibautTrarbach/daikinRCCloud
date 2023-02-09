<?php

	require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
	include_file('core', 'authentification', 'php');
	if (!isConnect('admin')) {
		throw new Exception('{{401 - Accès non autorisé}}');
	}
?>
<form class="form-horizontal">
    <fieldset>
        <legend><i class="fas fa-wifi"></i> {{Daikin}}</legend>
        <div class="form-group">
            <div class="form-group" hidden>
                <label class="col-sm-3 control-label">{{Utilisé le mode proxy}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Cocher la case pour utiliser le mode Proxy}}"></i></sup>
                </label>
                <div class="col-md-1">
                    <input type="checkbox" class="configKey" data-l1key="daikin_modeproxy"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Email}}</label>
                <div class="input-group col-sm-7">
                    <input class="configKey roundedLeft form-control" data-l1key="daikin_username" placeholder="{{Email d'accès a votre compte ONECTA}}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Mots de passe}}</label>
                <div class="input-group col-sm-7">
                    <input type="password" class="configKey roundedLeft form-control" data-l1key="daikin_password" placeholder="{{Mots de passe d'accès a votre compte ONECTA}}" />
                </div>
            </div>
            <div class="form-group" hidden>
                <label class="col-sm-3 control-label">{{Port du proxy}}</label>
                <div class="input-group col-sm-7">
                    <input class="configKey roundedLeft form-control" data-l1key="daikin_proxyPort" placeholder="{{Default : 8888}}" />
                </div>
            </div>
            <div class="form-group" hidden>
                <label class="col-sm-3 control-label">{{Port web du proxy}}</label>
                <div class="input-group col-sm-7">
                    <input class="configKey roundedLeft form-control" data-l1key="daikin_proxyWebPort" placeholder="{{Default : 8889}}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Préfixe}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Préfixe à utiliser dans MQTT.}}"></i></sup>
                </label>
                <div class="col-sm-7">
                    <input type="text" class="configKey form-control" data-l1key="prefix" placeholder="{{}}"/>
                </div>
            </div>
    </fieldset>
</form>

<script>
    $('.randomKey').off('click').on('click', function () {
        var el = $(this)
        bootbox.confirm('{{Êtes-vous sûr de vouloir réinitialiser la clé}}' + ' ' + el.attr('data-key') + ' ? La prise en compte sera effective après sauvegarde et relance du démon.', function(result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: "plugins/zwavejs/core/ajax/zwavejs.ajax.php",
                    data: {
                        action: "generateRandomKey"
                    },
                    dataType: 'json',
                    error: function(request, status, error) {
                        handleAjaxError(request, status, error)
                    },
                    success: function(data) {
                        el.closest('.input-group').find('.configKey').value(data.result)
                    }
                })
            }
        })
    })
</script>
