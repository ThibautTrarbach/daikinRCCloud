"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const modules_1 = require("./modules");
const cron_1 = require("./modules/cron");
async function main() {
    global.datadir = process.env.STORE_DIR || process.cwd() + "/config";
    global.logger = (0, modules_1.loadLogger)();
    console.info("Starting DaikinToMQTT");
    logger.info("=> Load configuration");
    await (0, modules_1.loadGlobalConfig)();
    logger.info("=> Connect to MQTT");
    await (0, modules_1.loadMQTTClient)();
    logger.info("=> Connect to Daikin");
    await (0, modules_1.loadDaikinAPI)();
    logger.info("=> Subscribe to MQTT Action");
    await (0, modules_1.subscribeDevices)();
    logger.info("DaikinToMQTT Started !!");
    logger.info("Generate Config Info");
    await (0, modules_1.generateConfig)();
    logger.info("Load Polling Daikin");
    await (0, cron_1.loadCron)();
    logger.info("Send First Event Data Value");
    await (0, modules_1.sendDevice)();
}
main().then();
