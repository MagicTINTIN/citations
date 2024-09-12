const plateInput = document.getElementById('plateinput');
const submitButtons = document.querySelectorAll('.subplate[type="submit"]');

const platePattern = /^[A-Za-z]{3}\d{3}$/;

function updtButtons() {
    const isValid = platePattern.test(window.plate);
    submitButtons.forEach((button) => {
        button.disabled = !isValid;
    });
}

updtButtons();

window.plate = "";
window.indexPlate = 0;

function updatePlate() {
    updtButtons();
    document.getElementById("platevalue").value = window.plate;
    let displayedPlate = "";
    for (let index = 0; index < 6; index++) {
        if (index < window.plate.length)
            displayedPlate += window.plate[index];
        else {
            if (index < 3)
                displayedPlate += "-";
            else
                displayedPlate += "⋅";
        }
    }

    document.getElementById("plateinput").innerHTML = displayedPlate;
}

function setAzertyKeyboard() {
    document.getElementById("keyboardDiv").innerHTML = `<div class="keyboardRow cols10 letters">
    <button onclick="addSymbol('A')" class="keyButton">A</button>
    <button onclick="addSymbol('Z')" class="keyButton">Z</button>
    <button onclick="addSymbol('E')" class="keyButton">E</button>
    <button onclick="addSymbol('R')" class="keyButton">R</button>
    <button onclick="addSymbol('T')" class="keyButton">T</button>
    <button onclick="addSymbol('Y')" class="keyButton">Y</button>
    <button onclick="addSymbol('U')" class="keyButton">U</button>
    <button onclick="addSymbol('I')" class="keyButton">I</button>
    <button onclick="addSymbol('O')" class="keyButton">O</button>
    <button onclick="addSymbol('P')" class="keyButton">P</button>
    </div><div class="keyboardRow cols10 letters">
    <button onclick="addSymbol('Q')" class="keyButton">Q</button>
    <button onclick="addSymbol('S')" class="keyButton">S</button>
    <button onclick="addSymbol('D')" class="keyButton">D</button>
    <button onclick="addSymbol('F')" class="keyButton">F</button>
    <button onclick="addSymbol('G')" class="keyButton">G</button>
    <button onclick="addSymbol('H')" class="keyButton">H</button>
    <button onclick="addSymbol('J')" class="keyButton">J</button>
    <button onclick="addSymbol('K')" class="keyButton">K</button>
    <button onclick="addSymbol('L')" class="keyButton">L</button>
    <button onclick="addSymbol('M')" class="keyButton">M</button>
    </div><div class="keyboardRow cols7 letters">
    <button onclick="addSymbol('W')" class="keyButton">W</button>
    <button onclick="addSymbol('X')" class="keyButton">X</button>
    <button onclick="addSymbol('C')" class="keyButton">C</button>
    <button onclick="addSymbol('V')" class="keyButton">V</button>
    <button onclick="addSymbol('B')" class="keyButton">B</button>
    <button onclick="addSymbol('N')" class="keyButton">N</button>
    <button onclick="removeSymbol()" class="keyButton">←</button>
    </div>`;
}

function setNumberKeyboard() {
    document.getElementById("keyboardDiv").innerHTML = `<div class="keyboardRow cols3 numbers">
    <button onclick="addSymbol('7')" class="keyButton">7</button>
    <button onclick="addSymbol('8')" class="keyButton">8</button>
    <button onclick="addSymbol('9')" class="keyButton">9</button>
    </div><div class="keyboardRow cols3 numbers">
    <button onclick="addSymbol('4')" class="keyButton">4</button>
    <button onclick="addSymbol('5')" class="keyButton">5</button>
    <button onclick="addSymbol('6')" class="keyButton">6</button>
    </div><div class="keyboardRow cols3 numbers">
    <button onclick="addSymbol('1')" class="keyButton">1</button>
    <button onclick="addSymbol('2')" class="keyButton">2</button>
    <button onclick="addSymbol('3')" class="keyButton">3</button>
    </div><div class="keyboardRow numbers">
    <button onclick="addSymbol('0')" class="keyButton key00">0</button>
    <button onclick="removeSymbol()" class="keyButton key0backspace">←</button>
    </div>`;
}

function updateKeyboard() {
    if (window.indexPlate < 3)
        setAzertyKeyboard();
    else
        setNumberKeyboard();
}

function addSymbol(s) {
    //console.log(`Added "${s}"`);
    if (window.plate.length < 6) {
        window.plate += s;
        window.indexPlate = window.plate.length;
    }
    updatePlate();
    updateKeyboard();
}

function removeSymbol() {
    //console.log(`Remove last char`);
    window.plate = window.plate.slice(0, -1);
    window.indexPlate = window.plate.length;
    updatePlate();
    updateKeyboard();
}

updateKeyboard();

function enableGetKey() {
    document.body.addEventListener('keyup', getKeyEvent);
}

enableGetKey();

function submitPlate() {
    const isValid = platePattern.test(window.plate);
    if (isValid)
        document.getElementById("plateform").submit();
}

function getKeyEvent(e) {
    //console.log(e, e.key);
    if (window.indexPlate < 3 && e.key.toLowerCase() == "a") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "z") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "e") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "r") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "t") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "y") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "u") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "i") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "o") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "p") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "q") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "s") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "d") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "f") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "g") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "h") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "j") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "k") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "l") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "m") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "w") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "x") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "c") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "v") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "b") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate < 3 && e.key.toLowerCase() == "n") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate > 2 && e.key.toLowerCase() == "0") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate > 2 && e.key.toLowerCase() == "1") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate > 2 && e.key.toLowerCase() == "2") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate > 2 && e.key.toLowerCase() == "3") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate > 2 && e.key.toLowerCase() == "4") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate > 2 && e.key.toLowerCase() == "5") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate > 2 && e.key.toLowerCase() == "6") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate > 2 && e.key.toLowerCase() == "7") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate > 2 && e.key.toLowerCase() == "8") addSymbol(e.key.toUpperCase());
    else if (window.indexPlate > 2 && e.key.toLowerCase() == "9") addSymbol(e.key.toUpperCase());
    else if (e.key == "Backspace") removeSymbol();
    else if (e.key == "Enter") submitPlate();
};