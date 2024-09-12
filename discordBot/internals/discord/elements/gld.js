const { Guild } = require("discord.js");
const { client } = require("../../../index.js");

module.exports = {
    /**
        * Get guild element by id
        *
        * @param {string} guildid guild to get by id
        */
    get: function (guildid) {
        return client.guilds.cache.get(guildid);
    },
    /**
        * Get guild name by id
        *
        * @param {string} guildid
        */
    gname: function (guildid) {
        return this.get(guildid).name;
    },
    /**
        * Get guild member count by id
        *
        * @param {string} guildid
        */
    gmembercount: function (guildid) {
        return this.get(guildid).memberCount;
    },


    // classical way


    /**
        * Get guild name
        *
        * @param {Guild} guild
        */
    name: function (guild) {
        return guild.name;
    },
    /**
        * Get guild member count
        *
        * @param {Guild} guild
        */
    membercount: function (guild) {
        return guild.memberCount;
    }
};  