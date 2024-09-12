const { client } = require("../../index.js");
const discord = require("discord.js");

function init() {
    client.glddata = new discord.Collection();
}

module.exports = init;