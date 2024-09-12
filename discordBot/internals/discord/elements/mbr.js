const { GuildMember } = require("discord.js");
const { client } = require("../../../index.js");

module.exports = {
    /**
        * Get member element by id
        *
        * @param {string} guildid guild id the member belongs
        * @param {string} memberid user id of the member
        */
    get: function (guildid, memberid) {
        return client.guilds.cache.get(guildid).members.cache.get(memberid);
    },
    /**
        * Get member nickname by id
        *
        * @param {string} guildid guild id the member belongs
        * @param {string} memberid user id of the member
        */
    gnickname: function (guildid, memberid) {
        return this.get(guildid, memberid).displayName;
    },
    /**
        * Get member hexadecimal color by id
        *
        * @param {string} guildid guild id the member belongs
        * @param {string} memberid user id of the member
        */
    gcolor: function (guildid, memberid) {
        return this.get(guildid, memberid).displayHexColor;
    },


    // classical way (with Member Object)


    /**
        * Get member nickname
        *
        * @param {GuildMember} member
        */
    nickname: function (member) {
        return member.displayName;
    },
    /**
        * Get member hexadecimal color
        *
        * @param {GuildMember} member
        */
    color: function (member) {
        return member.displayHexColor;
    },
};  