module.exports = {
    /**
        * Get a random string
        *
        * @param {int} strlength string length
        * @return {string} 
        */
    string: function (strlength) {
        const chars =
            "AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz1234567890";
        const randomArray = Array.from(
            { length: strlength },
            (v, k) => chars[Math.floor(Math.random() * chars.length)]
        );

        const randomString = randomArray.join("");
        return randomString;
    },

    /**
        * Get a random integer
        *
        * @param {int} min lowest random number
        * @param {int} max highest random number
        * @return {int} 
        */
    int: function (min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min)) + min;
    },

    /**
        * Get a random object of array
        *
        * @param {Object[]} arr
        * @return {int} 
        */
    obj: function (arr) {
        return arr[this.int(0, arr.length - 1)];
    },
};