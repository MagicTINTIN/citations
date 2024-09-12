const fs = require('fs');
const path = require('path');
const { client } = require("../../index");
const bot = require("../index")
const debugmsg = require("../../config/admin/debugmsg.json");
const cfgVersion = 1.0;

module.exports = {
    /**
        * Load guild config
        *
        * @param {string} guildid the guild id you want to load config
        * @param {boolean} muted to mute loading messages (default = false)
        */
    ldGldCfg: function (guildid, muted = false) {
        if (client.glddata && client.glddata.get(guildid))
            return client.glddata.get(guildid)
        else if (!client.glddata.get(guildid)) {
            return;
        }
        try { // Loading previous if it exists
            var previousfile = JSON.parse(fs.readFileSync(path.resolve(`./config/servers/${guildid}.json`)));
            if (!muted) bot.log.all(debugmsg.tools.file.caching + " guildCfg : " + guildid)
            client.glddata.set(guildid, previousfile);
            return previousfile;
        } catch (err) {
            if (!muted) bot.log.all(debugmsg.tools.file.nofile + " guildCfg : " + guildid);

            const datetime = Date.now();

            var guildCfgJSON = {
                id: guildid,
                name: bot.base.gld.gname(guildid),
                version: cfgVersion,
                commands: [],
                welcoming: {},
                boosting: {},
                exiting: {},
                counters: {},
                stats: {
                    evolution: {
                        membercount: [{ time: datetime, value: bot.base.gld.gmembercount(guildid) }],
                        msgs: [{ time: datetime, value: 0 }],
                        reacts: [{ time: datetime, value: 0 }]
                    },
                    global: {
                        // less than a day, a week, a month, a year and more than a year
                        agepopulation: { ltd: null, ltw: null, ltm: null, lty: null, mty: null },
                        // stats on 1 month
                        exitingage: {
                            week1: { ltd: 0, ltw: 0, ltm: 0, lty: 0, mty: 0 },
                            week2: { ltd: 0, ltw: 0, ltm: 0, lty: 0, mty: 0 },
                            week3: { ltd: 0, ltw: 0, ltm: 0, lty: 0, mty: 0 },
                            week4: { ltd: 0, ltw: 0, ltm: 0, lty: 0, mty: 0 },
                        }
                    },
                    admininfo: {
                        mutes: [],
                        other: []
                    }
                }
            }
            return guildCfgJSON;
        }

    },
    /**
        * Save guild config
        *
        * @param {string} guildid the guild id you want to save config
        * @param {Object} jsondata the guild id you want to save config
        * @param {boolean} muted to mute saving messages (default = false)
        */
    svGldCfg: function (guildid, jsondata, muted = false) {
        client.glddata.set(guildid, jsondata);
        // export new file
        const jsonStringcfg = JSON.stringify(jsondata);
        fs.writeFile(`./servers/${guildid}.json`, jsonStringcfg, err => {
            if (err) {
                if (!muted) {
                    bot.log.all(debugmsg.tools.file.errsaving + guildid);
                    bot.alert.err(err);
                }
            } else {
                if (!muted) bot.log.all(debugmsg.tools.file.saving + guildid);
            }

        })
    },
}