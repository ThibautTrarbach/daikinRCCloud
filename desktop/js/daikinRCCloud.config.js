/* global jeedomUtils */
(function() {
    'use strict';

    var STORAGE_KEY = 'daikinRCCloud_configAdvanced';

    function getConfigRoot() {
        return document.getElementById('div_plugin_configuration') || document.getElementById('div_confPlugin') || document;
    }

    function initTooltips() {
        if (typeof jeedomUtils !== 'undefined' && typeof jeedomUtils.initTooltips === 'function') {
            jeedomUtils.initTooltips(getConfigRoot());
        }
    }

    function parseIntField(root, key, fallback) {
        var input = root.querySelector('.configKey[data-l1key="' + key + '"]');
        if (!input) {
            return fallback;
        }
        var value = parseInt(input.value, 10);
        return isNaN(value) ? fallback : value;
    }

    function isWebSocketEnabled(root) {
        var input = root.querySelector('.configKey[data-l1key="daikin_enableWebSocket"]');
        return !input || input.checked;
    }

    function getAuthMode(root) {
        var authSelect = root.querySelector('#daikin_authMode');
        return authSelect ? authSelect.value : 'developer_portal';
    }

    function getDailyQuotaLimit(authMode) {
        return authMode === 'mobile_app' ? 3000 : 200;
    }

    /**
     * Miroir de daikinRCCloud::computePollingEstimate() / daikintomqtt cron.ts
     */
    function computePollingEstimate(params) {
        var dayInterval = params.dayInterval;
        var nightInterval = params.nightInterval;
        var nightStart = params.nightStart;
        var nightEnd = params.nightEnd;
        var authMode = params.authMode;
        var enableWebSocket = params.enableWebSocket;
        var dailyQuotaLimit = params.dailyQuotaLimit;

        var invalid = dayInterval <= 0 || nightInterval <= 0 || nightStart < 0 || nightStart > 23 || nightEnd < 0 || nightEnd > 23;
        if (invalid) {
            return {
                valid: false,
                pollsDay: 0,
                pollsNight: 0,
                pollsCron: 0,
                energyStats: 1,
                total: dailyQuotaLimit,
                effectiveDayInterval: dayInterval,
                effectiveNightInterval: nightInterval,
                wsSafetyNetApplied: false
            };
        }

        var nightHours;
        if (nightStart > nightEnd) {
            nightHours = (24 - nightStart) + nightEnd;
        } else {
            nightHours = Math.max(0, nightEnd - nightStart);
        }
        var dayHours = 24 - nightHours;

        var effectiveDayInterval = dayInterval;
        var effectiveNightInterval = nightInterval;
        var wsSafetyNetApplied = false;
        if (authMode === 'mobile_app' && enableWebSocket) {
            var newDayInterval = Math.max(dayInterval, 30);
            var newNightInterval = Math.max(nightInterval, 60);
            wsSafetyNetApplied = (newDayInterval !== dayInterval || newNightInterval !== nightInterval);
            effectiveDayInterval = newDayInterval;
            effectiveNightInterval = newNightInterval;
        }

        var pollsDay = Math.ceil(dayHours * 60 / effectiveDayInterval);
        var pollsNight = Math.ceil(nightHours * 60 / effectiveNightInterval);
        var pollsCron = pollsDay + pollsNight;
        var energyStats = 1;

        return {
            valid: true,
            pollsDay: pollsDay,
            pollsNight: pollsNight,
            pollsCron: pollsCron,
            energyStats: energyStats,
            total: pollsCron + energyStats,
            effectiveDayInterval: effectiveDayInterval,
            effectiveNightInterval: effectiveNightInterval,
            wsSafetyNetApplied: wsSafetyNetApplied
        };
    }

    function formatPollingEstimateDetail(estimate) {
        if (!estimate.valid) {
            return 'Intervalles invalides — estimation indisponible';
        }

        var detail = estimate.pollsDay + ' polls jour (intervalle ' + estimate.effectiveDayInterval + ' min) + '
            + estimate.pollsNight + ' polls nuit (intervalle ' + estimate.effectiveNightInterval + ' min) + '
            + estimate.energyStats + ' stats énergie = ' + estimate.total + ' GET planifiés/jour';

        if (estimate.wsSafetyNetApplied) {
            detail += ' — Filet WebSocket actif (Mobile App)';
        }

        detail += '. Hors commandes, refresh post-action et redémarrage du daemon (+1 GET).';

        return detail;
    }

    function updatePollingEstimate() {
        var root = getConfigRoot();
        var authMode = getAuthMode(root);
        var estimate = computePollingEstimate({
            dayInterval: parseIntField(root, 'daikin_polling_dayInterval', 15),
            nightInterval: parseIntField(root, 'daikin_polling_nightInterval', 30),
            nightStart: parseIntField(root, 'daikin_polling_nightStart', 22),
            nightEnd: parseIntField(root, 'daikin_polling_nightEnd', 7),
            authMode: authMode,
            enableWebSocket: isWebSocketEnabled(root),
            dailyQuotaLimit: getDailyQuotaLimit(authMode)
        });

        var totalInput = root.querySelector('.configKey[data-l1key="daikin_totalRqPerDay"]');
        var cronInput = root.querySelector('.configKey[data-l1key="daikin_pollingCronPerDay"]');
        var detailEl = root.querySelector('#daikin-polling-estimate-detail');

        if (totalInput) {
            totalInput.value = String(estimate.total);
        }
        if (cronInput) {
            cronInput.value = String(estimate.pollsCron);
        }
        if (detailEl) {
            detailEl.textContent = formatPollingEstimateDetail(estimate);
        }
    }

    function updateVersionsDisplay() {
        var root = getConfigRoot();
        var pluginVersion = root.querySelector('.configKey[data-l1key="pluginVersion"]');
        var daemonVersion = root.querySelector('.configKey[data-l1key="deamonVersion"]');
        var pluginEl = root.querySelector('.daikin-version-plugin');
        var daemonEl = root.querySelector('.daikin-version-daemon');
        if (pluginEl && pluginVersion) {
            pluginEl.textContent = pluginVersion.value || '—';
        }
        if (daemonEl && daemonVersion) {
            daemonEl.textContent = daemonVersion.value || '—';
        }
    }

    function updateAuthMode() {
        var root = getConfigRoot();
        var authSelect = root.querySelector('#daikin_authMode');
        if (!authSelect) {
            return;
        }
        var isDeveloper = authSelect.value === 'developer_portal';
        var isMobile = authSelect.value === 'mobile_app';

        var developerBlock = root.querySelector('#daikin-auth-developer');
        var mobileBlock = root.querySelector('#daikin-auth-mobile');
        var hintDeveloper = root.querySelector('#daikin-auth-quota-hint-developer');
        var hintMobile = root.querySelector('#daikin-auth-quota-hint-mobile');

        if (developerBlock) {
            developerBlock.style.display = isDeveloper ? '' : 'none';
        }
        if (mobileBlock) {
            mobileBlock.style.display = isMobile ? '' : 'none';
        }
        if (hintDeveloper) {
            hintDeveloper.style.display = isDeveloper ? '' : 'none';
        }
        if (hintMobile) {
            hintMobile.style.display = isMobile ? '' : 'none';
        }

        root.querySelectorAll('.daikin-auth-port').forEach(function(el) {
            el.style.display = isDeveloper ? '' : 'none';
        });

        updatePollingEstimate();
    }

    function updateAdvancedMode() {
        var root = getConfigRoot();
        var toggle = root.querySelector('#daikin_configAdvanced');
        var advancedBlock = root.querySelector('#daikin-config-advanced');
        if (!toggle || !advancedBlock) {
            return;
        }
        advancedBlock.style.display = toggle.checked ? '' : 'none';
        initTooltips();
    }

    function isPollingEstimateField(target) {
        if (!target || !target.getAttribute) {
            return false;
        }
        var key = target.getAttribute('data-l1key');
        return key === 'daikin_polling_dayInterval'
            || key === 'daikin_polling_nightInterval'
            || key === 'daikin_polling_nightStart'
            || key === 'daikin_polling_nightEnd'
            || key === 'daikin_enableWebSocket';
    }

    function daikinRCCloud_initConfigUI() {
        var root = getConfigRoot();
        var toggle = root.querySelector('#daikin_configAdvanced');
        var authSelect = root.querySelector('#daikin_authMode');
        if (!toggle || !authSelect) {
            return false;
        }

        if (toggle.getAttribute('data-daikin-init') !== '1') {
            toggle.setAttribute('data-daikin-init', '1');
            toggle.checked = localStorage.getItem(STORAGE_KEY) === '1';
        }

        updateVersionsDisplay();
        updateAuthMode();
        updateAdvancedMode();
        updatePollingEstimate();
        initTooltips();
        return true;
    }

    function boot(remaining) {
        if (!daikinRCCloud_initConfigUI() && remaining > 0) {
            setTimeout(function() {
                boot(remaining - 1);
            }, 300);
            return;
        }
        updateVersionsDisplay();
        updatePollingEstimate();
    }

    function watchConfigContainer() {
        var container = document.getElementById('div_plugin_configuration');
        if (!container || container.getAttribute('data-daikin-watch') === '1') {
            return;
        }
        container.setAttribute('data-daikin-watch', '1');
        var observer = new MutationObserver(function() {
            setTimeout(function() {
                boot(8);
            }, 100);
        });
        observer.observe(container, { childList: true, subtree: true });
    }

    if (!document.daikinRCCloudConfigBound) {
        document.daikinRCCloudConfigBound = true;

        document.addEventListener('change', function(event) {
            var target = event.target;
            if (!target || !target.id) {
                if (isPollingEstimateField(target)) {
                    updatePollingEstimate();
                }
                return;
            }
            if (target.id === 'daikin_configAdvanced') {
                localStorage.setItem(STORAGE_KEY, target.checked ? '1' : '0');
                updateAdvancedMode();
            }
            if (target.id === 'daikin_authMode') {
                updateAuthMode();
                initTooltips();
            }
            if (isPollingEstimateField(target)) {
                updatePollingEstimate();
            }
        });

        document.addEventListener('input', function(event) {
            if (isPollingEstimateField(event.target)) {
                updatePollingEstimate();
            }
        });

        watchConfigContainer();
    }

    boot(15);
    setTimeout(function() {
        boot(10);
    }, 1000);
    setTimeout(function() {
        boot(5);
    }, 2500);

    window.daikinRCCloud_initConfigUI = daikinRCCloud_initConfigUI;
    window.daikinRCCloud_computePollingEstimate = computePollingEstimate;
})();
