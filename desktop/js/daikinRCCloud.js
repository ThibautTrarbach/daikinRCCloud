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

/* Fonction permettant l'affichage des commandes dans l'équipement */
function addCmdToTable(_cmd) {
  if (!isset(_cmd)) {
    _cmd = { configuration: {} }
  }
  if (!isset(_cmd.configuration)) {
    _cmd.configuration = {}
  }
  let tr = '<tr>'
  tr += '<td class="hidden-xs">'
  tr += '<span class="cmdAttr" data-l1key="id"></span>'
  tr += '</td>'
  tr += '<td>'
  tr += '<div class="input-group">'
  tr += '<input class="cmdAttr form-control input-sm roundedLeft" data-l1key="name" placeholder="{{Nom de la commande}}">'
  tr += '<span class="input-group-btn"><a class="cmdAction btn btn-sm btn-default" data-l1key="chooseIcon" title="{{Choisir une icône}}"><i class="fas fa-icons"></i></a></span>'
  tr += '<span class="cmdAttr input-group-addon roundedRight" data-l1key="display" data-l2key="icon" style="font-size:19px;padding:0 5px 0 0!important;"></span>'
  tr += '</div>'
  tr += '<select class="cmdAttr form-control input-sm" data-l1key="value" style="display:none;margin-top:5px;" title="{{Commande info liée}}">'
  tr += '<option value="">{{Aucune}}</option>'
  tr += '</select>'
  tr += '</td>'
  tr += '<td>'
  tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>'
  tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>'
  tr += '</td>'
  tr += '<td>'
  tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="isVisible" checked/>{{Afficher}}</label> '
  tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="isHistorized" checked/>{{Historiser}}</label> '
  tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="invertBinary"/>{{Inverser}}</label> '
  tr += '<div style="margin-top:7px;">'
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="width:30%;max-width:80px;display:inline-block;margin-right:2px;">'
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="width:30%;max-width:80px;display:inline-block;margin-right:2px;">'
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="unite" placeholder="{{Unité}}" title="{{Unité}}" style="width:30%;max-width:80px;display:inline-block;margin-right:2px;">'
  tr += '</div>'
  tr += '</td>'
  tr += '<td>'
  tr += '<span class="cmdAttr" data-l1key="htmlstate"></span>'
  tr += '</td>'
  tr += '<td>'
  if (is_numeric(_cmd.id)) {
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> '
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> Tester</a>'
  }
  tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove" title="{{Supprimer la commande}}"></i></td>'
  tr += '</tr>'

  const newRow = document.createElement('tr')
  newRow.innerHTML = tr
  newRow.addClass('cmd')
  newRow.setAttribute('data-cmd_id', init(_cmd.id))
  document.getElementById('table_cmd').querySelector('tbody').appendChild(newRow)

  jeedom.eqLogic.buildSelectCmd({
    id: document.querySelector('.eqLogicAttr[data-l1key="id"]').jeeValue(),
    filter: { type: 'info' },
    error: function(error) {
      jeedomUtils.showAlert({ message: error.message, level: 'danger' })
    },
    success: function(result) {
      newRow.querySelector('.cmdAttr[data-l1key="value"]').insertAdjacentHTML('beforeend', result)
      newRow.setJeeValues(_cmd, '.cmdAttr')
      jeedom.cmd.changeType(newRow, init(_cmd.subType))
    }
  })
}

document.getElementById('div_pageContainer').addEventListener('click', function(event) {
  const target = event.target.closest('.eqLogicAction[data-action="createCommunityPost"]')
  if (!target) return
  jeedom.plugin.createCommunityPost({
    type: eqType,
    error: function(error) {
      jeedomUtils.showAlert({ message: error.message, level: 'danger' })
    },
    success: function(data) {
      const link = document.createElement('a')
      link.href = data.url
      link.target = '_blank'
      link.style.display = 'none'
      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)
    }
  })
})

