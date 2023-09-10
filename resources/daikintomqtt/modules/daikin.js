"use strict";
var __createBinding = (this && this.__createBinding) || (Object.create ? (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    var desc = Object.getOwnPropertyDescriptor(m, k);
    if (!desc || ("get" in desc ? !m.__esModule : desc.writable || desc.configurable)) {
      desc = { enumerable: true, get: function() { return m[k]; } };
    }
    Object.defineProperty(o, k2, desc);
}) : (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    o[k2] = m[k];
}));
var __setModuleDefault = (this && this.__setModuleDefault) || (Object.create ? (function(o, v) {
    Object.defineProperty(o, "default", { enumerable: true, value: v });
}) : function(o, v) {
    o["default"] = v;
});
var __importStar = (this && this.__importStar) || function (mod) {
    if (mod && mod.__esModule) return mod;
    var result = {};
    if (mod != null) for (var k in mod) if (k !== "default" && Object.prototype.hasOwnProperty.call(mod, k)) __createBinding(result, mod, k);
    __setModuleDefault(result, mod);
    return result;
};
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.sendDevice = exports.generateConfig = exports.subscribeDevices = exports.loadDaikinAPI = void 0;
const daikin_controller_cloud_1 = __importDefault(require("daikin-controller-cloud"));
const ip_1 = __importDefault(require("ip"));
const path_1 = __importDefault(require("path"));
const fs_1 = __importDefault(require("fs"));
const gateway_1 = require("./gateway");
const converter_1 = require("./converter");
const mqtt_1 = require("./mqtt");
const Process = __importStar(require("process"));
async function getOptions() {
    return {
        logger: null,
        logLevel: config.system.logLevel,
        proxyOwnIp: ip_1.default.address(),
        proxyPort: config.daikin.proxyPort,
        proxyWebPort: config.daikin.proxyWebPort,
        proxyListenBind: '0.0.0.0',
        proxyDataDir: datadir,
        communicationTimeout: config.daikin.communicationTimeout,
        communicationRetries: config.daikin.communicationRetries
    };
}
async function loadDaikinAPI() {
    let startError = false;
    const tokenFile = path_1.default.join(datadir, '/tokenset.json');
    let daikinOptions = await getOptions();
    if (fs_1.default.existsSync(tokenFile))
        global.daikinToken = JSON.parse(fs_1.default.readFileSync(tokenFile).toString());
    else
        global.daikinToken = undefined;
    let daikinClient = new daikin_controller_cloud_1.default(daikinToken, daikinOptions);
    daikinClient.on('token_update', (tokenSet) => {
        fs_1.default.writeFileSync(tokenFile, JSON.stringify(tokenSet));
    });
    try {
        await daikinClient.getCloudDeviceDetails();
    }
    catch (e) {
        startError = true;
    }
    if (daikinToken == undefined || startError) {
        try {
            if (config.daikin.modeProxy) {
                await daikinClient.initProxyServer();
            }
            else {
                await daikinClient.login(config.daikin.username, config.daikin.password);
            }
            global.daikinToken = JSON.parse(fs_1.default.readFileSync(tokenFile).toString());
            logger.debug('Use Token with the following claims: ' + JSON.stringify(daikinClient.getTokenSet().claims()));
        }
        catch (e) {
            let error = e.toString();
            logger.error("Error to connect to Daikin Cloud");
            logger.error(error);
            await (0, mqtt_1.publishStatus)(false, true, error);
            await timeout(10000);
            Process.exit(2);
        }
    }
    global.daikinClient = daikinClient;
}
exports.loadDaikinAPI = loadDaikinAPI;
async function subscribeDevices() {
    const devices = await daikinClient.getCloudDevices();
    for (let dev of devices) {
        let subscribeTopic = config.mqtt.topic + "/" + dev.getId() + "/set";
        mqttClient.subscribe(subscribeTopic, function (err) {
            if (!err)
                logger.info("Subscribe to " + subscribeTopic);
        });
    }
    mqttClient.on('message', async function (topic, message) {
        logger.debug(`Topic : ${topic} \n- Message : ${message.toString()}`);
        const devices = await daikinClient.getCloudDevices();
        for (let dev of devices) {
            if (!topic.toString().includes(dev.getId()))
                continue;
            let gateway = getModels(dev);
            if (gateway !== undefined) {
                await (0, gateway_1.eventValue)(dev, gateway, JSON.parse(message.toString()));
            }
        }
    });
}
exports.subscribeDevices = subscribeDevices;
async function sendDevice() {
    const devices = await daikinClient.getCloudDevices();
    if (devices && devices.length) {
        for (let dev of devices) {
            let gateway = getModels(dev);
            await (0, mqtt_1.publishToMQTT)(dev.getId(), JSON.stringify(gateway));
        }
    }
    return devices;
}
exports.sendDevice = sendDevice;
function getModels(devices) {
    let value;
    if (devices.getData('gateway', 'modelInfo') !== null)
        value = devices.getData('gateway', 'modelInfo').value;
    else if (devices.getData('0', 'modelInfo') !== null)
        value = devices.getData('0', 'modelInfo').value;
    switch (value) {
        case 'BRP069C4x':
            return new gateway_1.BRP069C4x(devices);
        case 'BRP069A62':
            return new gateway_1.BRP069A62(devices);
        case 'BRP069A78':
            return new gateway_1.BRP069A78(devices);
        case 'BRP069B4x':
            return new gateway_1.BRP069B4x(devices);
        case 'BRP069A4x':
            return new gateway_1.BRP069A4x(devices);
        case 'BRP069A61':
            return new gateway_1.BRP069A61(devices);
        case 'BRP069C41':
            return new gateway_1.BRP069C41(devices);
        case 'BRP069C8x':
            return new gateway_1.BRP069C8x(devices);
        default:
            (0, gateway_1.anonymise)(devices, value);
            return undefined;
    }
}
async function generateConfig() {
    const devices = await daikinClient.getCloudDevices();
    if (devices && devices.length) {
        for (let dev of devices) {
            let module = getModels(dev);
            if (module)
                await (0, converter_1.makeDefineFile)(module);
        }
    }
}
exports.generateConfig = generateConfig;
function timeout(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
