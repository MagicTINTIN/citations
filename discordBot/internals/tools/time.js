module.exports = {
    /**
            * Get duration between two dates
            *
            * @param {string} d1 Starting date
            * @param {string} d2 Ending date
            */
    duration: function (d1, d2) {
        diffMs = d2 - d1
        var diffDays = Math.floor(diffMs / 86400000); // days
        var diffHrs = Math.floor((diffMs % 86400000) / 3600000); // hours
        var diffMins = Math.round(((diffMs % 86400000) % 3600000) / 60000); // minutes
        var diffSecs = Math.round((((diffMs % 86400000) % 3600000) % 60000) / 1000); // seconds
        return `${diffDays}d ${diffHrs}h ${diffMins}m ${diffSecs}s`;
    }
}