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
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Client ID}}</label>
                <div class="input-group col-sm-7">
                    <input type="password" class="configKey roundedLeft form-control" data-l1key="daikin_clientID" placeholder="{{Client ID de votre application  Daikin Cloud}}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Client Secret}}</label>
                <div class="input-group col-sm-7">
                    <input type="password" class="configKey roundedLeft form-control" data-l1key="daikin_clientSecret" placeholder="{{Client Secret de votre application Daikin Cloud}}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Port du client}}</label>
                <div class="input-group col-sm-7">
                    <input class="configKey roundedLeft form-control" data-l1key="daikin_clientPort" placeholder="{{Default : 8765}}" />
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
