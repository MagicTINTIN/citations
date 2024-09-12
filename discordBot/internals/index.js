const base = {
    bf: require("./discord/elements/bf"),
    ch: require("./discord/elements/ch"),
    gld: require("./discord/elements/gld"),
    itr: require("./discord/elements/itr"),
    mbr: require("./discord/elements/mbr"),
    msg: require("./discord/elements/msg"),
    rct: require("./discord/elements/rct"),
    usr: require("./discord/elements/usr"),
    clt: require("./discord/elements/clt"),
}
exports.base = base;

const dscrd = {
    guild: require("./discord/guild"),
    interaction: require("./discord/interaction"),
    message: require("./discord/message"),
    reaction: require("./discord/reaction"),
}
exports.dscrd = dscrd;

module.exports = {
    base: base,
    dscrd: dscrd,
    tests: require("./tests/print"),
    importercount: require("./tools/importercount"),
    log: require("./tools/logger"),
    str: require("./tools/string"),
    rdm: require("./tools/random"),
    alert: require("./tools/alert"),
    file: require("./tools/file"),
    time: require("./tools/time"),
    init: require("./tools/init"),
}