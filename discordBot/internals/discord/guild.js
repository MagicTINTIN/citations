const { GuildMember, GuildAuditLogsEntry } = require("discord.js");

module.exports = {
    /**
        *   
        *
        * @param {GuildMember} member member who joined
        */
    onMemberJoin: function (member) {

    },
    /**
        *   
        *
        * @param {GuildMember} member member who joined
        */
    onMemberLeave: function (member) {

    },
    /**
        *   
        *
        * @param {GuildMember} guild
        * @param {GuildMember} user banned user
        * @param {GuildMember} reason
        */
    onMemberBan: function (guild, user, reason = null) {

    },
    /**
        *   
        *
        * @param {GuildMember} guild
        * @param {GuildMember} user unbanned user
        * @param {GuildMember} reason
        */
    onMemberUnban: function (guild, user, reason = null) {

    },

    /**
        *   
        *
        * @param {GuildAuditLogsEntry} audit
        * @param {Guild} guild
        */
    onAuditLog: function (audit, guild) {

    },
}
