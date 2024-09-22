<?php

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<form class="form-horizontal">
    <fieldset>
        <legend><i class="fas fa-wifi"></i> {{Daikin limitation requête}}</legend>
        <div class="form-group" style="padding-bottom:4px">
            <table style="margin-left: auto; margin-right: auto; table-layout: auto; width: 60%">
                <tr>
                    <th style="text-align: left; ">{{Date dernière mise à jour : }}<span class="label configKey" data-l1key="rate_lastupdate"></span></th>
                    <th style="text-align: center; ">{{Maximum possible}}</th>
                    <th style="text-align: center; ">{{Requete restante}}</th>
                </tr>
                <tr>
                    <td style="text-align: left; resize: both">{{Sur 60 secondes glissantes}}</td>
                    <td style="text-align: center; resize: both"><span class="label configKey" style="background-color : green; color:white;" data-l1key="rate_limitMinute"></span></td>
                    <td style="text-align: center; resize: both"><span class="label configKey" style="background-color : green; color:white;" data-l1key="rate_remainingMinute"></span></td>
                </tr>

                <tr>
                    <td style="text-align: left; resize: both">{{Sur 24 heures glissantes}}</td>
                    <td style="text-align: center; resize: both"><span class="label configKey" style="background-color : green; color:white;" data-l1key="rate_limitDay"></span></td>
                    <td style="text-align: center; resize: both"><span class="label configKey" style="background-color : green; color:white;" data-l1key="rate_remainingDay"></span></td>
                </tr>
            </table>
        </div>

        <legend><i class="fas fa-wrench"></i> {{Daikin Onecta Client Configuration}}</legend>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Client ID}}</label>
            <div class="input-group col-sm-7">
                <input type="password" class="configKey roundedLeft form-control" data-l1key="daikin_clientID"
                       placeholder="{{Client ID de votre application  Daikin Cloud}}"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Client Secret}}</label>
            <div class="input-group col-sm-7">
                <input type="password" class="configKey roundedLeft form-control" data-l1key="daikin_clientSecret"
                       placeholder="{{Client Secret de votre application Daikin Cloud}}"/>
            </div>
        </div>

        <legend><i class="fas fa-tools"></i> {{Plugin Configuration}}</legend>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Port pour l'authentication}}</label>
            <div class="input-group col-sm-7">
                <input class="configKey roundedLeft form-control" data-l1key="daikin_clientPort"
                       placeholder="{{Default : 8765}}"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Préfixe  MQTT}}
                <sup><i class="fas fa-question-circle tooltips" title="{{Préfixe à utiliser dans MQTT.}}"></i></sup>
            </label>
            <div class="col-sm-7">
                <input type="text" class="configKey form-control" data-l1key="prefix" placeholder="{{}}"/>
            </div>
        </div>
    </fieldset>
</form>