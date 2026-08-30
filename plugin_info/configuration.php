<?php

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
try {
    $authMode = config::byKey('daikin_authMode', 'daikinRCCloud', 'developer_portal');
    $dailyQuotaLimit = ($authMode === 'mobile_app') ? 3000 : 200;
    config::save('daikin_dailyQuotaLimit', $dailyQuotaLimit, 'daikinRCCloud');

    $rqDay = config::byKey('daikin_polling_dayInterval', 'daikinRCCloud', 0);
    $rqNight = config::byKey('daikin_polling_nightInterval', 'daikinRCCloud', 0);
    $rqNightStart = config::byKey('daikin_polling_nightStart', 'daikinRCCloud', 0);
    $rqNightEnd = config::byKey('daikin_polling_nightEnd', 'daikinRCCloud', 0);
    $totalRqDay = $rqNightStart - $rqNightEnd;
    $totalrqnight = 24 - $totalRqDay;
    if ($rqDay > 0 && $rqNight > 0 && $rqNightStart >= 0 && $rqNightEnd >= 0) {
        $NbRqsDay = ($totalRqDay * 60) / $rqDay;
        $NbRqsNight = ($totalrqnight * 60) / $rqNight;
        $NbRqsTotal = $NbRqsDay + $NbRqsNight;
    } else {
        $NbRqsTotal = $dailyQuotaLimit;
    }
    config::save('daikin_totalRqPerDay', round($NbRqsTotal), 'daikinRCCloud');
} catch (\Exception $e) {
    log::add('daikinRCCloud', 'error', '{{Erreur lors du chargement de la configuration : }} ' . $e->getMessage());
}
$displayPluginVersion = config::byKey('pluginVersion', 'daikinRCCloud', '—');
$displayDeamonVersion = config::byKey('deamonVersion', 'daikinRCCloud', '—');
?>

