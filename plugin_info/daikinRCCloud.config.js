(function() {
    'use strict';

    var STORAGE_KEY = 'daikinRCCloud_configAdvanced';
    var initialized = false;

    function initTooltips() {
        if (typeof jeedomUtils !== 'undefined' && typeof jeedomUtils.initTooltips === 'function') {
            jeedomUtils.initTooltips(document.getElementById('div_confPlugin'));
        }
    }

    function updateVersionsDisplay() {
        var pluginVersion = document.querySelector('.configKey[data-l1key="pluginVersion"]');
        var daemonVersion = document.querySelector('.configKey[data-l1key="deamonVersion"]');
        var pluginEl = document.querySelector('.daikin-version-plugin');
        var daemonEl = document.querySelector('.daikin-version-daemon');
        if (pluginEl && pluginVersion) {
            pluginEl.textContent = pluginVersion.value || '—';
        }
        if (daemonEl && daemonVersion) {
            daemonEl.textContent = daemonVersion.value || '—';
        }
    }

    function updateAuthMode() {
        var authSelect = document.getElementById('daikin_authMode');
        if (!authSelect) {
            return;
        }
        var mode = authSelect.value;
        var isDeveloper = mode === 'developer_portal';
        var isMobile = mode === 'mobile_app';

        var developerBlock = document.getElementById('daikin-auth-developer');
        var mobileBlock = document.getElementById('daikin-auth-mobile');
        var hintDeveloper = document.getElementById('daikin-auth-quota-hint-developer');
        var hintMobile = document.getElementById('daikin-auth-quota-hint-mobile');

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

        document.querySelectorAll('.daikin-auth-port').forEach(function(el) {
            el.style.display = isDeveloper ? '' : 'none';
        });
    }

    function updateAdvancedMode() {
        var toggle = document.getElementById('daikin_configAdvanced');
        var advancedBlock = document.getElementById('daikin-config-advanced');
        if (!toggle || !advancedBlock) {
            return;
        }
        advancedBlock.style.display = toggle.checked ? '' : 'none';
        initTooltips();
    }

    function daikinRCCloud_initConfigUI() {
        var toggle = document.getElementById('daikin_configAdvanced');
        var authSelect = document.getElementById('daikin_authMode');
        if (!toggle || !authSelect) {
            return;
        }

        if (!initialized) {
            initialized = true;
            toggle.checked = localStorage.getItem(STORAGE_KEY) === '1';
            toggle.addEventListener('change', function() {
                localStorage.setItem(STORAGE_KEY, this.checked ? '1' : '0');
                updateAdvancedMode();
            });

            authSelect.addEventListener('change', function() {
                updateAuthMode();
                initTooltips();
            });
        }

        updateVersionsDisplay();
        updateAuthMode();
        updateAdvancedMode();
        initTooltips();
    }

    function daikinRCCloud_watchConfigLoad() {
        var pluginVersion = document.querySelector('.configKey[data-l1key="pluginVersion"]');
        if (!pluginVersion) {
            return;
        }
        var observer = new MutationObserver(function() {
            updateVersionsDisplay();
            daikinRCCloud_initConfigUI();
        });
        observer.observe(pluginVersion, { attributes: true, attributeFilter: ['value'] });
        pluginVersion.addEventListener('change', updateVersionsDisplay);
        pluginVersion.addEventListener('input', updateVersionsDisplay);
    }

    if (typeof jQuery !== 'undefined') {
        jQuery(document).ready(function() {
            setTimeout(function() {
                daikinRCCloud_watchConfigLoad();
                daikinRCCloud_initConfigUI();
            }, 300);
            setTimeout(daikinRCCloud_initConfigUI, 1000);
            setTimeout(daikinRCCloud_initConfigUI, 2500);
        });
    } else {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                daikinRCCloud_watchConfigLoad();
                daikinRCCloud_initConfigUI();
            }, 300);
            setTimeout(daikinRCCloud_initConfigUI, 1000);
            setTimeout(daikinRCCloud_initConfigUI, 2500);
        });
    }

    window.daikinRCCloud_initConfigUI = daikinRCCloud_initConfigUI;
})();
