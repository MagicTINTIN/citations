const startingts = Date.now();
const debugmsg = require("./config/admin/debugmsg.json");
const commsg = require("./config/public/common.json");

console.log(debugmsg.init.startInitMsg);
let initialized = false;
// to enable all logs set to 0 to disable all set to 1
const debugmode = 1;

// Import configurations
const initcfg = require("./config/admin/init.json");
const cfg = require("./config/admin/config.json");

const { Client, Partials, MessageReaction } = require('discord.js');
// Client creation and export
const client = new Client({
    intents: initcfg.intents,
    partials: initcfg.partials
});
exports.client = client;

// Import librairies
const bot = require("./internals");
const { tests } = require("./internals");

// Prevents bot from crash
process.on('uncaughtException', function (err) {
    bot.alert.err(err, initialized);
});

// Prevents bot from crash
process.on('SIGINT', function (code) {
    durationwork = bot.time.duration(startingts, Date.now());
    bot.base.clt.setStatus(`${commsg.status.exiting} ${durationwork}`, 4, 4);
    bot.log.all(debugmsg.stop.durationmsg + durationwork, true, "", initialized);
    bot.log.all(debugmsg.stop.exitingmsg + code, true, "**", initialized);

    setTimeout(() => {
        process.exit(0);
    }, 5000);
});

// Load plate vars
global.carplates = require("./carplates.json");
global.sessionStartTime = -1;

// --- BOT EVENTS SECTION --- \\

// when Immatricat logged in Discord
client.once('ready', () => {
    initialized = true;
    bot.log.ch(debugmsg.init.startInitMsg, true, "**");
    bot.log.all(bot.importercount(bot), true);
    bot.init();
    bot.log.all(bot.base.clt.lstsrv(), true);
    bot.base.clt.setStatus(commsg.status.starting, 4, 1);
    bot.log.all(debugmsg.init.endInitMsg, true, "**");

});

client.on('debug', async info => {
    if (debugmode != 1)
        bot.alert.debug(info, initialized);
});

client.on('error', async error => {
    bot.alert.err(error, initialized);
});

client.on('warn', async warning => {
    bot.alert.warn(warning, initialized);
});

// triggered when bot joins a new server
client.on('guildCreate', async guild => {
    if (guild.id in cfg.mutedservers) return;
    //not implemanted yet
});



// --- GUILD EVENTS SECTION --- \\

// triggered when bot joins a new server
client.on('guildUpdate', async (oldGuild, newGuild) => {
    if (newGuild.id in cfg.mutedservers) return;
    //not implemanted yet
});



// --- MESSAGE EVENTS SECTION --- \\

client.on('messageCreate', message => {
    if (message.content.startsWith("tagueule"))
        message.channel.send("\u200B");
    if (message.guild.id in cfg.mutedservers) return;
    //tests.msg(message);
    bot.dscrd.message.onNew(message);
});

client.on('messageUpdate', (oldMessage, newMessage) => {
    if (newMessage.guild.id in cfg.mutedservers) return;
    bot.dscrd.message.onUpdt(oldMessage, newMessage);
});

client.on('messageDelete', message => {
    if (message.guild.id in cfg.mutedservers) return;
    bot.dscrd.message.onDel(message);
});

client.login(process.env.TOKEN);