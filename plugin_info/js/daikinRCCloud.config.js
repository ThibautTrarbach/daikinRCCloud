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
})();
