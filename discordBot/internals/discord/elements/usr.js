const { User } = require("discord.js");
const { client } = require("../../../index.js");

module.exports = {
    /**
        * Get user element by id
        *
        * @param {string} userid user to get by id
        */
    get: function (userid) {
        return client.users.cache.get(userid);
    },
    /**
        * Get user name by id
        *
        * @param {string} userid
        */
    gtag: function (userid) {
        return this.get(userid).tag;
    },


    // classical way


    /**
        * Get user name
        *
        * @param {User} user
        */
    tag: function (user) {
        return user.tag;
    },
};  