function readEqConfig(key) {
  const el = document.querySelector('.eqLogicAttr[data-l1key="configuration"][data-l2key="' + key + '"]')
  if (!el) return ''
  if (typeof el.jeeValue === 'function') {
    return el.jeeValue() || ''
  }
  return el.value !== undefined ? el.value : (el.textContent || '')
}

function formatJsonConfig(raw) {
  if (!raw) return ''
  try {
    return JSON.stringify(JSON.parse(raw), null, 2)
  } catch (e) {
    return raw
  }
}

function updateDaikinSupportUi() {
  const supportStatus = readEqConfig('supportStatus') || 'full'
  const configCoverage = readEqConfig('configCoverage') || 'complete'
  const settableMismatches = readEqConfig('settableMismatches') || ''
  const unmappedDatapoints = readEqConfig('unmappedDatapoints') || ''
  const needsReporting = (supportStatus !== 'full') || (configCoverage === 'incomplete') || !!settableMismatches || !!unmappedDatapoints
  const alertBox = document.getElementById('daikin_support_alert')
  const alertInner = document.getElementById('daikin_support_alert_box')
  const alertTitle = document.getElementById('daikin_support_alert_title')
  const alertMessage = document.getElementById('daikin_support_alert_message')
  const debugPanel = document.getElementById('daikin_support_debug')
  const unitModelsDisplay = document.getElementById('daikin_unit_models_display')
  const apiDatapointsDisplay = document.getElementById('daikin_api_datapoints_display')
  const settableMismatchGroup = document.getElementById('daikin_settable_mismatch_group')
  const settableMismatchesDisplay = document.getElementById('daikin_settable_mismatches_display')
  const unmappedGroup = document.getElementById('daikin_unmapped_group')
  const unmappedDetailGroup = document.getElementById('daikin_unmapped_detail_group')
  const unmappedDetailDisplay = document.getElementById('daikin_unmapped_detail_display')
  const supportMessageGroup = document.getElementById('daikin_support_message_group')
  const supportMessageDisplay = document.getElementById('daikin_support_message_display')
  const debugReportGroup = document.getElementById('daikin_debug_report_group')
  const debugReportDisplay = document.getElementById('daikin_debug_report_display')
  const githubIssueGroup = document.getElementById('daikin_github_issue_group')
  const githubIssueLink = document.getElementById('daikin_github_issue_link')

  if (!alertBox || !debugPanel) return

  debugPanel.style.display = needsReporting ? 'block' : 'none'

  if (!needsReporting) {
    alertBox.style.display = 'none'
  } else {
    alertBox.style.display = 'block'
    alertInner.className = 'alert'

    if (supportStatus === 'unsupported') {
      alertInner.classList.add('alert-danger')
      alertTitle.textContent = 'Appareil non supporté'
      alertMessage.textContent = 'Cet appareil n\'est pas pilotable. Créez un post sur la communauté Jeedom avec le rapport de debug ci-dessous.'
    } else if (supportStatus === 'partial') {
      alertInner.classList.add('alert-warning')
      alertTitle.textContent = 'Support partiel'
      alertMessage.textContent = 'Cet appareil utilise un mapping automatique. Créez un post sur la communauté Jeedom pour améliorer la prise en charge.'
    } else if (settableMismatches && !unmappedDatapoints) {
      alertInner.classList.add('alert-warning')
      alertTitle.textContent = 'Écarts settable détectés'
      alertMessage.textContent = 'Certains datapoints sont settable côté API mais mappés en lecture seule. Consultez le détail ci-dessous.'
    } else {
      alertInner.classList.add('alert-warning')
      alertTitle.textContent = 'Configuration incomplète'
      alertMessage.textContent = 'La configuration statique ne couvre pas tous les datapoints API. Signalez-le sur la communauté Jeedom avec le rapport de debug.'
    }
  }

  const supportMessage = readEqConfig('supportMessage')
  if (supportMessageGroup && supportMessageDisplay) {
    if (supportMessage) {
      supportMessageGroup.style.display = 'block'
      supportMessageDisplay.textContent = supportMessage
    } else {
      supportMessageGroup.style.display = 'none'
      supportMessageDisplay.textContent = ''
    }
  }

  if (unitModelsDisplay) {
    unitModelsDisplay.textContent = formatJsonConfig(readEqConfig('unitModels'))
  }

  if (apiDatapointsDisplay) {
    apiDatapointsDisplay.textContent = formatJsonConfig(readEqConfig('apiDatapointsDetail'))
  }

  if (settableMismatchGroup && settableMismatchesDisplay) {
    const detail = readEqConfig('settableMismatchesDetail') || settableMismatches
    if (detail) {
      settableMismatchGroup.style.display = 'block'
      settableMismatchesDisplay.textContent = formatJsonConfig(detail) || detail
    } else {
      settableMismatchGroup.style.display = 'none'
      settableMismatchesDisplay.textContent = ''
    }
  }

  if (unmappedGroup) {
    unmappedGroup.style.display = unmappedDatapoints ? 'block' : 'none'
  }
  if (unmappedDetailGroup && unmappedDetailDisplay) {
    const detail = readEqConfig('unmappedDatapointsDetail')
    if (detail) {
      unmappedDetailGroup.style.display = 'block'
      unmappedDetailDisplay.textContent = formatJsonConfig(detail)
    } else {
      unmappedDetailGroup.style.display = 'none'
      unmappedDetailDisplay.textContent = ''
    }
  }

  const debugReport = readEqConfig('debugReport')
  if (debugReportGroup && debugReportDisplay) {
    if (debugReport) {
      debugReportGroup.style.display = 'block'
      debugReportDisplay.textContent = debugReport
    } else {
      debugReportGroup.style.display = 'none'
      debugReportDisplay.textContent = ''
    }
  }

  const githubIssueUrl = readEqConfig('githubIssueUrl')
  if (githubIssueGroup && githubIssueLink) {
    if (needsReporting && githubIssueUrl) {
      githubIssueGroup.style.display = 'block'
      githubIssueLink.href = githubIssueUrl
      githubIssueLink.textContent = githubIssueUrl
    } else {
      githubIssueGroup.style.display = 'none'
      githubIssueLink.href = '#'
      githubIssueLink.textContent = ''
    }
  }
}

