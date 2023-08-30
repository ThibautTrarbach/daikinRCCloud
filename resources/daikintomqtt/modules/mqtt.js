"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.publishToMQTT = exports.loadMQTTClient = void 0;
const mqtt_1 = require("resources/daikintomqtt/modules/mqtt");
async function getOptions() {
    const clientId = `mqtt_${Math.random().toString(16).slice(3)}`;
    let option = {
        clientId,
        clean: true,
        connectTimeout: config.mqtt.connectTimeout,
        username: (config.mqtt.username != null) ? config.mqtt.username : undefined,
        password: (config.mqtt.password != null) ? config.mqtt.password : undefined,
        reconnectPeriod: config.mqtt.reconnectPeriod,
    };
    return option;
}
async function loadMQTTClient() {
    let options = await getOptions();
    const mqttHost = `mqtt://${config.mqtt.host}:${config.mqtt.port}`;
    global.mqttClient = (0, mqtt_1.connect)(mqttHost, options);
    global.cache = {};
}
exports.loadMQTTClient = loadMQTTClient;
async function publishToMQTT(topic, data) {
    if (cache[topic] == data)
        return;
    global.cache[topic] = data;
    mqttClient.publish(config.mqtt.topic + "/" + topic, data, { qos: 0, retain: true }, (error) => {
        logger.debug("Send Data to MQTT : " + topic);
        if (error)
            logger.error(error);
    });
}
exports.publishToMQTT = publishToMQTT;
