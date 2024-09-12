<?php session_start();

if (isset($_POST["newsession"]) && isset($_POST["startsession"])) {
    $_SESSION["name"] = htmlspecialchars($_POST["newsession"]);
    $_SESSION["time"] = time();
    $_SESSION["new"] = 0;
    $_SESSION["updated"] = 0;
    $_SESSION["total"] = 0;
    header("Location: ./");
    exit();
}

if (isset($_POST["end"])) {
    unset($_SESSION["name"], $_SESSION["time"]);
    header("Location: ./");
}

include_once("includes/db.php");
include_once("includes/time.php");
$outputvalue = "";

if (isset($_POST["plate"]) && isset($_SESSION["name"]) && isset($_SESSION["time"]) && isset($_SESSION["new"]) && isset($_SESSION["updated"]) && isset($_SESSION["total"])) {
    $platename = strtoupper(htmlspecialchars($_POST["plate"]));

    $db = dbConnect();
    $platesStatement = $db->prepare('SELECT * FROM plates WHERE plate = :plate');
    $platesStatement->execute(['plate' => $platename]);
    $plates = $platesStatement->fetchAll();


    $isNewPlate = 1;
    $newplatetype = 0;
    $oldplatetype = 0;
    $nbSeen = 1;
    $now = time();
    if (sizeof($plates) > 0) {
        $isNewPlate = 0;
        $oldplatetype = $plates[0]["type"];
        $nbSeen += $plates[0]["nbSeen"];
        $outputvalue .= "+ Seen " . getTime((int) $plates[0]["lastSeen"], $now) . " ago (Seen $nbSeen times)<br>";
    } else {
        $outputvalue .= "+ NEW <br>";
    }

    if (isset($_POST["sendnplate"])) {
        // do nothing
    } else if (isset($_POST["sendlplate"])) {
        if ($oldplatetype != 7)
            $outputvalue .= "-> Rented car<br>";
        $newplatetype = 7;
    } else if (isset($_POST["sendbplate"])) {
        if ($oldplatetype != 5)
            $outputvalue .= "-> Bus<br>";
        $newplatetype = 5;
    } else if (isset($_POST["sendpplate"])) {
        if ($oldplatetype == 3) {
            $isNewPlate = -1;
            $outputvalue .= "ALREADY SEEN PARKED<br>";
        } else
            $outputvalue .= "-> Parked<br>";
        $newplatetype = 3;
    }
    if (!isset($_POST["sendpplate"]) && $oldplatetype == 3) {
        $outputvalue .= "-> Not parked<br>";
    }


    if ($isNewPlate == 1) {
        $sqlQuery = 'INSERT INTO plates(session, sessionTime, createdAt, plate, lastSeen, nbSeen, type) VALUES (:session, :sessionTime, :createdAt, :plate, :lastSeen, :nbSeen, :type)';

        $insertPlate = $db->prepare($sqlQuery);
        $insertPlate->execute([
            'session' => $_SESSION["name"],
            'sessionTime' => $_SESSION["time"],
            'createdAt' => $now,
            'plate' => $platename,
            'lastSeen' => $now,
            'nbSeen' => $nbSeen,
            'type' => $newplatetype
        ]);
        $_SESSION["new"] += 1;
    } else if ($isNewPlate == 0) {
        $sqlQuery = 'UPDATE plates SET lastSeen = :lastSeen, nbSeen = :nbSeen, type = :type WHERE plate = :plate';

        $updatePlates = $db->prepare($sqlQuery);
        $updatePlates->execute([
            'plate' => $platename,
            'lastSeen' => $now,
            'nbSeen' => $nbSeen,
            'type' => $newplatetype
        ]);
        $_SESSION["updated"] += 1;
    }
    $_SESSION["total"] += 1;

    $_SESSION["outputmsg"] = $outputvalue;

    header("Location: ./");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
    <title>Immatricat</title>
    <link href="vars.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <link href="importer/imports.css" rel="stylesheet">
    <meta name="author" content="MagicTINTIN">
    <meta name="description" content="J'ai aucun problème avec les statistiques. J'arrête quand je veux.">

    <link rel="icon" type="image/x-icon" href="images/favicon.png">

    <meta property="og:title" content="Immatricat">
    <meta property="og:description" content="J'ai aucun problème avec les statistiques. J'arrête quand je veux.">

    <meta property="og:image" content="https://etud.insa-toulouse.fr/~serviere/Immatricat/images/favicon.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:alt" content="Logo of Immatricat">
</head>

<body>
    <main>
        <form id="seestatsform" method="post" action="stats/">
            <input type="submit" id="seestats" name="gotostats" value="SEE STATISTICS >>" />
        </form>
        <?php
        if (!isset($_SESSION["name"]) || !isset($_SESSION["time"]) || !isset($_SESSION["total"]) || !isset($_SESSION["updated"]) || !isset($_SESSION["new"])) {
        ?>
            <form id="createsession" method="post">
                <input type="text" id="sessioninput" name="newsession" required maxlength="32" placeholder="pseudo" title="Enter a pseudo for your session">
                <input type="submit" id="sessionsubmit" name="startsession" value="START" />
            </form>

            <form id="farmform" method="post" action="farm/">
                <input type="submit" id="farmbutton" name="gotofarm" value="NEW INTERFACE >>" />
            </form>
            <?php if (isset($_SESSION["new"]) && isset($_SESSION["updated"]) && isset($_SESSION["total"])) { ?>
                <div id="output"><?php echo "New : " . $_SESSION["new"] . "<br>Updated : " . $_SESSION["updated"] . "<br>Total : " . $_SESSION["total"] ?></div>
            <?php }
        } else {
            ?>
            <div id="stats"><?php echo "New : " . $_SESSION["new"] . " | Updated : " . $_SESSION["updated"] . " | Total : " . $_SESSION["total"] ?></div>
            <form id="plateform" method="post">
                <div id="multibtn">
                    <div class="row">
                        <input type="submit" id="platesubmitn" class="subplate subchoice" name="sendnplate" value="NORMAL" />
                        <input type="submit" id="platesubmitb" class="subplate subchoice" name="sendbplate" value="BUS" />
                    </div>
                    <div class="row">
                        <input type="submit" id="platesubmitl" class="subplate subchoice" name="sendlplate" value="RENTED" />
                        <input type="submit" id="platesubmitp" class="subplate subchoice" name="sendpplate" value="PARKED" />
                    </div>
                </div>
                <input type="text" id="plateinput" class="plateinput" name="plate" required pattern="^[A-Za-z]{3}\d{3}$" oldpattern="[a-zA-Z0-9_-]+" minlength="6" maxlength="6" size="6" title="Enter the car plate" placeholder="XXX000" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
            </form>

            <form id="endform" method="post">
                <input type="submit" id="submitend" name="end" value="END SESSION" />
            </form>
            <!-- val "<?php echo $outputvalue ?>" -->
            <?php if (isset($_SESSION["outputmsg"])) {
            ?>
                <div id="output"><?php echo $_SESSION["outputmsg"] ?></div>
        <?php
                unset($_SESSION["outputmsg"]);
            }
            echo '<script src="script.js"></script>';
        }
        ?>
    </main>
</body>

</html>