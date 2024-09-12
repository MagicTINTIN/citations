const { BaseInteraction } = require("discord.js");

module.exports = {
    /**
            * Get interaction member
            *
            * @param {BaseInteraction} interaction
            */
    member: function (interaction) {
        return interaction.member
    },
    /**
            * Get interaction user
            *
            * @param {BaseInteraction} interaction
            */
    user: function (interaction) {
        return interaction.user
    },
    /**
            * Get interaction type
            *
            * @param {BaseInteraction} interaction
            */
    type: function (interaction) {
        if (interaction.type == 1)
            return "PING";
        else if (interaction.type == 2)
            return "APPLICATION_COMMAND";
        else if (interaction.type == 3)
            return "MESSAGE_COMPONENT";
        else if (interaction.type == 4)
            return "APPLICATION_COMMAND_AUTOCOMPLETE";
        else if (interaction.type == 5)
            return "MODAL_SUBMIT";
        else
            return "UNKNOWN";
    },

}