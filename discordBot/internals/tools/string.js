module.exports = {
    /**
        * Add a uppercase letter at the beginning
        *
        * @param {string} string
        * @return {string} 
        */
    majFirstLetter: function (string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    },
    /**
        * Add plurals to words
        *
        * @param {int} nb element number
        * @return {string} 
        */
    s: function (nb) {
        return (nb > 1) ? "s" : "";
    }
}