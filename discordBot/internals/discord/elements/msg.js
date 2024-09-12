const { Message } = require("discord.js");
const { client } = require("../../../index.js");

module.exports = {
    /**
        * Get message element by id
        *
        * @param {string} channelid channel id the message belongs
        * @param {string} messageid id of the message
        */
    get: function (channelid, messageid) {
        return client.channels.cache.get(channelid).messages.fetch(messageid);
    },
    /**
        * Get message content by id
        *
        * @param {string} channelid channel id the message belongs
        * @param {string} messageid id of the message
        */
    gcontent: function (channelid, messageid) {
        return this.get(channelid, messageid).content;
    },
    /**
        * Get message author by id
        *
        * @param {string} channelid channel id the message belongs
        * @param {string} messageid id of the message
        */
    gauthor: function (channelid, messageid) {
        return this.get(channelid, messageid).author;
    },


    // classical way (with message Object)


    /**
        * Get message content
        *
        * @param {Message} message
        */
    content: function (message) {
        return message.content;
    },
    /**
        * Get message author
        *
        * @param {Message} message
        */
    author: function (message) {
        return message.author;
    },
};  