const debugmsg = require("../../config/admin/debugmsg.json")
const log = require("../tools/logger")

module.exports = {
    /**
        * Print error in terminal and bot channel
        *
        * @param {Error|string} err error to print
        * @param {boolean} initialized if bot is initialised or not (default = true)
        */
    err: function (err, initialized = true) {


        console.error(err);
        console.log(debugmsg.errors.mainMsg);
        if (initialized && typeof (err) == 'string') return log.ch(debugmsg.errors.important + "\n```diff\n-> " + err + "\n```");
        if (initialized && typeof (err) != 'string')
            try {
                log.ch(debugmsg.errors.mainMsg);
                errorwide = err.stack
                messerror = errorwide.match(/(.{1,1800})/g);
                for (const msgparterror of messerror) {
                    log.ch("```diff\n-> " + msgparterror + "\n```");
                }
            } catch (error) {
                console.error(error);
            }
    },

    /**
        * Print warn in terminal and bot channel
        *
        * @param {string} warning warn to print
        * @param {boolean} initialized if bot is initialised or not (default = true)
        */
    warn: function (warning, initialized = true) {
        console.log(debugmsg.errors.warning + warning);
        if (initialized) return log.ch(debugmsg.errors.warning + "\n```fix\n" + warning + "\n```");
    },
    /**
            * Print debug in terminal and bot channel
            *
            * @param {string} debug debug to print
            * @param {boolean} initialized if bot is initialised or not (default = true)
            */
    debug: function (debug, initialized = true) {
        console.log(debugmsg.errors.debug + debug);
        if (initialized) return log.ch(debugmsg.errors.debug + "\n```\n" + debug + "\n```");
    }
}