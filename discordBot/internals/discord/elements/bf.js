const stepLimit = 100000;
const debugbf = false;
/**
 * @typedef {Object} bfreturn
 * @property {string} code cleaned code
 * @property {int} step step number
 * @property {string} str string output
 * @property {int[]} mem memory
 * @property {boolean} success if no error has occurred
 * @property {int} posProg program position
 * @property {int} posMem memory frame
 */

String.prototype.cleantobf = function () {
    return this.replace(/[^\+\-\[\]\<\>\,\.]+/g, "");
}

function cleanMem(mem) {
    while (mem.length > 1 && mem[mem.length - 1] == 0) {
        mem.pop();
    }
    return mem;
}

/**
        * Returns what have been imported
        *
        * @param {string} rawcode to be executed
        * @param {string[]} args will be the inputs
        * @return {bfreturn} brainfuck execution
        */
function exe(rawcode, args) {
    let code = rawcode.cleantobf();
    let res = {
        code: code.cleantobf(),
        step: 0,
        str: "",
        mem: [0],
        success: true,
        posProg: 0,
        posMem: 0,
    }
    let argnb = 1;
    while (res.posProg >= 0 && res.posProg < code.length && res.step < stepLimit) {
        const cmd = code[res.posProg];
        res.step++;
        if (cmd == "[") {
            if (debugbf) console.log("[ pointing", res.posMem, res.mem[res.posMem]);
            if (res.mem[res.posMem] == 0) {
                let loopnb = 1;
                while (loopnb > 0) {
                    res.posProg++;
                    if (code[res.posProg] == "[") {
                        loopnb++;
                    } else if (code[res.posProg] == "]") {
                        loopnb--;
                    }
                }
            }
        }
        else if (cmd == "]") {
            if (debugbf) console.log("] pointing", res.posMem, res.mem[res.posMem]);
            if (res.mem[res.posMem] != 0) {
                let loopnb = 1;
                while (loopnb > 0) {
                    res.posProg--;
                    if (code[res.posProg] == "[") {
                        loopnb--;
                    } else if (code[res.posProg] == "]") {
                        loopnb++;
                    }
                }
            }
        }
        else if (cmd == "." && res.mem[res.posMem] >= 32) {
            if (debugbf) console.log(res.mem[res.posMem], String.fromCharCode(res.mem[res.posMem]))
            res.str += String.fromCharCode(res.mem[res.posMem]);
        }
        else if (cmd == ",") {
            if (debugbf) console.log("get", argnb, args);
            if (args[argnb])
                res.mem[res.posMem] = args[argnb].charCodeAt(0);
            argnb++;
        }
        else if (cmd == ">") {
            res.posMem++;
            if (!res.mem[res.posMem])
                res.mem[res.posMem] = 0;
            if (debugbf) console.log("pointing", res.posMem, res.mem[res.posMem]);
        }
        else if (cmd == "<") {
            if (res.posMem > 0)
                res.posMem--;
            else {
                res.success = false;
                res.mem = cleanMem(res.mem);
                res.str += "\n**ERROR :** Cursor tried to go before first memory frame";
                return res;
            }
        }
        else if (cmd == "+") {
            if (debugbf) console.log("adb", res.posMem, res.mem[res.posMem]);
            if (res.mem[res.posMem] >= 255)
                res.mem[res.posMem] = 0;
            else
                res.mem[res.posMem]++;
            if (debugbf) console.log("ada", res.posMem, res.mem[res.posMem]);
        }
        else if (cmd == "-") {
            if (debugbf) console.log("rmb", res.posMem, res.mem[res.posMem]);
            if (res.mem[res.posMem] <= 0)
                res.mem[res.posMem] = 255;
            else
                res.mem[res.posMem]--;
            if (debugbf) console.log("rma", res.posMem, res.mem[res.posMem]);
        }
        res.posProg++;
    }
    res.mem = cleanMem(res.mem);

    if (res.step >= stepLimit) {
        res.success = false;
        res.str += "\n**ERROR :** Too many steps";
    }
    return res;
}
module.exports = { exe };