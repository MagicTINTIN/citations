const { MessageReaction } = require("discord.js");

module.exports = {
    /**
            * Get reaction message
            *
            * @param {MessageReaction} reaction
            */
    message: function (reaction) {
        return reaction.message
    },
    /**
            * Get interaction user
            *
            * @param {MessageReaction} reaction
            */
    emoji: function (reaction) {
        return reaction.emoji
    },

}