<form class="form-horizontal">
    <fieldset>
        <legend><i class="fas fa-info-circle"></i> {{Informations}}</legend>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Versions}}
                <sup><i class="fas fa-question-circle tooltips" title="{{Versions du plugin et du daemon à indiquer en cas de demande sur la communauté Jeedom.}}"></i></sup>
            </label>
            <div class="col-sm-8">
                <p class="form-control-static" id="daikin-versions-display">
                    <span>{{Plugin}} :</span> <strong class="daikin-version-plugin"><?php echo htmlspecialchars($displayPluginVersion, ENT_QUOTES, 'UTF-8'); ?></strong>
                    <span class="text-muted"> — </span>
                    <span>{{Daemon}} :</span> <strong class="daikin-version-daemon"><?php echo htmlspecialchars($displayDeamonVersion, ENT_QUOTES, 'UTF-8'); ?></strong>
                </p>
                <input class="configKey" data-l1key="pluginVersion" type="hidden" />
                <input class="configKey" data-l1key="deamonVersion" type="hidden" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">{{Configuration avancée}}
                <sup><i class="fas fa-question-circle tooltips" title="{{Affiche les réglages de polling, de synchronisation post-action, MQTT et dépendances. Laissez désactivé si la configuration par défaut vous convient.}}"></i></sup>
            </label>
            <div class="col-sm-4">
                <input type="checkbox" id="daikin_configAdvanced" />
                <span class="help-block">{{Affiche le polling, le tuning refresh, MQTT et les dépendances}}</span>
            </div>
        </div>

        <legend><i class="fas fa-key"></i> {{Connexion Daikin}}</legend>
        <div class="form-group">
            <label class="col-sm-3 control-label">{{Mode d'authentification}}
                <sup><i class="fas fa-question-circle tooltips" title="{{Developer Portal : OAuth manuel via developer.cloud.daikineurope.com, quota 200 req/jour. Mobile App : email et mot de passe Onecta, quota 3000 req/jour + WebSocket temps réel.}}"></i></sup>
            </label>
            <div class="col-sm-4">
                <select class="configKey form-control" data-l1key="daikin_authMode" id="daikin_authMode">
                    <option value="developer_portal">{{Developer Portal (OAuth)}}</option>
                    <option value="mobile_app">{{Mobile App (recommandé)}}</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label"></label>
            <div class="col-sm-8">
                <div id="daikin-auth-quota-hint-developer" class="alert alert-info" style="margin-bottom: 0;">
                    <small><i class="fas fa-info-circle"></i> {{Developer Portal : quota API de 200 requêtes/jour. Configuration OAuth requise (Client ID / Secret).}}</small>
                </div>
                <div id="daikin-auth-quota-hint-mobile" class="alert alert-success" style="margin-bottom: 0; display:none;">
                    <small><i class="fas fa-check-circle"></i> {{Le mode Mobile App utilise vos identifiants Onecta (même compte que l'application mobile). Quota API : 3000 requêtes/jour. Aucune configuration OAuth manuelle requise.}}</small>
                </div>
            </div>
        </div>

        <div id="daikin-auth-developer">
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
        </div>

        <div id="daikin-auth-mobile" style="display:none;">
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Email Onecta}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Adresse email de votre compte application Daikin Onecta / Daikin Residential Controller}}"></i></sup>
                </label>
                <div class="col-sm-4">
                    <input type="text" class="configKey form-control" data-l1key="daikin_onectaEmail"
                        placeholder="{{email@exemple.com}}" autocomplete="username" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Mot de passe Onecta}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Mot de passe de votre compte Onecta. Stocké chiffré dans Jeedom.}}"></i></sup>
                </label>
                <div class="col-sm-4">
                    <input type="password" class="configKey form-control" data-l1key="daikin_onectaPassword"
                        placeholder="{{Mot de passe Onecta}}" autocomplete="current-password" />
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">{{Quota API journalier (mode auth)}}
                <sup><i class="fas fa-question-circle tooltips" title="{{200 req/jour en Developer Portal, 3000 req/jour en Mobile App. Le WebSocket réduit le besoin de polling en mode Mobile App.}}"></i></sup>
            </label>
            <div class="col-sm-4">
                <input class="configKey roundedLeft form-control" data-l1key="daikin_dailyQuotaLimit" readonly />
            </div>
        </div>

        <div id="daikin-config-advanced" style="display:none;">

            <legend><i class="fas fa-sync"></i> {{Synchronisation et polling}}</legend>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Intervalle de rafraîchissement pendant la journée}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Fréquence de lecture du cloud Daikin en journée (minutes). Plus bas = plus réactif mais consomme plus de quota API. Défaut : 15 min. Minimum recommandé : 5 min (Developer Portal) / 1 min (Mobile App).}}"></i></sup>
                </label>
                <div class="col-sm-4">
                    <input type="number" class="configKey roundedLeft form-control" data-l1key="daikin_polling_dayInterval"
                        placeholder="{{Intervalle de rafraîchissement en minutes pendant la journée}}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Intervalle de rafraîchissement pendant la nuit}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Fréquence de lecture pendant la période nuit (minutes). Défaut : 30 min. Permet d'économiser le quota quand l'usage est faible.}}"></i></sup>
                </label>
                <div class="col-sm-4">
                    <input type="number" class="configKey roundedLeft form-control" data-l1key="daikin_polling_nightInterval"
                        placeholder="{{Intervalle de rafraîchissement en minutes pendant la nuit}}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Heure de début de la période nuit}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Heure de début de la période nuit (0-23). Défaut : 22h. Le polling nuit s'applique à partir de cette heure.}}"></i></sup>
                </label>
                <div class="col-sm-4">
                    <input type="number" class="configKey roundedLeft form-control" data-l1key="daikin_polling_nightStart"
                        placeholder="{{Heure de début de la période nuit (0-23)}}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Heure de fin de la période nuit}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Heure de fin de la période nuit (0-23). Défaut : 7h. Exemple : 22→7 = nuit de 22h à 7h.}}"></i></sup>
                </label>
                <div class="col-sm-4">
                    <input type="number" class="configKey roundedLeft form-control" data-l1key="daikin_polling_nightEnd"
                        placeholder="{{Heure de fin de la période nuit (0-23)}}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Nombre de requètes sur la journée (polling)}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Estimation basée sur les intervalles de polling. Sauvegarder et rafraîchir la page pour recalculer.}}"></i></sup>
                </label>
                <div class="col-sm-4">
                    <input class="configKey roundedLeft form-control" data-l1key="daikin_totalRqPerDay" readonly />
                </div>
            </div>

            <legend><i class="fas fa-sync-alt"></i> {{Comportement après action}}</legend>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Mode de rafraîchissement}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Comportement après une commande : mode 1 = vérification cloud différée (plus de requêtes), mode 2 = mise à jour immédiate sans cloud (plus rapide), mode 3 = hybride recommandé (immédiat + vérification cloud). Défaut : 3.}}"></i></sup>
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
                    <sup><i class="fas fa-question-circle tooltips" title="{{Délai en secondes avant le rafraîchissement complet depuis le cloud après une action. Utilisé en mode 1 et 3. Valeur par défaut : 60 secondes.}}"></i></sup>
                </label>
                <div class="col-sm-4">
                    <input type="number" class="configKey form-control" data-l1key="daikin_actionRefreshDelaySeconds"
                        placeholder="{{60}}" min="0" />
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
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Stratégie refresh post-action}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Comment déclencher la vérification cloud après une action : fusion avec le prochain polling (économise le quota, recommandé), requête dédiée après délai, ou désactivée. Défaut : fusion avec polling.}}"></i></sup>
                </label>
                <div class="col-sm-4">
                    <select class="configKey form-control" data-l1key="daikin_actionRefreshStrategy">
                        <option value="merge_with_poll">{{merge_with_poll - Fusion avec polling (recommandé)}}</option>
                        <option value="timer">{{timer - GET dédié après délai}}</option>
                        <option value="disabled">{{disabled - Pas de GET cloud}}</option>
                    </select>
                    <a href="#help-actionRefreshStrategy" data-toggle="collapse" class="btn btn-xs btn-info" style="margin-top: 5px;">
                        <i class="fas fa-info-circle"></i> {{Aide sur les stratégies}}
                    </a>
                    <div id="help-actionRefreshStrategy" class="collapse" style="margin-top: 5px;">
                        <div class="alert alert-info" style="margin-bottom: 0;">
                            <small>
                                <strong>{{merge_with_poll - Fusion avec polling (recommandé) :}}</strong> {{Si le prochain polling est prévu dans la fenêtre définie (Fenêtre fusion polling), le refresh post-action attend ce cycle au lieu de lancer un GET dédié. Réduit la consommation de quota API tout en garantissant une mise à jour cloud.}}<br/><br/>
                                <strong>{{timer - GET dédié après délai :}}</strong> {{Lance un GET cloud ciblé sur l'équipement concerné après le délai de rafraîchissement, indépendamment du planning de polling. Plus réactif mais consomme une requête API supplémentaire à chaque action.}}<br/><br/>
                                <strong>{{disabled - Pas de GET cloud :}}</strong> {{Aucune requête GET cloud après une action. Seule la mise à jour locale (selon le mode de rafraîchissement choisi) est appliquée. Utile pour économiser le quota, mais sans vérification cloud.}}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Fenêtre fusion polling (minutes)}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Si un refresh post-action est prévu dans les X prochaines minutes, il est fusionné avec le prochain polling au lieu d'un GET dédié. Défaut : 5 min. Réduit la consommation de quota.}}"></i></sup>
                </label>
                <div class="col-sm-4">
                    <input type="number" class="configKey form-control" data-l1key="daikin_mergeWithPollWindowMinutes"
                        placeholder="{{5}}" min="1" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Coalescence commandes (ms)}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Regroupe les commandes MQTT rapprochées en une seule requête API (millisecondes). Défaut : 400 ms. Utile si plusieurs consignes sont envoyées d'affilée.}}"></i></sup>
                </label>
                <div class="col-sm-4">
                    <input type="number" class="configKey form-control" data-l1key="daikin_commandCoalesceMs"
                        placeholder="{{400}}" min="0" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Refresh stats énergie (HH:MM)}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Heure quotidienne (HH:MM) du rafraîchissement prioritaire des statistiques de consommation. Défaut : 23:58. Consomme 1 requête API réservée par jour.}}"></i></sup>
                </label>
                <div class="col-sm-4">
                    <input type="text" class="configKey form-control" data-l1key="daikin_energyStatsRefreshTime"
                        placeholder="{{23:58}}" />
                </div>
            </div>

            <legend><i class="fas fa-microchip"></i> {{DynamicGateway & API}}</legend>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{DynamicGateway (modèles inconnus)}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Active le mapping automatique des caractéristiques API pour les modèles Daikin non listés (BRP069A4x, BRP069C4x, etc.). Désactiver si vous rencontrez des comportements inattendus. Défaut : activé.}}"></i></sup>
                </label>
                <div class="col-sm-4">
                    <input type="checkbox" class="configKey" data-l1key="daikin_dynamicFallback" checked="checked" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Publier capteurs lecture seule}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Publie sur MQTT les capteurs en lecture seule (températures extérieures, diagnostics, etc.). Désactiver réduit le bruit MQTT si non utilisé. Défaut : activé.}}"></i></sup>
                </label>
                <div class="col-sm-4">
                    <input type="checkbox" class="configKey" data-l1key="daikin_exposeReadOnly" checked="checked" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Publication MQTT si delta}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Ne publie sur MQTT que si la valeur a changé depuis la dernière lecture. Réduit le trafic MQTT. Défaut : activé.}}"></i></sup>
                </label>
                <div class="col-sm-4">
                    <input type="checkbox" class="configKey" data-l1key="daikin_publishOnDelta" checked="checked" />
                </div>
            </div>

            <legend><i class="fas fa-sliders-h"></i> {{Options plugin}}</legend>
            <div class="form-group daikin-auth-port">
                <label class="col-sm-3 control-label">{{Port pour l'authentication}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Port local pour le callback OAuth (mode Developer Portal uniquement). Défaut : 8765. Vérifiez que ce port est libre et autorisé par votre pare-feu.}}"></i></sup>
                </label>
                <div class="input-group col-sm-4">
                    <input class="configKey roundedLeft form-control" data-l1key="daikin_clientPort"
                        placeholder="{{Default : 8765}}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Préfixe MQTT}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Préfixe des topics MQTT utilisés par le daemon (ex: daikinToMQTT/deviceId). Doit correspondre à la configuration mqtt2. Défaut : daikinToMQTT.}}"></i></sup>
                </label>
                <div class="col-sm-4">
                    <input type="text" class="configKey form-control" data-l1key="prefix" placeholder="{{daikinToMQTT}}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{WebSocket temps réel}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Reçoit les mises à jour en temps réel depuis le cloud Daikin (mode Mobile App). Réduit fortement le polling nécessaire. Défaut : activé.}}"></i></sup>
                </label>
                <div class="col-sm-4">
                    <input type="checkbox" class="configKey" data-l1key="daikin_enableWebSocket" checked="checked" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Transport HTTP}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Moteur HTTP pour les appels API. Utilisez curl si Node.js est bloqué par le WAF Daikin (erreurs TLS, 403 ou timeouts répétés). Défaut : Node.js.}}"></i></sup>
                </label>
                <div class="col-sm-4">
                    <select class="configKey form-control" data-l1key="daikin_httpTransport">
                        <option value="node">{{Node.js (défaut)}}</option>
                        <option value="curl">{{curl (fallback WAF)}}</option>
                    </select>
                </div>
            </div>

            <legend><i class="fas fa-code-branch"></i> {{Configuration des dépendances}}</legend>
            <div class="form-group">
                <label class="col-sm-3 control-label"></label>
                <div class="col-sm-8">
                    <div class="alert alert-warning" style="margin-bottom: 10px;">
                        <small><i class="fas fa-exclamation-triangle"></i> {{Le daemon daikintomqtt >= 2.0.0 est obligatoire. Les branches release-stable, dev et release-dev ne sont plus supportées.}}</small>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">{{Type de référence}}
                    <sup><i class="fas fa-question-circle tooltips" title="{{Branche : installe la dernière version d'une branche (release-beta recommandé). Commit : version figée par hash Git. Réservé aux tests ou corrections ponctuelles.}}"></i></sup>
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
                    <sup><i class="fas fa-question-circle tooltips" title="{{Nom de branche (ex: release-beta, main) ou hash de commit complet. Branches supportées : release-beta et versions stables V2. Relancez l'installation des dépendances après modification.}}"></i></sup>
                </label>
                <div class="col-sm-4">
                    <input type="text" class="configKey form-control" data-l1key="daikin_dependency_ref"
                        placeholder="{{release-beta}}" />
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
                                {{• Pour une branche :}} {{Indiquez le nom de la branche (par défaut: release-beta)}}<br/>
                                {{• Pour un commit :}} {{Indiquez le hash complet du commit (ex: abc123def456789...)}}<br/><br/>
                                <strong>{{Note :}}</strong> {{Après modification, vous devrez relancer l'installation des dépendances pour que les changements prennent effet.}}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </fieldset>
</form>

<?php include_file('plugin_info', 'daikinRCCloud.config', 'js', 'daikinRCCloud'); ?>
<script>
(function() {
    var remaining = 12;
    function run() {
        if (typeof daikinRCCloud_initConfigUI === 'function') {
            daikinRCCloud_initConfigUI();
        }
        remaining -= 1;
        if (remaining > 0) {
            setTimeout(run, 400);
        }
    }
    run();
})();
</script>
