const { client } = require("../../../index.js");

module.exports = {
    /**
            * Get channel element by id
            *
            * @param {string} channelid channel to get by id
            */
    get: function (channelid) {
        return client.channels.cache.get(channelid);
    },
}