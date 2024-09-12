function getTime(d1, d2) {
    if (d1 <= 1697832735)
        return "a long time ago";
    diffMs = d2 - d1;
    diffDays = Math.floor(diffMs / 86400); //   days
    diffHrs = Math.floor((diffMs % 86400) / 3600); // hours
    diffMins = Math.round(((diffMs % 86400) % 3600) / 60); // minutes
    diffSecs = Math.round((((diffMs % 86400) % 3600) % 60)); // seconds
    return diffDays + "d " + diffHrs + "h " + diffMins + "m " + diffSecs + "s";
}

prefixes = {};
prefixesCarOnly = {};

doublePrefixes = {};
doublePrefixesCarOnly = {};

suffixes = {};
suffixesCarOnly = {};

doubleSuffixes = {};
doubleSuffixesCarOnly = {};

letters = {};
numbers = {};

nbNbSeen = []
maxNbSeen = 0;

central0 = 0;
doubleLetter = 0;
doubleNumber = 0;
totalPlates = 0;
totalUnique = 0;
totalUniqueCarOnly = 0;

function addP(toAdd) {
    if (!prefixes[toAdd])
        prefixes[toAdd] = 1;
    else
        prefixes[toAdd] += 1;
}

function addPCO(toAdd) {
    if (!prefixesCarOnly[toAdd])
        prefixesCarOnly[toAdd] = 1;
    else
        prefixesCarOnly[toAdd] += 1;
}

function addS(toAdd) {
    if (!suffixes[toAdd])
        suffixes[toAdd] = 1;
    else
        suffixes[toAdd] += 1;
}

function addSCO(toAdd) {
    if (!suffixesCarOnly[toAdd])
        suffixesCarOnly[toAdd] = 1;
    else
        suffixesCarOnly[toAdd] += 1;
}

function addDP(toAdd) {
    if (!doublePrefixes[toAdd])
        doublePrefixes[toAdd] = 1;
    else
        doublePrefixes[toAdd] += 1;
}

function addDPCO(toAdd) {
    if (!doublePrefixesCarOnly[toAdd])
        doublePrefixesCarOnly[toAdd] = 1;
    else
        doublePrefixesCarOnly[toAdd] += 1;
}

function addDS(toAdd) {
    if (!doubleSuffixes[toAdd])
        doubleSuffixes[toAdd] = 1;
    else
        doubleSuffixes[toAdd] += 1;
}

function addDSCO(toAdd) {
    if (!doubleSuffixesCarOnly[toAdd])
        doubleSuffixesCarOnly[toAdd] = 1;
    else
        doubleSuffixesCarOnly[toAdd] += 1;
}

function addL(toAdd) {
    if (!letters[toAdd])
        letters[toAdd] = 1;
    else
        letters[toAdd] += 1;
}

function addN(toAdd) {
    if (!numbers[toAdd])
        numbers[toAdd] = 1;
    else
        numbers[toAdd] += 1;
}

function percentages(val) {
    return Math.floor(10000 * val + 0.5) / 100;
}

for (const plate of plates) {
    totalUnique++;

    let prefix = "";
    let suffix = ""
    let i = 0;
    for (const c of plate["name"]) {
        if (i > 2) {
            addN(c)
            suffix += c;
        }
        else {
            addL(c);
            prefix += c;
        }
        if (i == 4 && c == '0')
            central0++;
        i++;
    }
    addP(prefix);
    addS(suffix);
    addDP(plate["name"][0] + plate["name"][1]);
    addDP(plate["name"][1] + plate["name"][2]);
    addDS(plate["name"][3] + plate["name"][4]);
    addDS(plate["name"][4] + plate["name"][5]);
    if (plate["type"] <= 3) {
        addPCO(prefix);
        addSCO(suffix);
        addDPCO(plate["name"][0] + plate["name"][1]);
        addDPCO(plate["name"][1] + plate["name"][2]);
        addDSCO(plate["name"][3] + plate["name"][4]);
        addDSCO(plate["name"][4] + plate["name"][5]);
        totalUniqueCarOnly++;
    }
    const nbseen = plate["nbSeen"];
    totalPlates += nbseen;
    if (maxNbSeen < nbseen) {
        for (let index = maxNbSeen; index < nbseen; index++) {
            nbNbSeen[index] = 0;
        }
        maxNbSeen = nbseen;
    }
    nbNbSeen[nbseen - 1]++;

    if (plate["name"][0] == plate["name"][1] || plate["name"][1] == plate["name"][2] || plate["name"][2] == plate["name"][0])
        doubleLetter++;

    if (plate["name"][3] == plate["name"][4] || plate["name"][4] == plate["name"][5] || plate["name"][5] == plate["name"][3])
        doubleNumber++;
}

// PREFIXES AND SUFFIXES STATS

