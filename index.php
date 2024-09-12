<?php session_start();
// include_once("includes/cas.php");
$username = "user";//phpCAS::getUser();
include_once("includes/db.php");
include_once("includes/time.php");

if (isset($_POST["plate"]) && isset($_SESSION["name"]) && isset($_SESSION["time"]) && isset($_SESSION["new"]) && isset($_SESSION["updated"]) && isset($_SESSION["total"])) {


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
    <title>Citations Magistrales</title>
    <link href="vars.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <link href="importer/imports.css" rel="stylesheet">
    <meta name="author" content="MagicTINTIN">
    <meta name="description" content="Un site pour recenser les pépites entendues en CM">

    <link rel="icon" type="image/x-icon" href="images/favicon.png">

    <meta property="og:title" content="Citations Magistrales">
    <meta property="og:description" content="Un site pour recenser les pépites entendues en CM">

    <meta property="og:image" content="https://etud.insa-toulouse.fr/~serviere/citations/images/favicon.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:alt" content="Logo of Citations Magistrales">
</head>

<body>
    <main>
        <ul>
            <?php
            $db = dbConnect();
            $platesStatement = $db->prepare('SELECT * FROM citations');
            $platesStatement->execute();
            $plates = $platesStatement->fetchAll();

            foreach ($plates as $key => $value) {
                echo "<li>
                <div class='citationZone'><span class='citation'>\"" . $value["citation"] . "\"</div>
                <div class='authorDateZone'><span class='authorDate'>" . $value["author"] . " - " . $value["date"] . "</div>";
                if (in_array($username , array( 'serviere' , 'v_lasser')) || $username == $value["username"]) {
                    echo "<div class='deleteCitation'>x</div>";
                }
                echo "</li>";
            }
            ?>
        </ul>
    </main>
</body>

</html>