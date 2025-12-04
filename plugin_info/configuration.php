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
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Version du daemon}}
                <sup><i class="fas fa-question-circle tooltips" title="{{Version du Deamon (A indiquer sur Community)}}"></i></sup>
            </label>
            <div class="col-sm-4">
                <input class="configKey form-control input-xs" data-l1key="deamonVersion" readonly />
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

        <legend><i class="fas fa-sync-alt"></i> {{Mode de rafraîchissement des actions}}</legend>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Mode de rafraîchissement}}
                <sup><i class="fas fa-question-circle tooltips" title="{{Mode de rafraîchissement après une action : 1=Rafraîchissement complet différé, 2=Mise à jour optimiste sans rafraîchissement cloud, 3=Hybride (optimiste + rafraîchissement complet différé).}}"></i></sup>
            </label>
            <div class="col-sm-4">
                <select class="configKey form-control" data-l1key="daikin_actionRefreshMode">
                    <option value="1">{{1 - Rafraîchissement complet différé}}</option>
                    <option value="2">{{2 - Mise à jour optimiste (sans cloud)}}</option>
                    <option value="3">{{3 - Hybride (optimiste + rafraîchissement complet)}}</option>
                </select>
                <a href="#help-actionRefreshMode" data-toggle="collapse" class="btn btn-xs btn-info" style="margin-top: 5px;">
                    <i class="fas fa-info-circle"></i> {{Aide sur les modes}}
                </a>
                <div id="help-actionRefreshMode" class="collapse" style="margin-top: 5px;">
                    <div class="alert alert-info" style="margin-bottom: 0;">
                        <small>
                            <strong>{{1 - Rafraîchissement complet différé :}}</strong> {{Rafraîchissement complet depuis le cloud Daikin, différé de}} <code>actionRefreshDelaySeconds</code> {{secondes après l'action. Garantit la cohérence mais génère plus de requêtes.}}<br/><br/>
                            <strong>{{2 - Mise à jour optimiste :}}</strong> {{Mise à jour immédiate du cache local et publication MQTT sans interroger le cloud Daikin après l'action. Plus rapide et moins de requêtes, mais pas de vérification cloud.}}<br/><br/>
                            <strong>{{3 - Hybride (recommandé) :}}</strong> {{Combine les avantages : mise à jour optimiste immédiate + rafraîchissement complet depuis le cloud après}} <code>actionRefreshDelaySeconds</code> {{secondes. Meilleur équilibre entre réactivité et cohérence.}}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Délai de rafraîchissement (secondes)}}
                <sup><i class="fas fa-question-circle tooltips" title="{{Délai en secondes avant le rafraîchissement complet depuis le cloud après une action. Utilisé en mode 1 et 3. Valeur par défaut : 120 secondes.}}"></i></sup>
            </label>
            <div class="col-sm-4">
                <input type="number" class="configKey form-control" data-l1key="daikin_actionRefreshDelaySeconds"
                    placeholder="{{120}}" min="0" />
                <a href="#help-actionRefreshDelaySeconds" data-toggle="collapse" class="btn btn-xs btn-info" style="margin-top: 5px;">
                    <i class="fas fa-info-circle"></i> {{Aide}}
                </a>
                <div id="help-actionRefreshDelaySeconds" class="collapse" style="margin-top: 5px;">
                    <div class="alert alert-info" style="margin-bottom: 0;">
                        <small>{{Délai en secondes avant le rafraîchissement complet depuis le cloud Daikin après une action. Utilisé uniquement en mode 1 (Rafraîchissement complet différé) et mode 3 (Hybride). Plus la valeur est élevée, plus cela laissera le temps au cloud et à votre device de traiter l'information et de se mettre à jour}}</small>
                    </div>
                </div>
            </div>
        </div>

        <legend><i class="fas fa-wrench"></i> {{Daikin Onecta Client Configuration}}</legend>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Client ID}}
                <sup><i class="fas fa-question-circle tooltips" title="{{Client ID de votre application Daikin Cloud. Obtenez-le sur developer.cloud.daikineurope.com}}"></i></sup>
            </label>
            <div class="input-group col-sm-4">
                <input type="password" class="configKey roundedLeft form-control" data-l1key="daikin_clientID"
                    placeholder="{{Client ID de votre application  Daikin Cloud}}" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Client Secret}}
                <sup><i class="fas fa-question-circle tooltips" title="{{Client Secret de votre application Daikin Cloud. Obtenez-le sur developer.cloud.daikineurope.com}}"></i></sup>
            </label>
            <div class="input-group col-sm-4">
                <input type="password" class="configKey roundedLeft form-control" data-l1key="daikin_clientSecret"
                    placeholder="{{Client Secret de votre application Daikin Cloud}}" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>
            <div class="col-sm-8">
                <a href="#help-daikin-config" data-toggle="collapse" class="btn btn-xs btn-info">
                    <i class="fas fa-question-circle"></i> {{Comment obtenir ces informations ?}}
                </a>
                <div id="help-daikin-config" class="collapse" style="margin-top: 10px;">
                    <div class="alert alert-info" style="margin-bottom: 0;">
                        <strong><i class="fas fa-info-circle"></i> {{Tutoriel de configuration}}</strong><br/>
                        <small>
                            <strong>{{Étape 1 :}}</strong> {{Allez sur}} <a href="https://developer.cloud.daikineurope.com/" target="_blank">developer.cloud.daikineurope.com</a> {{et connectez-vous avec votre compte Daikin}}<br/><br/>
                            <strong>{{Étape 2 :}}</strong> {{En haut à droite, cliquez sur votre adresse email et choisissez "My Apps"}}<br/><br/>
                            <strong>{{Étape 3 :}}</strong> {{Cliquez sur "New App"}}<br/><br/>
                            <strong>{{Étape 4 :}}</strong> {{Donnez un nom à votre application et cliquez sur "Créer"}}<br/><br/>
                            <strong>{{Étape 5 :}}</strong> {{Copiez le}} <strong>Client ID</strong> {{affiché et collez-le dans le champ "Client ID" ci-dessus}}<br/><br/>
                            <strong>{{Étape 6 :}}</strong> {{Copiez le}} <strong>Client Secret</strong> {{affiché et collez-le dans le champ "Client Secret" ci-dessus}}<br/><br/>
                            <strong>{{Étape 7 :}}</strong> {{Démarrez le daemon et ouvrez les logs. Copiez l'URL d'authentification affichée dans les logs}}<br/><br/>
                            <strong>{{Étape 8 :}}</strong> {{Retournez sur le site Daikin Developer, éditez votre application et collez l'URL précédemment copiée dans le champ "Redirect URI", puis cliquez sur "Update"}}<br/><br/>
                            <strong>{{Étape 9 :}}</strong> {{Dans un navigateur, ouvrez l'URL copiée à l'étape 7 et suivez le processus d'authentification}}<br/><br/>
                            <strong>{{Étape 10 :}}</strong> {{Si tout est correct, vous verrez un message de succès. Le plugin est maintenant configuré !}}<br/><br/>
                            <strong>{{Documentation complète :}}</strong> <a href="https://community.jeedom.com/t/onecta-cloud-api-key-invalidation-action-required/127311/33?u=thibaut_t" target="_blank">{{Voir le tutoriel complet sur la communauté Jeedom}}</a>
                        </small>
                    </div>
                </div>
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

        <legend><i class="fas fa-code-branch"></i> {{Configuration des dépendances}}</legend>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Type de référence}}
                <sup><i class="fas fa-question-circle tooltips" title="{{Choisissez si vous voulez installer une branche ou un commit spécifique}}"></i></sup>
            </label>
            <div class="col-sm-4">
                <select class="configKey form-control" data-l1key="daikin_dependency_type">
                    <option value="branch">{{Branche}}</option>
                    <option value="commit">{{Commit}}</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Branche ou Commit}}
                <sup><i class="fas fa-question-circle tooltips" title="{{Nom de la branche (ex: release-beta, main, dev) ou hash du commit (ex: abc123def456)}}"></i></sup>
            </label>
            <div class="col-sm-4">
                <input type="text" class="configKey form-control" data-l1key="daikin_dependency_ref"
                    placeholder="{{release-stable}}" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>
            <div class="col-sm-8">
                <a href="#help-dependency-config" data-toggle="collapse" class="btn btn-xs btn-info">
                    <i class="fas fa-question-circle"></i> {{Aide sur la configuration des dépendances}}
                </a>
                <div id="help-dependency-config" class="collapse" style="margin-top: 10px;">
                    <div class="alert alert-info" style="margin-bottom: 0;">
                        <small>
                            <strong>{{Type de référence :}}</strong><br/>
                            {{• Branche :}} {{Installe la dernière version de la branche spécifiée (ex: release-beta, main, dev)}}<br/>
                            {{• Commit :}} {{Installe un commit spécifique en utilisant son hash (ex: abc123def456...)}}<br/><br/>
                            <strong>{{Branche ou Commit :}}</strong><br/>
                            {{• Pour une branche :}} {{Indiquez le nom de la branche (par défaut: release-stable)}}<br/>
                            {{• Pour un commit :}} {{Indiquez le hash complet du commit (ex: abc123def456789...)}}<br/><br/>
                            <strong>{{Note :}}</strong> {{Après modification, vous devrez relancer l'installation des dépendances pour que les changements prennent effet.}}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</form>