const sortedPrefixes = Object.entries(prefixes);
sortedPrefixes.sort((a, b) => b[1] - a[1]);
prefixesOL = "";
for (let index = 0; index < Math.min(sortedPrefixes.length, 20); index++) {
    prefixesOL += `<li>${sortedPrefixes[index][0]} - ${sortedPrefixes[index][1]} plates (${percentages(sortedPrefixes[index][1] / totalUnique)}%)</li>`;
}
document.getElementById("prefixlist").innerHTML = prefixesOL;

const sortedPrefixesCarOnly = Object.entries(prefixesCarOnly);
sortedPrefixesCarOnly.sort((a, b) => b[1] - a[1]);
prefixescoOL = "";
for (let index = 0; index < Math.min(sortedPrefixesCarOnly.length, 20); index++) {
    prefixescoOL += `<li>${sortedPrefixesCarOnly[index][0]} - ${sortedPrefixesCarOnly[index][1]} plates (${percentages(sortedPrefixesCarOnly[index][1] / totalUniqueCarOnly)}%)</li>`;
}
document.getElementById("prefixcolist").innerHTML = prefixescoOL;

const sortedSuffixes = Object.entries(suffixes);
sortedSuffixes.sort((a, b) => b[1] - a[1]);
suffixesOL = "";
for (let index = 0; index < Math.min(sortedSuffixes.length, 20); index++) {
    suffixesOL += `<li>${sortedSuffixes[index][0]} - ${sortedSuffixes[index][1]} plates (${percentages(sortedSuffixes[index][1] / totalUnique)}%)</li>`;
}
document.getElementById("suffixlist").innerHTML = suffixesOL;

const sortedSuffixesCarOnly = Object.entries(suffixesCarOnly);
sortedSuffixesCarOnly.sort((a, b) => b[1] - a[1]);
suffixescoOL = "";
for (let index = 0; index < Math.min(sortedSuffixesCarOnly.length, 20); index++) {
    suffixescoOL += `<li>${sortedSuffixesCarOnly[index][0]} - ${sortedSuffixesCarOnly[index][1]} plates (${percentages(sortedSuffixesCarOnly[index][1] / totalUniqueCarOnly)}%)</li>`;
}
document.getElementById("suffixcolist").innerHTML = suffixescoOL;

// COUPLE OF LETTERS AND NUMBERS STATS

const sortedDoublePrefixes = Object.entries(doublePrefixes);
sortedDoublePrefixes.sort((a, b) => b[1] - a[1]);
doublePrefixesOL = "";
for (let index = 0; index < Math.min(sortedDoublePrefixes.length, 10); index++) {
    doublePrefixesOL += `<li>${sortedDoublePrefixes[index][0]} - ${sortedDoublePrefixes[index][1]} plates (${percentages(sortedDoublePrefixes[index][1] / totalUnique)}%)</li>`;
}
document.getElementById("dprefixlist").innerHTML = doublePrefixesOL;

const sortedDoublePrefixesCarOnly = Object.entries(doublePrefixesCarOnly);
sortedDoublePrefixesCarOnly.sort((a, b) => b[1] - a[1]);
doublePrefixescoOL = "";
for (let index = 0; index < Math.min(sortedDoublePrefixesCarOnly.length, 10); index++) {
    doublePrefixescoOL += `<li>${sortedDoublePrefixesCarOnly[index][0]} - ${sortedDoublePrefixesCarOnly[index][1]} plates (${percentages(sortedDoublePrefixesCarOnly[index][1] / totalUniqueCarOnly)}%)</li>`;
}
document.getElementById("dprefixcolist").innerHTML = doublePrefixescoOL;

const sortedDoubleSuffixes = Object.entries(doubleSuffixes);
sortedDoubleSuffixes.sort((a, b) => b[1] - a[1]);
doubleSuffixesOL = "";
for (let index = 0; index < Math.min(sortedDoubleSuffixes.length, 10); index++) {
    doubleSuffixesOL += `<li>${sortedDoubleSuffixes[index][0]} - ${sortedDoubleSuffixes[index][1]} plates (${percentages(sortedDoubleSuffixes[index][1] / totalUnique)}%)</li>`;
}
document.getElementById("dsuffixlist").innerHTML = doubleSuffixesOL;

const sortedDoubleSuffixesCarOnly = Object.entries(doubleSuffixesCarOnly);
sortedDoubleSuffixesCarOnly.sort((a, b) => b[1] - a[1]);
doubleSuffixescoOL = "";
for (let index = 0; index < Math.min(sortedDoubleSuffixesCarOnly.length, 10); index++) {
    doubleSuffixescoOL += `<li>${sortedDoubleSuffixesCarOnly[index][0]} - ${sortedDoubleSuffixesCarOnly[index][1]} plates (${percentages(sortedDoubleSuffixesCarOnly[index][1] / totalUniqueCarOnly)}%)</li>`;
}
document.getElementById("dsuffixcolist").innerHTML = doubleSuffixescoOL;

