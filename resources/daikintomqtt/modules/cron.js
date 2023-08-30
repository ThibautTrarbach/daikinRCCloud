"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.loadCron = void 0;
const node_cron_1 = __importDefault(require("node-cron"));
const daikin_1 = require("./daikin");
async function loadCron() {
    node_cron_1.default.schedule('*/5 * * * * *', async function () {
        logger.debug("Run Polling Daikin");
        await (0, daikin_1.sendDevice)();
    });
}
exports.loadCron = loadCron;
