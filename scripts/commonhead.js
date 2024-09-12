function animateCSS(objectid, classname, duration) {
    let objecttoanim = document.getElementById(objectid)
    let saveattributes = objecttoanim.getAttribute("class")
    objecttoanim.setAttribute("class", saveattributes + " " + classname);
    setTimeout(function () {
        objecttoanim.setAttribute("class", saveattributes);
    }, duration);
}

function autoGrow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight + 5) + "px";
}