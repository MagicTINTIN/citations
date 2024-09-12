<?php session_start();
// include_once("includes/cas.php");
$username = "user"; //phpCAS::getUser();
include_once("includes/db.php");
include_once("includes/time.php");
$db = dbConnect();

if (isset($_POST["citationInput"]) && isset($_POST["authorInput"]) && isset($_POST["dateInput"]) && isset($_POST["citationInput"]) && isset($_POST["newCitationSubmit"])) {


    $sqlQuery = 'INSERT INTO citations(date, citation, author, username) VALUES (:date, :citation, :author, :username)';

    $insertCitation = $db->prepare($sqlQuery);
    $insertCitation->execute([
        'date' => htmlspecialchars($_POST["dateInput"]),
        'citation' => htmlspecialchars($_POST["citationInput"]),
        'author' => htmlspecialchars($_POST["authorInput"]),
        'username' => $username
    ]);
    header("Refresh:0");
    exit();
} else if (false) {
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
            $citationsStatement = $db->prepare('SELECT * FROM citations');
            $citationsStatement->execute();
            $citations = $citationsStatement->fetchAll();

            foreach ($citations as $key => $value) {
                echo "<li>
                <div class='citationZone'><span class='citation citationCommon'>\"" . $value["citation"] . "\"</div>
                <div class='authorDateZone'><span class='authorDate authorDateCommon'>" . $value["author"] . " - " . $value["date"] . "</div>";
                if (in_array($username, array('serviere', 'v_lasser')) || $username == $value["username"]) {
                    echo "<div class='deleteCitation'>x</div>";
                }
                echo "</li>";
            }
            ?>
        </ul>
        <form method="post" class="citationForm">
            <textarea oninput="autoGrow(this)" class="citationInput citationCommon" name="citationInput" id="citationInput" required maxlength="1024"></textarea>
            <br>
            <input type="text" class="authorDateInput authorDateCommon" name="authorInput" id="authorInput" required maxlength="250">
            <input type="date" class="authorDateInput authorDateCommon" id="start" name="dateInput" value="<?php date('Y-m-d') ?>" required>
            <br>
            <input type="submit" class="citationSubmit" id="newCitationSubmit" value="Update description" name="newCitationSubmit">

        </form>
    </main>
</body>

</html>