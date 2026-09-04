<?php
	if (!isConnect('admin')) {
		throw new Exception('{{401 - Accès non autorisé}}');
	}
	// Déclaration des variables obligatoires
	$plugin = plugin::byId('daikinRCCloud');
	sendVarToJS('eqType', $plugin->getId());
	$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
    <!-- Page d'accueil du plugin -->
    <div class="col-xs-12 eqLogicThumbnailDisplay">
        <legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
        <!-- Boutons de gestion du plugin -->
        <div class="eqLogicThumbnailContainer">
            <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
                <i class="fas fa-wrench"></i>
                <br>
                <span>{{Configuration}}</span>
            </div>
			<?php
				$jeedomVersion  = jeedom::version() ?? '0';
				$displayInfoValue = version_compare($jeedomVersion, '4.4.0', '>=');
				if ($displayInfoValue) {
					?>
                    <div class="cursor eqLogicAction logoSecondary" data-action="createCommunityPost">
                        <i class="fas fa-ambulance"></i>
                        <br>
                        <span style="color:var(--txt-color)">{{Créer un post Community}}</span>
                    </div>
					<?php
				}
			?>
        </div>
        <legend><i class="fas fa-table"></i> {{Mes Equipments}}</legend>
		<?php
			if (count($eqLogics) == 0) {
				echo '<br><div class="text-center" style="font-size:1.2em;font-weight:bold;">{{Aucun équipement trouvé, relancer le daemon ou contacter le developer }}</div>';
			} else {
				// Champ de recherche
				echo '<div class="input-group" style="margin:5px;">';
				echo '<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic">';
				echo '<div class="input-group-btn">';
				echo '<a id="bt_resetSearch" class="btn" style="width:30px"><i class="fas fa-times"></i></a>';
				echo '<a class="btn roundedRight hidden" id="bt_pluginDisplayAsTable" data-coreSupport="1" data-state="0"><i class="fas fa-grip-lines"></i></a>';
				echo '</div>';
				echo '</div>';
				// Liste des équipements du plugin
				echo '<div class="eqLogicThumbnailContainer">';
				foreach ($eqLogics as $eqLogic) {
					$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
					$supportStatus = $eqLogic->getConfiguration('supportStatus', 'full');
					$configCoverage = $eqLogic->getConfiguration('configCoverage', 'complete');
					$needsSupportBadge = ($supportStatus !== 'full') || ($configCoverage === 'incomplete');
					echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
					echo '<img src="' . $plugin->getPathImgIcon() . '">';
					if ($needsSupportBadge && $eqLogic->getLogicalId() !== daikinRCCloud::INSTANCE_ID) {
						echo '<span class="label label-warning pull-right" title="{{Support partiel ou configuration incomplète}}"><i class="fas fa-exclamation-triangle"></i></span>';
					}
					echo '<br>';
					echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
					echo '<span class="hiddenAsCard displayTableRight hidden">';
					echo ($eqLogic->getIsVisible() == 1) ? '<i class="fas fa-eye" title="{{Equipement visible}}"></i>' : '<i class="fas fa-eye-slash" title="{{Equipement non visible}}"></i>';
					echo '</span>';
					echo '</div>';
				}
				echo '</div>';
			}
		?>
    </div> <!-- /.eqLogicThumbnailDisplay -->

    <!-- Page de présentation de l'équipement -->
    <div class="col-xs-12 eqLogic" style="display: none;">
        <!-- barre de gestion de l'équipement -->
        <div class="input-group pull-right" style="display:inline-flex;">
			<span class="input-group-btn">
				<a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i><span class="hidden-xs"> {{Configuration avancée}}</span>
				</a><a class="btn btn-sm btn-info eqLogicAction" data-action="createCommunityPost"><i class="fas fa-ambulance"></i><span class="hidden-xs"> {{Créer un post Community}}</span>
				</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
				</a><a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}
				</a>
			</span>
        </div>
        <!-- Onglets -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
            <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
            <li role="presentation"><a href="#commandtab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-list"></i> {{Commandes}}</a></li>
        </ul>
        <div class="tab-content">
            <!-- Onglet de configuration de l'équipement -->
            <div role="tabpanel" class="tab-pane active" id="eqlogictab">
                <!-- Partie gauche de l'onglet "Equipements" -->
                <!-- Paramètres généraux et spécifiques de l'équipement -->
                <form class="form-horizontal">
                    <fieldset>
                        <div class="col-lg-6">
                            <legend><i class="fas fa-wrench"></i> {{Paramètres généraux}}</legend>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{Nom de l'équipement}}</label>
                                <div class="col-sm-6">
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display:none;">
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" >{{Objet parent}}</label>
                                <div class="col-sm-6">
                                    <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                                        <option value="">{{Aucun}}</option>
										<?php
											$options = '';
											foreach ((jeeObject::buildTree(null, false)) as $object) {
												$options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
											}
											echo $options;
										?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{Catégorie}}</label>
                                <div class="col-sm-6">
									<?php
										foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
											echo '<label class="checkbox-inline">';
											echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" >' . $value['name'];
											echo '</label>';
										}
									?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{{Options}}</label>
                                <div class="col-sm-6">
                                    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked>{{Activer}}</label>
                                    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked>{{Visible}}</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12" id="daikin_support_alert" style="display:none;">
                            <div class="alert" id="daikin_support_alert_box">
                                <strong id="daikin_support_alert_title"></strong>
                                <p id="daikin_support_alert_message"></p>
                            </div>
                        </div>

                        <!-- Partie droite de l'onglet "Équipement" -->
                        <div class="col-xs-4 alert alert-info eqDefault">
                            <form class="form-horizontal">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-8 control-label"><u>{{Information sur l'équipement associé}}</u></label>

                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">{{Modèle : }}</label>
                                        <div class="col-sm-6">
                                            <span class="eqLogicAttr" data-l1key="configuration" data-l2key="modelInfo"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">{{Numéro de série : }}</label>
                                        <div class="col-sm-6">
                                            <span class="eqLogicAttr" data-l1key="configuration" data-l2key="serialNumber"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">{{Firmware Version : }}</label>
                                        <div class="col-sm-6">
                                            <span class="eqLogicAttr" data-l1key="configuration" data-l2key="firmwareVersion"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label">{{Code Erreur : }}</label>
                                        <div class="col-sm-6">
                                            <span class="eqLogicAttr" data-l1key="configuration" data-l2key="errorCode"></span>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>

                        <div class="col-xs-12" id="daikin_support_debug">
                            <div class="alert alert-info" style="margin-bottom:15px;">
                                <legend><i class="fas fa-info-circle"></i> {{Informations API / diagnostic}}</legend>
                                <div class="form-group" id="daikin_support_message_group" style="display:none;">
                                    <label class="col-sm-3 control-label">{{Message support}}</label>
                                    <div class="col-sm-9">
                                        <p id="daikin_support_message_display" class="form-control-static"></p>
                                        <span class="eqLogicAttr" data-l1key="configuration" data-l2key="supportMessage" style="display:none;"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">{{Statut support}}</label>
                                    <div class="col-sm-9">
                                        <span class="eqLogicAttr" data-l1key="configuration" data-l2key="supportStatus"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">{{Couverture config}}</label>
                                    <div class="col-sm-9">
                                        <span class="eqLogicAttr" data-l1key="configuration" data-l2key="configCoverage"></span>
                                        (<span class="eqLogicAttr" data-l1key="configuration" data-l2key="configCoverageDetail"></span>)
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">{{Modèle gateway (API)}}</label>
                                    <div class="col-sm-9">
                                        <span class="eqLogicAttr" data-l1key="configuration" data-l2key="gatewayModelRaw"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">{{Modèle gateway (résolu)}}</label>
                                    <div class="col-sm-9">
                                        <span class="eqLogicAttr" data-l1key="configuration" data-l2key="gatewayModelResolved"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">{{Points de gestion}}</label>
                                    <div class="col-sm-9">
                                        <span class="eqLogicAttr" data-l1key="configuration" data-l2key="managementPointsList"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">{{Unités détectées}}</label>
                                    <div class="col-sm-9">
                                        <pre id="daikin_unit_models_display" style="white-space:pre-wrap;"></pre>
                                        <span class="eqLogicAttr" data-l1key="configuration" data-l2key="unitModels" style="display:none;"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">{{Inventaire API (settable / min-max)}}</label>
                                    <div class="col-sm-9">
                                        <pre id="daikin_api_datapoints_display" style="white-space:pre-wrap;max-height:260px;overflow:auto;"></pre>
                                        <span class="eqLogicAttr" data-l1key="configuration" data-l2key="apiDatapointsDetail" style="display:none;"></span>
                                        <small class="help-block">{{Liste des leaves API découvertes avec settable, valueType, values, min/max.}}</small>
                                    </div>
                                </div>
                                <div class="form-group" id="daikin_settable_mismatch_group" style="display:none;">
                                    <label class="col-sm-3 control-label">{{Écarts settable}}</label>
                                    <div class="col-sm-9">
                                        <pre id="daikin_settable_mismatches_display" style="white-space:pre-wrap;max-height:180px;overflow:auto;"></pre>
                                        <span class="eqLogicAttr" data-l1key="configuration" data-l2key="settableMismatches" style="display:none;"></span>
                                        <span class="eqLogicAttr" data-l1key="configuration" data-l2key="settableMismatchesDetail" style="display:none;"></span>
                                        <small class="help-block">{{Datapoints settable côté API mais mappés en lecture seule par le daemon.}}</small>
                                    </div>
                                </div>
                                <div class="form-group" id="daikin_unmapped_group" style="display:none;">
                                    <label class="col-sm-3 control-label">{{Datapoints non mappés}}</label>
                                    <div class="col-sm-9">
                                        <pre class="eqLogicAttr" data-l1key="configuration" data-l2key="unmappedDatapoints" style="white-space:pre-wrap;max-height:120px;overflow:auto;"></pre>
                                    </div>
                                </div>
                                <div class="form-group" id="daikin_unmapped_detail_group" style="display:none;">
                                    <label class="col-sm-3 control-label">{{Détail datapoints non mappés}}</label>
                                    <div class="col-sm-9">
                                        <pre id="daikin_unmapped_detail_display" style="white-space:pre-wrap;max-height:220px;overflow:auto;"></pre>
                                        <span class="eqLogicAttr" data-l1key="configuration" data-l2key="unmappedDatapointsDetail" style="display:none;"></span>
                                    </div>
                                </div>
                                <div class="form-group" id="daikin_debug_report_group" style="display:none;">
                                    <label class="col-sm-3 control-label">{{Rapport de debug}}</label>
                                    <div class="col-sm-9">
                                        <pre id="daikin_debug_report_display" style="white-space:pre-wrap;max-height:310px;overflow:auto;border:1px solid #ccc;padding:8px;background:#fff;"></pre>
                                        <span class="eqLogicAttr" data-l1key="configuration" data-l2key="debugReport" style="display:none;"></span>
                                        <button type="button" class="btn btn-default btn-sm" id="daikin_copy_debug_report" style="margin-top:8px;">
                                            <i class="fas fa-copy"></i> {{Copier le rapport}}
                                        </button>
                                        <small class="help-block">{{Copiez ce rapport dans votre post Community pour aider au support.}}</small>
                                    </div>
                                </div>
                                <div class="form-group" id="daikin_github_issue_group" style="display:none;">
                                    <label class="col-sm-3 control-label">{{Signaler sur GitHub}}</label>
                                    <div class="col-sm-9">
                                        <a id="daikin_github_issue_link" href="#" target="_blank" rel="noopener noreferrer"></a>
                                        <span class="eqLogicAttr" data-l1key="configuration" data-l2key="githubIssueUrl" style="display:none;"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div><!-- /.tabpanel #eqlogictab-->
            <!-- Onglet des commandes de l'équipement -->
            <div role="tabpanel" class="tab-pane" id="commandtab">
                <a class="btn btn-default btn-sm pull-right cmdAction" data-action="add" style="margin-top:5px;"><i class="fas fa-plus-circle"></i> {{Ajouter une commande}}</a>
                <br><br>
                <div class="table-responsive">
                    <table id="table_cmd" class="table table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th class="hidden-xs" style="min-width:50px;width:70px;">ID</th>
                            <th style="min-width:200px;width:350px;">{{Nom}}</th>
                            <th>{{Type}}</th>
                            <th style="min-width:260px;">{{Options}}</th>
                            <th>{{Etat}}</th>
                            <th style="min-width:80px;width:200px;">{{Actions}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div><!-- /.tabpanel #commandtab-->

        </div><!-- /.tab-content -->
    </div><!-- /.eqLogic -->
</div><!-- /.row row-overflow -->

<!-- Inclusion du fichier javascript du plugin (dossier, nom_du_fichier, extension_du_fichier, id_du_plugin) -->
<?php include_file('desktop', 'daikinRCCloud', 'js', 'daikinRCCloud');?>
<!-- Inclusion du fichier javascript du core - NE PAS MODIFIER NI SUPPRIMER -->
<?php include_file('core', 'plugin.template', 'js');?>
