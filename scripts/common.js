document.getElementById("nojs").style.display = "none";
let activedropdown = false;

String.prototype.replaceAt = function (index, replacement) {
    return this.substring(0, index) + replacement + this.substring(index, this.length).substring(1); //replacement.length
}

String.prototype.biReplaceAt = function (index1, replacement1, index2, replacement2) {
    if (index1 < index2)
        return this.substring(0, index1) + replacement1 + this.substring(index1, index2).substring(1) + replacement2 + this.substring(index2, this.length).substring(1); //replacement.length
    else
        return this.substring(0, index2) + replacement2 + this.substring(index2, index1).substring(1) + replacement1 + this.substring(index1, this.length).substring(1); //replacement.length
}

function deleteMsg(type) {
    const obj = document.getElementById(`${type}Msg`);
    if (obj) {
        obj.style.transform = "scale(0) rotate(45deg)";
        obj.style.margin = "0px";
    }
    else return 1
}

function copytcb(tocopy) {
    const storage = document.createElement('textarea');
    try {
        storage.value = tocopy;
        document.body.appendChild(storage);

        storage.select();
        storage.setSelectionRange(0, 99999);
        document.execCommand('copy');

        logger('Link copied to clipboard', 2);
        return 0;

    } catch (err) {
        errLogger('Failed to copy: ', err, 2);
        return 1;
    }
    finally {
        document.body.removeChild(storage);
    }
}

autoGrow(document.getElementById("citationInput"));

window.onresize = function(event) {
    autoGrow(document.getElementById("citationInput"));
};