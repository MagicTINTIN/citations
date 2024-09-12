function openclose(object) {
    divtoopenclose = object.id.slice(0, -3); 
    if (object.innerHTML == "︾")
    {
        document.getElementById(divtoopenclose).style.maxHeight = "200vh";
        object.innerHTML = "︽";
    }
    else
    {
        document.getElementById(divtoopenclose).style.maxHeight = "0px";
        object.innerHTML = "︾";
    }
}