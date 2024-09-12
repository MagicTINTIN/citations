/**
        * Returns what have been imported
        *
        * @param {Object} arg imported object
        * @return {string} 
        */
function importercount(arg) {
    let text = ""
    let totalfcts = 0;
    for (const key in arg) {
        if (typeof (arg[key]) == "object") {
            if (key == 'base') {
                let subsubfct = 0;
                for (const subkey in arg[key]) {
                    const subgroup = arg[key][subkey]
                    subsubfct += Object.keys(subgroup).length;
                }
                text += `- Discord Base (${key}) : ${Object.keys(arg[key]).length} subgroup${(Object.keys(arg[key]).length > 1) ? "s" : ""} = ${subsubfct} subsubfct${(subsubfct > 1) ? "s\n" : "\n"}`;
                totalfcts += subsubfct;
            }
            else if (key == 'dscrd') {
                let subsubfct = 0;
                for (const subkey in arg[key]) {
                    const subgroup = arg[key][subkey]
                    subsubfct += Object.keys(subgroup).length;
                }
                text += `- Discord (${key}) : ${Object.keys(arg[key]).length} subgroup${(Object.keys(arg[key]).length > 1) ? "s" : ""} = ${subsubfct} subsubfct${(subsubfct > 1) ? "s\n" : "\n"}`;
                totalfcts += subsubfct;
            }
            else {
                text += `- ${key} : ${Object.keys(arg[key]).length} subfct` + ((Object.keys(arg[key]).length > 1) ? "s\n" : "\n");
                totalfcts += Object.keys(arg[key]).length;
            }
        }
        else {
            text += `- ${key}\n`;
            totalfcts++;
        };
    }
    return `Imported ${Object.keys(arg).length} elements (total = ${totalfcts} fcts) from internals : \n${text}`;
};

module.exports = importercount;