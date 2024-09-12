const { client } = require("../../../index.js");

module.exports = {
    /**
            * Get channel element by id
            *
            * @param {string} channelid channel to get by id
            */
    lstsrv: function () {
        let srvlst = client.guilds.cache.map(g => `${g.name} (${g.id})`)
        return `Handling ${srvlst.length} server${(srvlst.length > 1) ? "s" : ""} :\n - ${srvlst.join('\n - ')}`;
    },
    /**
            * Set bot activity and status
            *
            * @param {string} activity message to put in activity (null to keep previous)
            * @param {int} type default = 0 playing | 1 streaming | 2 listening | 3 watching | 4 custom
            * @param {int} status 0 null (keep previous) | 1 online | 2 idle | 3 invisible | 4 dnd (default : null)
            */
    setStatus: function (activity, type = 0, status = 0) {
        let statuslist = [null, 'online', 'idle', 'invisible', 'dnd'];
        if (activity)
            client.user.setActivity(activity, { type: type });
        if (status != 0)
            client.user.setStatus(statuslist[status]);
    },
}