// LETTERS AND NUMBERS STATS

const sortedLetters = Object.entries(letters);
sortedLetters.sort((a, b) => b[1] - a[1]);
lettersOL = "";
for (let index = 0; index < sortedLetters.length; index++) {
    lettersOL += `<li>${sortedLetters[index][0]} - ${sortedLetters[index][1]} uses (${percentages(sortedLetters[index][1] / (3 * totalUnique))}%)</li>`;
}
document.getElementById("letterslist").innerHTML = lettersOL;

const sortedNumbers = Object.entries(numbers);
sortedNumbers.sort((a, b) => b[1] - a[1]);
numbersOL = "";
for (let index = 0; index < sortedNumbers.length; index++) {
    numbersOL += `<li>${sortedNumbers[index][0]} - ${sortedNumbers[index][1]} uses (${percentages(sortedNumbers[index][1] / (3 * totalUnique))}%)</li>`;
}
document.getElementById("numberslist").innerHTML = numbersOL;

const sortedNbSeenPlates = plates.slice();
sortedNbSeenPlates.sort((a, b) => b.nbSeen - a.nbSeen);
nbseenplatesOL = "";
for (let index = 0; index < Math.min(sortedNbSeenPlates.length, 20); index++) {
    nbseenplatesOL += `<li>${sortedNbSeenPlates[index].name} seen ${sortedNbSeenPlates[index].nbSeen} times</li>`;
}
document.getElementById("nbSeen").innerHTML = nbseenplatesOL;

nbbnbseenplateUL = "";
for (let index = 0; index < maxNbSeen; index++) {
    let nbplates = nbNbSeen[index];
    for (let i = index + 1; i < maxNbSeen; i++)
        nbplates += nbNbSeen[i];
    nbbnbseenplateUL += `<li>Seen ${index + 1}+ time${(index > 0) ? "s" : ""} - ${nbplates} car${(nbplates > 1) ? "s" : ""}</li>`;
}
document.getElementById("nbnbSeen").innerHTML = nbbnbseenplateUL;


document.getElementById("totalplates").innerHTML = `Total plates written ${totalPlates}`;
document.getElementById("totalunique").innerHTML = `Unique plates : ${totalUnique}`;
document.getElementById("totalbuses").innerHTML = `Buses/rented cars : ${totalUnique - totalUniqueCarOnly} (${percentages((totalUnique - totalUniqueCarOnly) / totalUnique)}%) | Normal cars : ${totalUniqueCarOnly}`;
document.getElementById("doubleletter").innerHTML = `2 or 3 identical letters : ${doubleLetter} plates (${percentages(doubleLetter / totalUnique)}% - normal: 11.24%)`;
document.getElementById("doublenumber").innerHTML = `2 or 3 identical numbers : ${doubleNumber} plates (${percentages(doubleNumber / totalUnique)}% - normal: 28%)`;
document.getElementById("zerocentral").innerHTML = `Has a 0 as center number : ${central0} plates (${percentages(central0 / totalUnique)}% - normal: 10%)`;

// document.getElementById("btnsearch").addEventListener("click pointerdown touch")

function searchPlate() {
    search = ""
    nbElements = 0;
    normalFilter = document.getElementById("normalfilter").checked;
    busFilter = document.getElementById("busfilter").checked;
    rentedFilter = document.getElementById("rentedfilter").checked;
    const val = document.getElementById("platesearchinput").value.toUpperCase();
    if (val.length < 1 || val.length > 6)
        return;
    for (const plate of plates) {
        if (plate.name.includes(val) && ((plate.type != 0 && plate.type != 3) || normalFilter) && (plate.type != 5 || busFilter) && (plate.type != 7 || rentedFilter)) {
            nbElements++;
            typecar = "";
            if (plate.type == 3)
                typecar = "[P] ";
            if (plate.type == 5)
                typecar = "[B] ";
            if (plate.type == 7)
                typecar = "[R] ";
            else
                typecar = "[N] "
            search += `<li>${plate.name.split(val).join("<span class='bold'>" + val + "</span>")} ${typecar}- Seen : ${plate.nbSeen} ${(plate.nbSeen > 1) ? "times" : "time"} : last time seen ${getTime(plate.lastSeen, Math.floor(Date.now() / 1000))}</li>`;
        }
    }
    document.getElementById("foundnb").innerHTML = `Found ${nbElements} result${(nbElements > 1) ? "s" : ""}${(nbElements > 0) ? " :" : "."}`
    document.getElementById("searchedplates").innerHTML = search;
}