document.getElementById('div_pageContainer').addEventListener('click', function(event) {
  if (event.target.closest('.eqLogicDisplayCard')) {
    setTimeout(updateDaikinSupportUi, 400)
  }
})

if (typeof jeedom !== 'undefined' && jeedom.eqLogic && typeof jeedom.eqLogic.print === 'function') {
  const originalPrint = jeedom.eqLogic.print
  jeedom.eqLogic.print = function() {
    const result = originalPrint.apply(this, arguments)
    setTimeout(updateDaikinSupportUi, 400)
    return result
  }
}

document.getElementById('div_pageContainer').addEventListener('click', function(event) {
  const copyButton = event.target.closest('#daikin_copy_debug_report')
  if (!copyButton) return

  const report = readEqConfig('debugReport')
  if (!report) {
    jeedomUtils.showAlert({ message: 'Aucun rapport de debug disponible.', level: 'warning' })
    return
  }

  if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
    navigator.clipboard.writeText(report).then(function() {
      jeedomUtils.showAlert({ message: 'Rapport de debug copié dans le presse-papiers.', level: 'success' })
    }).catch(function() {
      jeedomUtils.showAlert({ message: 'Impossible de copier le rapport de debug.', level: 'danger' })
    })
    return
  }

  const textarea = document.createElement('textarea')
  textarea.value = report
  textarea.style.position = 'fixed'
  textarea.style.left = '-9999px'
  document.body.appendChild(textarea)
  textarea.select()
  try {
    document.execCommand('copy')
    jeedomUtils.showAlert({ message: 'Rapport de debug copié dans le presse-papiers.', level: 'success' })
  } catch (e) {
    jeedomUtils.showAlert({ message: 'Impossible de copier le rapport de debug.', level: 'danger' })
  }
  document.body.removeChild(textarea)
})
