<?php

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$rqDay = config::byKey('daikin_polling_dayInterval', 'daikinRCCloud', 0);
$rqNight = config::byKey('daikin_polling_nightInterval', 'daikinRCCloud', 0);
$rqNightStart = config::byKey('daikin_polling_nightStart', 'daikinRCCloud', 0);
$rqNightEnd = config::byKey('daikin_polling_nightEnd', 'daikinRCCloud', 0);
$totalRqDay = $rqNightStart - $rqNightEnd;
$totalrqnight = 24 - $totalRqDay;
$NbRqsDay = ($totalRqDay * 60) / $rqDay;
$NbRqsNight = ($totalrqnight * 60) / $rqNight;
$NbRqsTotal = $NbRqsDay + $NbRqsNight;
config::save('daikin_totalRqPerDay', $NbRqsTotal, 'daikinRCCloud');
?>
<form class="form-horizontal">
    <fieldset>
        <legend><i class="fas fa-wifi"></i> {{Informations globales}}</legend>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Version du plugin}}
                <sup><i class="fas fa-question-circle tooltips" title="{{Version du Plugin (A indiquer sur Community)}}"></i></sup>
            </label>
            <div class="col-sm-4">
                <input class="configKey form-control input-xs" data-l1key="pluginVersion" readonly />
            </div>
        </div>

        <legend><i class="fas fa-wifi"></i> {{Daikin Polling Settings}}</legend>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Intervalle de rafraîchissement pendant la journée}}</label>
            <div class="col-sm-4">
                <input type="number" class="configKey roundedLeft form-control" data-l1key="daikin_polling_dayInterval"
                    placeholder="{{Intervalle de rafraîchissement en minutes pendant la journée}}" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Intervalle de rafraîchissement pendant la nuit}}</label>
            <div class="col-sm-4">
                <input type="number" class="configKey roundedLeft form-control" data-l1key="daikin_polling_nightInterval"
                    placeholder="{{Intervalle de rafraîchissement en minutes pendant la nuit}}" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Heure de début de la période nuit}}</label>
            <div class="col-sm-4">
                <input type="number" class="configKey roundedLeft form-control" data-l1key="daikin_polling_nightStart"
                    placeholder="{{Heure de début de la période nuit (0-23)}}" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Heure de fin de la période nuit}}</label>
            <div class="col-sm-4">
                <input type="number" class="configKey roundedLeft form-control" data-l1key="daikin_polling_nightEnd"
                    placeholder="{{Heure de fin de la période nuit (0-23)}}" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Nombre de requètes sur la journée}}
                <sup><i class="fas fa-question-circle tooltips" title="{{Sauvegarder et rafraîchir la page pour voir le nouveau total}}"></i></sup>
            </label>
            <div class="col-sm-4">
                <input class="configKey roundedLeft form-control" data-l1key="daikin_totalRqPerDay" readonly />
            </div>
        </div>

        <legend><i class="fas fa-wrench"></i> {{Daikin Onecta Client Configuration}}</legend>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Client ID}}</label>
            <div class="input-group col-sm-4">
                <input type="password" class="configKey roundedLeft form-control" data-l1key="daikin_clientID"
                    placeholder="{{Client ID de votre application  Daikin Cloud}}" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Client Secret}}</label>
            <div class="input-group col-sm-4">
                <input type="password" class="configKey roundedLeft form-control" data-l1key="daikin_clientSecret"
                    placeholder="{{Client Secret de votre application Daikin Cloud}}" />
            </div>
        </div>

        <legend><i class="fas fa-tools"></i> {{Plugin Configuration}}</legend>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Port pour l'authentication}}</label>
            <div class="input-group col-sm-4">
                <input class="configKey roundedLeft form-control" data-l1key="daikin_clientPort"
                    placeholder="{{Default : 8765}}" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Préfixe MQTT}}
                <sup><i class="fas fa-question-circle tooltips" title="{{Préfixe à utiliser dans MQTT.}}"></i></sup>
            </label>
            <div class="col-sm-4">
                <input type="text" class="configKey form-control" data-l1key="prefix" placeholder="{{}}" />
            </div>
        </div>
    </fieldset>
</form>