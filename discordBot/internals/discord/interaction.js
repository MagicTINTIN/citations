const { BaseInteraction } = require("discord.js");

module.exports = {
    /**
        *   
        *
        * @param {BaseInteraction} interaction interaction element
        */
    on: function (interaction) {
        
        if (mutedservers.includes(interaction.guild.id) || !interaction.isCommand()) return;
    }
}
