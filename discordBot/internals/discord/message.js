const { Embed, Attachment, Message, MessageReaction, User } = require("discord.js");
const { client } = require("../../index.js");
const { base } = require("../index");
const fs = require("fs");

module.exports = {
    /**
        * Send a message
        *
        * @param {string|Channel} channel channel destination (id or element)
        * @param {string} content message to send
        * @param {Embed[]} [embeds] embed to send
        * @param {Attachment[]} [attachments] attachment to send 
        */
    sendch: function (channel, content, embeds = null, attachments = null) {

        let chdest = (typeof (channel) == "string") ? base.ch.get(channel) : channel;
        if (chdest == null) {
            console.log("Wrong channel ID");
            return 1;
        }
        if (content.length = 0)
            content = null;
        if (content != null || embeds != null || attachments != null)
            chdest.send({ content: content, files: attachments, embeds: embeds })
    },
    /**
        * Reply to interaction
        *
        * @param {InteractionType} interaction interaction to reply
        * @param {boolean} ephemeral
        * @param {string} content message to send
        * @param {Embed[]} [embeds] embed to send
        * @param {Attachment[]} [attachments] attachment to send 
        */
    reply: function (interaction, ephemeral, content, embeds = null, attachments = null) {
        console.log(content);
        if (content.length = 0)
            content = null;
        if (content != null || embeds != null || attachments != null)
            interaction.reply({ content: content, files: attachments, embeds: embeds, ephemeral: ephemeral });
    },
    /**
        *   
        *
        * @param {Message} message
        */
    onNew: function (message) {
        const time = require("../tools/time.js")
        let updateChannels = ["1163862929129607219", "1163861634398290013", "1163861683513598137", "1163861713343492169", "1163861762685292655"]
        var pattern = /^[A-Za-z]{3}\d{3}$/;
        if (!updateChannels.includes(message.channelId))
            return;
        if (message.content.toLowerCase().startsWith("&stop")) {
            if (global.sessionStartTime > 0) {
                message.reply(`Ending session after ${time.duration(global.sessionStartTime, Date.now())}`);
                global.sessionStartTime = -1;
            }
            else {
                message.reply(`No session were active. Use &start to create one`)
            }
        }
        if (message.content.toLowerCase().startsWith("&start")) {
            global.sessionStartTime = Date.now();
            message.reply(`New session created`)
        }
        if (message.channel.id == "1163862929129607219" || message.author.bot)
            return;
        nbAddedElements = 0;
        for (const platerough of message.content.toUpperCase().split("\n")) {
            plate = platerough;
            platetype = 0;
            newelement = {};
            valid = true;
            if (platerough.endsWith("BUS")) {
                plate = platerough.slice(0, -3);
                platetype = 1;
            }
            else if (platerough.endsWith("P")) {
                plate = platerough.slice(0, -1);
                platetype = 2;
            }
            else if (platerough.endsWith("BOLT")) {
                plate = platerough.slice(0, -4);
                platetype = 3;
            }
            if (plate.length != 6 || !pattern.test(plate)) {
                valid = false;
                message.channel.send(`${plate} is not a valid car plat (must be 3 letters followed by 3 numbers)`);
                continue;
            }
            
            if (message.channel.id == "1163861634398290013" && platetype == 0)
                newelement = {
                    name: plate,
                    isBus: false,
                    isParked: false,
                    isBolt: false
                };
            else if (message.channel.id == "1163861683513598137" || platetype == 1)
                newelement = {
                    name: plate,
                    isBus: true,
                    isParked: false,
                    isBolt: false
                };
            else if (message.channel.id == "1163861713343492169" || platetype == 2)
                newelement = {
                    name: plate,
                    isBus: false,
                    isParked: true,
                    isBolt: false
                };
            else if (message.channel.id == "1163861762685292655" || platetype == 3)
                newelement = {
                    name: plate,
                    isBus: false,
                    isParked: false,
                    isBolt: true
                };
            if (newelement.name) {
                global.carplates.push(newelement);
                nbAddedElements++;
            }
        }

        if (nbAddedElements > 0)
            fs.writeFile("./carplates.json", JSON.stringify(global.carplates, null, 2), err => {

                // Checking for errors 
                if (err) throw err;

                console.log("Updated"); // Success
                message.react("✅")
            });
    },
    /**
        *   
        *
        * @param {Message} message
        */
    onDel: function (message) {

    },
    /**
        *   
        *
        * @param {Message} oldmsg
        * @param {Message} newmsg
        */
    onUpdt: function (oldmsg, newmsg) {

    },
    /**
        *   
        *
        * @param {MessageReaction} reaction
        * @param {User} user
        */
    onReactAdd: function (reaction, user) {

    },
    /**
        *   
        *
        * @param {MessageReaction} reaction
        * @param {User} user
        */
    onReactRem: function (reaction, user) {

    },
};

// penser à securiser l'envoi de message !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!