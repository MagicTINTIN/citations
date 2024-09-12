<?php session_start();

if (isset($_POST["gotostats"])) {
    header("Location: ./");
    exit();
}

include_once("../includes/db.php");
$plates = [];

$db = dbConnect();
$platesStatement = $db->prepare('SELECT * FROM plates');
$platesStatement->execute();
$plates = $platesStatement->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
    <title>Statistics - Immatricat</title>
    <link href="../vars.css" rel="stylesheet">
    <link href="../styles.css" rel="stylesheet">
    <link href="stats.css" rel="stylesheet">
    <meta name="author" content="MagicTINTIN">
    <meta name="description" content="J'ai aucun problème avec les statistiques. J'arrête quand je veux.">

    <link rel="icon" type="image/x-icon" href="../images/favicon.png">

    <meta property="og:title" content="Statistics - Immatricat">
    <meta property="og:description" content="J'ai aucun problème avec les statistiques. J'arrête quand je veux.">

    <meta property="og:image" content="https://etud.insa-toulouse.fr/~serviere/Immatricat/images/favicon.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:alt" content="Logo of Immatricat">
</head>

<body>
    <div class="void"></div>
    <section class="col">
        <h2><span class="menuopenclose" id="prefixusagebtn" onclick="openclose(this)" ontouchstart="openclose(this)">︾</span> | Most seen prefixes</h2>
        <div id="prefixusage" class="split closable">
            <h3>All plates</h3>
            <ol id="prefixlist"></ol>
            <h3>Only car plates (no buses/rentend cars)</h3>
            <ol id="prefixcolist"></ol>
        </div>
        <h2><span class="menuopenclose" id="suffixusagebtn" onclick="openclose(this)" ontouchstart="openclose(this)">︾</span> | Most seen suffixes</h2>
        <div id="suffixusage" class="split closable">
            <h3>All plates</h3>
            <ol id="suffixlist"></ol>
            <h3>Only car plates (no buses/rentend cars)</h3>
            <ol id="suffixcolist"></ol>
        </div>
        <h2><span class="menuopenclose" id="dprefixusagebtn" onclick="openclose(this)" ontouchstart="openclose(this)">︾</span> | Most seen couple of Letters</h2>
        <div id="dprefixusage" class="split closable">
            <h3>All plates</h3>
            <ol id="dprefixlist"></ol>
            <h3>Only car plates (no buses/rentend cars)</h3>
            <ol id="dprefixcolist"></ol>
        </div>
        <h2><span class="menuopenclose" id="dsuffixusagebtn" onclick="openclose(this)" ontouchstart="openclose(this)">︾</span> | Most seen couple of Numbers</h2>
        <div id="dsuffixusage" class="split closable">
            <h3>All plates</h3>
            <ol id="dsuffixlist"></ol>
            <h3>Only car plates (no buses/rentend cars)</h3>
            <ol id="dsuffixcolist"></ol>
        </div>
        <h2><span class="menuopenclose" id="letternumberssusagebtn" onclick="openclose(this)" ontouchstart="openclose(this)">︾</span> | Most seen Characters</h2>
        <div id="letternumberssusage" class="split closable">
            <div id="letters">
                <h3>Most used letters - normal: 3.85%</h3>
                <ol id="letterslist"></ol>
            </div>
            <div id="numbers">
                <h3>Most used numbers - normal: 10%</h3>
                <ol id="numberslist"></ol>
            </div>
        </div>
        <h2><span class="menuopenclose" id="mostseenplatesbtn" onclick="openclose(this)" ontouchstart="openclose(this)">︾</span> | Most seen plates</h2>
        <div class="split closable" id="mostseenplates">
            <div>
                <h3>Most seen plates</h3>
                <ol id="nbSeen"></ol>
            </div>
            <div>
                <h3>Plates seen numbers</h3>
                <ul id="nbnbSeen"></ul>
            </div>
        </div>
        <h2><span class="menuopenclose" id="variousstatsbtn" onclick="openclose(this)" ontouchstart="openclose(this)">︾</span> | Various statistics</h2>
        <div id="variousstats" class="closable">
            <span id="totalplates"></span><br>
            <span id="totalunique"></span><br>
            <span id="totalbuses"></span><br>
            <span id="zerocentral"></span><br>
            <span id="doubleletter"></span><br>
            <span id="doublenumber"></span><br>
        </div>
        <div>
            <h2>Search for specific plates</h2>
            <span>Enter what should contain the plate : </span>
            <input type="text" id="platesearchinput" class="platesearchinput" name="platesearch" minlength="1" maxlength="6" size="6" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
            <button onclick="searchPlate()" id="btnsearch">SEARCH</button>
            <br>
            <label for="normalfilter">Normal</label>
            <input type="checkbox" id="normalfilter" name="normalfilter" value="normalFilter" checked="checked">
            <span> | </span>
            <label for="busfilter">Bus</label>
            <input type="checkbox" id="busfilter" name="busfilter" value="busFilter" checked="checked">
            <span> | </span>
            <label for="rentedfilter">Rented</label>
            <input type="checkbox" id="rentedfilter" name="rentedfilter" value="rentedFilter" checked="checked">
            <br>
            <span id="foundnb"></span>
            <ul id="searchedplates"></ul>
        </div>
    </section>
    <div class="void"></div>
    <script>
        const plates = [
            <?php
            foreach ($plates as $key => $value) {
                echo "{name:\"" . $value["plate"] . "\",nbSeen:" . $value["nbSeen"] . ",type:" . $value["type"] . ",lastSeen:" . $value["lastSeen"] . "},";
            }
            ?>
        ];
    </script>
    <script src="style.js"></script>
    <script src="stats.js"></script>
</body>

</html>