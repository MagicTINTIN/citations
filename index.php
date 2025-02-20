<?php session_start();

use PSpell\Config;

if (!isset($_SESSION["SORT-BY"]))
    $_SESSION["SORT-BY"] = "PLUS RÉCENT";

function safeStr($input): string
{
    return str_replace("\n", "\\n", str_replace("\\", "\\\\", $input));
}

if (isset($_GET["json"])) {

    include_once("includes/db.php");
    $db = dbConnect();
    $db = dbConnect();
    $citationsStatement = $db->prepare('SELECT * FROM citations');
    $citationsStatement->execute();
    $citations = $citationsStatement->fetchAll();
    $i = 0;
    echo "[";
    foreach ($citations as $key => $value) {
        if ($value["status"] < 1) continue;
        if ($i > 0)
            echo ",";
        echo "{\"ID\":\"" . safeStr($value["ID"]) . "\"\"author\":\"" . safeStr($value["author"]) . "\",\"citation\":\"" . safeStr($value["citation"]) . "\",\"date\":\"" . $value["date"] . "\"}";
        $i++;
    }
    echo "]";
    exit();
}

if (isset($_POST["connection"])) {
    unset($_POST);
    $_SESSION["connected"] = true;

    // header("Refresh:0"); // WTF ??? PHP LA MERDE ? POURQUOI ÇA MARCHE PAS ???
    header('Location: #');
    exit();
}


if (isset($_POST["disconnection"])) {
    unset($_POST);
    unset($_SESSION["connected"]);
    header('Location: #');
    exit();
}

$promoted = array('serviere', 'v_lasser', 'rebillar');
$admin = array('serviere');
if (isset($_SESSION["connected"])) {
    $username = "serviere";
    // include_once("includes/cas.php");
    // $username = phpCAS::getUser();
}

include_once("../db.php");
include_once("includes/time.php");
$db = dbConnect();

if (isset($_POST["sort-by"])) {
    if ($_SESSION["SORT-BY"] == "NOTE")
        $_SESSION["SORT-BY"] = "PLUS RÉCENT";
    else
        $_SESSION["SORT-BY"] = "NOTE";

    header("Refresh:0");
    exit();
}

if (isset($_SESSION["connected"])) {
    if (isset($_POST["citationInput"]) && isset($_POST["authorInput"]) && isset($_POST["dateInput"]) && isset($_POST["citationInput"]) && isset($_POST["newCitationSubmit"])) {

        if (strlen(htmlspecialchars($_POST["citationInput"])) >= 4096 || strlen(htmlspecialchars($_POST["authorInput"])) >= 255) {
            header("Refresh:0");
            exit();
        }

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
    } else if (isset($_POST["deletemsg"]) && isset($_POST["delID"])) {
        $db = dbConnect();
        $citationsStatement = $db->prepare('SELECT username FROM citations WHERE ID = :ID');
        $citationsStatement->execute([
            'ID' => htmlspecialchars($_POST["delID"])
        ]);
        $citations = $citationsStatement->fetchAll();
        if (sizeof($citations) > 0 && in_array($username, $promoted) || $citations[0]["username"] == $username) {
            $sqlQuery = 'UPDATE citations SET status = :status WHERE ID = :ID';

            $updatePlates = $db->prepare($sqlQuery);
            $updatePlates->execute([
                'ID' => htmlspecialchars($_POST["delID"]),
                'status' => 0
            ]);
        }

        header("Refresh:0");
        exit();
    } else if (isset($_POST["ultradeletemsg"]) && isset($_POST["udelID"]) && in_array($username, $admin)) {
        $sqlQuery = 'UPDATE citations SET status = :status WHERE ID = :ID';

        $updatePlates = $db->prepare($sqlQuery);
        $updatePlates->execute([
            'ID' => htmlspecialchars($_POST["udelID"]),
            'status' => -1
        ]);

        header("Refresh:0");
        exit();
    } else if (isset($_POST["restoremsg"]) && isset($_POST["resID"]) && in_array($username, $promoted)) {
        $sqlQuery = 'UPDATE citations SET status = :status WHERE ID = :ID';

        $updatePlates = $db->prepare($sqlQuery);
        $updatePlates->execute([
            'ID' => htmlspecialchars($_POST["resID"]),
            'status' => 1
        ]);

        header("Refresh:0");
        exit();
    } else if (isset($_POST["ultrarestoremsg"]) && isset($_POST["uresID"]) && in_array($username, $admin)) {
        $sqlQuery = 'UPDATE citations SET status = :status WHERE ID = :ID';

        $updatePlates = $db->prepare($sqlQuery);
        $updatePlates->execute([
            'ID' => htmlspecialchars($_POST["uresID"]),
            'status' => 0
        ]);

        header("Refresh:0");
        exit();
    } else if (isset($_POST["verifymsg"]) && isset($_POST["verID"]) && in_array($username, $promoted)) {
        $sqlQuery = 'UPDATE citations SET status = :status WHERE ID = :ID';

        $updatePlates = $db->prepare($sqlQuery);
        $updatePlates->execute([
            'ID' => htmlspecialchars($_POST["verID"]),
            'status' => 2
        ]);

        header("Refresh:0");
        exit();
    } else if (isset($_POST["unverifymsg"]) && isset($_POST["unverID"]) && in_array($username, $promoted)) {
        $sqlQuery = 'UPDATE citations SET status = :status WHERE ID = :ID';

        $updatePlates = $db->prepare($sqlQuery);
        $updatePlates->execute([
            'ID' => htmlspecialchars($_POST["unverID"]),
            'status' => 1
        ]);

        header("Refresh:0");
        exit();
    } else if (isset($_POST["updateReaction"]) && isset($_POST["citationID"]) && isset($_POST["likeValue"])) {
        $likeValue = intval(htmlspecialchars($_POST["likeValue"]));
        $citIDValue = intval(htmlspecialchars($_POST["citationID"]));
        if ($likeValue < -1 || $likeValue > 1) {
            $_SESSION["redirectToID"] = $citIDValue;
            header("Refresh:0");
            exit();
        }
        // {$date->format('Y-m-d H:i:s')}
        $now = date('Y-m-d H:i:s');
        $sqlQuery = "UPDATE citationsCounters SET likeValue = :likeValue, time = '$now' WHERE citationID = :citationID AND username = :username";

        $updateReactions = $db->prepare($sqlQuery);
        $updateReactions->execute([
            'citationID' => $citIDValue,
            'username' => $username,
            'likeValue' => $likeValue
        ]);
        if ($updateReactions->rowCount() == 0) {
            $sqlQuery = 'INSERT INTO citationsCounters(citationID, username, likeValue) VALUES (:citationID, :username, :likeValue)';

            $insertReaction = $db->prepare($sqlQuery);
            $insertReaction->execute([
                'citationID' => $citIDValue,
                'username' => $username,
                'likeValue' => $likeValue
            ]);
        }

        $_SESSION["redirectToID"] = $citIDValue;
        header("Refresh:0");
        exit();
    }
}

if (isset($_GET["c"])) {
    $_SESSION["redirectToID"] = intval(htmlspecialchars($_GET["c"]));

    $db = dbConnect();
    $citationsStatement = $db->prepare('SELECT * FROM citations WHERE ID = :ID AND status > 0');
    $citationsStatement->execute([
        "ID" => $_SESSION["redirectToID"]
    ]);
    $citations = $citationsStatement->fetchAll();

    if (sizeof($citations) > 0) {
        $_SESSION["redirectToCitation"] = $citations[0]["citation"];
        $_SESSION["redirectToCitationAuthor"] = $citations[0]["author"];
    }
}

if (isset($_SESSION["redirectToCitation"]) && isset($_SESSION["redirectToCitationAuthor"])) {
    $description = $_SESSION["redirectToCitation"] . "\n\n   - " . $_SESSION["redirectToCitationAuthor"];
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
        <title>Citations Magistrales</title>

        <script src="./scripts/commonhead.js"></script>
        <link href="./styles/animations.css" rel="stylesheet">
        <link href="./styles/vars.css" rel="stylesheet">
        <link href="./styles/issue.css" rel="stylesheet">
        <link href="./styles/common.css" rel="stylesheet">
        <link href="./styles/citation.css" rel="stylesheet">
        <meta name="author" content="MagicTINTIN">
        <meta name="description" content="<?php echo $description ?>">

        <link rel="icon" type="image/x-icon" href="images/favicon.png">

        <meta property="og:type" content="website" />
        <meta property="og:title" content="Citations Magistrales">
        <meta property="og:description" content="<?php echo $description ?>">

        <meta property="og:image" content="https://etud.insa-toulouse.fr/~serviere/citations/images/favicon.png">
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:alt" content="Logo of Citations Magistrales">

        <meta property="og:url" content="https://etud.insa-toulouse.fr/~serviere/citations" />
        <meta data-react-helmet="true" name="theme-color" content="#43ceed" />
    </head>
<?php
} else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
        <title>Citations Magistrales</title>

        <script src="./scripts/commonhead.js"></script>
        <link href="./styles/animations.css" rel="stylesheet">
        <link href="./styles/vars.css" rel="stylesheet">
        <link href="./styles/issue.css" rel="stylesheet">
        <link href="./styles/common.css" rel="stylesheet">
        <link href="./styles/citation.css" rel="stylesheet">
        <meta name="author" content="MagicTINTIN">
        <meta name="description" content="Un site pour recenser les pépites entendues en CM">

        <link rel="icon" type="image/x-icon" href="images/favicon.png">

        <meta property="og:type" content="website" />
        <meta property="og:title" content="Citations Magistrales">
        <meta property="og:description" content="Un site pour recenser les pépites entendues en CM">

        <meta property="og:image" content="https://etud.insa-toulouse.fr/~serviere/citations/images/favicon.png">
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:alt" content="Logo of Citations Magistrales">

        <meta property="og:url" content="https://etud.insa-toulouse.fr/~serviere/citations" />
        <meta data-react-helmet="true" name="theme-color" content="#43ceed" />
    </head>
<?php
}

function reactions(int $citationNum, $db, $username): void
{
?>
    <div class="reactions">
        <div class="reactionCounter">
            <?php
            $citationReactionsStatement = $db->prepare('SELECT * FROM citationsCounters WHERE citationID = :citationID');
            $citationReactionsStatement->execute([
                'citationID' => $citationNum
            ]);
            $citationReactions = $citationReactionsStatement->fetchAll();

            $userLikeValue = 0;
            $totalLikesRatio = 0;

            foreach ($citationReactions as $key => $value) {
                if ($value["username"] == $username)
                    $userLikeValue = $value["likeValue"];
                $totalLikesRatio += $value["likeValue"];
            }

            if ($userLikeValue == 1) { ?>
                <form method="post" class="shiny reactionButtonContainer">
                    <input type='hidden' name="likeValue" value="0">
                    <input type='hidden' name="citationID" value="<?php echo $citationNum ?>">

                    <input type="submit" name="updateReaction" value="▲" class="spanButtonReaction">
                </form>
            <?php
            } else {
            ?>
                <form method="post" class="reactionButtonContainer">
                    <input type='hidden' name="likeValue" value="1">
                    <input type='hidden' name="citationID" value="<?php echo $citationNum ?>">

                    <input type="submit" name="updateReaction" value="△" class="spanButtonReaction">
                </form>
            <?php
            }
            ?>
            <div class="reactionButtonContainer">
                <span class="reactionNumber"><?php echo $totalLikesRatio ?></span>
            </div>
            <?php
            if ($userLikeValue == -1) {
            ?>
                <form method="post" class="shiny reactionButtonContainer">
                    <input type='hidden' name="likeValue" value="0">
                    <input type='hidden' name="citationID" value="<?php echo $citationNum ?>">

                    <input type="submit" name="updateReaction" value="▼" class="spanButtonReaction">
                </form>
            <?php
            } else {
            ?>
                <form method="post" class="reactionButtonContainer">
                    <input type='hidden' name="likeValue" value="-1">
                    <input type='hidden' name="citationID" value="<?php echo $citationNum ?>">

                    <input type="submit" name="updateReaction" value="▽" class="spanButtonReaction">
                </form>
            <?php
            }
            ?>
        </div>
        <div class="reactionButtonContainer">
            <span class="spanButtonReaction" onclick="shareCitation(<?php echo $citationNum ?>);">➦</span>
        </div>

        <!-- ▲⇧⬆1⬇⇩▼ -->
        <!-- <span class="spanButtonReaction" onclick="alert('Not available yet');">🗩</span> -->
    </div>
<?php
}

function reactionsNotConnected(int $citationNum, $db): void
{
    $citationReactionsStatement = $db->prepare('SELECT * FROM citationsCounters WHERE citationID = :citationID');
    $citationReactionsStatement->execute([
        'citationID' => $citationNum
    ]);
    $citationReactions = $citationReactionsStatement->fetchAll();

    $totalLikesRatio = 0;

    foreach ($citationReactions as $key => $value) {
        $totalLikesRatio += $value["likeValue"];
    }
?>
    <div class="reactions">
        <div class="reactionCounter">
            <div class="reactionButtonContainer">
                <span class="spanButtonReactionDisabled" title="Connectez-vous pour pouvoir réagir !" onclick="youNeedToBeConnected('voter')">△</span>
            </div>
            <div class="reactionButtonContainer">
                <span class="reactionNumber"><?php echo $totalLikesRatio ?></span>
            </div>
            <div class="reactionButtonContainer">
                <span class="spanButtonReactionDisabled" title="Connectez-vous pour pouvoir réagir !" onclick="youNeedToBeConnected('voter !')">▽</span>
            </div>
        </div>
        <div class="reactionButtonContainer">
            <span class="spanButtonReaction"  onclick="shareCitation(<?php echo $citationNum ?>);">➦</span>
        </div>
        <!-- ▲⇧⬆1⬇⇩▼ -->
        <!-- <span class="spanButtonReaction" onclick="alert('Not available yet');">🗩</span> -->
    </div>
<?php
}


?>

<body>
    <?php include_once("./includes/nojs.php"); ?>
    <?php include_once("./includes/infoanderror.php");
    if (isset($_SESSION["connected"])) {
    ?>
        <form method="post" class="topDeco">
            <input type="submit" class="disconnectionButton" id="connection" value="Déconnexion" name="disconnection">
        </form>
    <?php
    }
    ?>

    <main>
        <h1>Citations Magistrales</h1>
        <p class="underH1" title="Tant que ça ne porte pas atteinte à l'intégrité de la personne... bien évidemment">Enregistrez les pépites entendues en CM</p>
        <?php if (isset($_SESSION["connected"])) { ?>
            <form method="post" class="citationForm">
                <div class='citationZone zone'>
                    <!-- <span class='citationCommon openingInput'>"</span> -->
                    <textarea oninput="autoGrow(this)" class="citationInput citationCommon" name="citationInput" id="citationInput" required maxlength="1024" placeholder="La citation"></textarea>
                    <!-- <span class='citationCommon closingInput'>"</span> -->
                </div>

                <div class='authorDateZone authorDateZoneInput zone2'><input type="text" class="input authorDateInput authorDateCommon authorInput" name="authorInput" id="authorInput" required maxlength="250" placeholder="Quelqu'un">
                    <input type="date" class="input authorDateInput authorDateCommon dateInput" id="dateInput" name="dateInput" value="<?php echo date('Y-m-d') ?>" required>
                </div>

                <div class='zone3'>
                    <input type="submit" class="input citationSubmit" id="newCitationSubmit" value="Ajouter la citation" name="newCitationSubmit">
                </div>

            </form>
        <?php } else { ?>
            <form method="post" class="citationFormDisabled" title="Connectez-vous pour ajouter de nouvelles citations !">
                <div class='citationZone zoned'>
                    <!-- <span class='citationCommon openingInput'>"</span> -->
                    <textarea disabled oninput="autoGrow(this)" class="citationInput citationCommon" name="citationInput" id="citationInput" required maxlength="1024" placeholder="La citation"></textarea>
                    <!-- <span class='citationCommon closingInput'>"</span> -->
                </div>

                <div class='authorDateZone authorDateZoneInput zone2d'><input type="text" class="input authorDateInput authorDateCommon authorInput" name="authorInput" id="authorInput" required maxlength="250" placeholder="Quelqu'un">
                    <input disabled type="date" class="input authorDateInput authorDateCommon dateInput" id="dateInput" name="dateInput" value="<?php echo date('Y-m-d') ?>" required>
                </div>

                <div class='zone3d'>
                    <input disabled type="submit" class="input citationSubmit" id="newCitationSubmit" value="Ajouter la citation" name="newCitationSubmit">
                </div>
            </form>
            <form method="post" class="citationConnect">
                <div class='connectButton'>
                    <input type="submit" class="connectionButton" id="connection" value="Se connecter" name="connection">
                </div>
            </form>
        <?php } ?>
        <form method="post" id="sortby">
            <span>TRIER PAR : </span>
            <input type="submit" name="sort-by" value="<?php echo $_SESSION["SORT-BY"] ?>" class="sortbyButtton">
        </form>
        <ul>
            <?php
            $db = dbConnect();
            if ($_SESSION["SORT-BY"] == "NOTE")
                $citationsStatement = $db->prepare('SELECT c.*
FROM citations c
LEFT JOIN (
    SELECT citationID, SUM(likeValue) AS totalLikes
    FROM citationsCounters
    GROUP BY citationID
) cc ON c.ID = cc.citationID
ORDER BY COALESCE(cc.totalLikes, 0) ASC, c.postedTime ASC;
');
            else
                $citationsStatement = $db->prepare('SELECT * FROM citations');
            $citationsStatement->execute();
            $citations = $citationsStatement->fetchAll();

            foreach (array_reverse($citations) as $key => $value) {
                $datetime = DateTime::createFromFormat('Y-m-d', $value["date"]);
                $formattedDate = $datetime->format('j M Y');

                if (isset($_SESSION["connected"]) && in_array($username, $promoted) && (isset($_GET["mod"])  || isset($_GET["deleted"]) || isset($_GET["unverified"]))) {
                    if (in_array($username, $admin) && $value["status"] < 0 && (isset($_GET["mod"])  || isset($_GET["deleted"]))) {
                        echo "<li id='cit" . $value["ID"] . "' class='ultradeletedCitation'>";
                    }
                    if ($value["status"] == 0 && (isset($_GET["mod"])  || isset($_GET["deleted"]))) {
                        echo "<li id='cit" . $value["ID"] . "' class='deletedCitation'>";
                    } else if ($value["status"] == 1 && (isset($_GET["mod"])  || isset($_GET["unverified"]))) {
                        echo "<li id='cit" . $value["ID"] . "' class='unverifiedCitation'>";
                    } else if ($value["status"] > 1 && (isset($_GET["mod"]))) {
                        echo "<li id='cit" . $value["ID"] . "' class='verifiedCitation'>";
                    }
                    if ((isset($_GET["mod"]) && $value["status"] >= 0) || (isset($_GET["ultradeleted"]) && $value["status"] < 0 && in_array($username, $admin)) || (isset($_GET["deleted"]) && $value["status"] == 0) || (isset($_GET["unverified"]) && $value["status"] == 1)) {
                        echo "<div class='citationZone zone'><span class='citation citationCommon'>\"" . $value["citation"] . "\"</div>
                        <div class='authorDateZone zone adzCitation'>";
                        reactions($value['ID'], $db, $username);
                        echo "<div class='reactionButtonContainer'><span class='authorDate authorDateCommon'>" . $value["author"] . ", " . $formattedDate . "";
                        if (isset($_GET["mod"]) && in_array($username, $promoted)) {
            ?>
                            <div class="writer">
                                <span>written by <?php echo $value['username']; ?></span>
                            </div>
                            <?php
                        }
                        if (in_array($username, $promoted) || $username == $value["username"]) {
                            if (isset($_GET["ultradeleted"]) && $value["status"] < 0 && in_array($username, $admin)) { ?>
                                <div class="delMsgDiv">
                                    <span onclick="createMessage('confirm', 'Unultradelete citation ?', 'Are you sure you want to unultradelete this citation?', 'ultrarestoremsg', 'Unultradelete', 'uresID', '<?php echo $value['ID']; ?>')" class="delMsgSpan">Unultradelete</span>
                                </div>
                            <?php } else if ($value["status"] == 0 && (isset($_GET["mod"])  || isset($_GET["deleted"]))) {
                            ?>
                                <div class="delMsgDiv">
                                    <span onclick="createMessage('confirm', 'Restore citation ?', 'Are you sure you want to restore this citation?', 'restoremsg', 'Restore', 'resID', '<?php echo $value['ID']; ?>')" class="delMsgSpan">Restore</span>
                                </div>
                                <?php
                                if (in_array($username, $admin)) {
                                ?>
                                    <div class="delMsgDiv">
                                        <span onclick="createMessage('confirm', 'Ultradelete citation ?', 'Are you sure you want to ultradelete this citation?', 'ultradeletemsg', 'Ultradelete', 'udelID', '<?php echo $value['ID']; ?>')" class="delMsgSpan">Ultradelete</span>
                                    </div>
                                <?php }
                            } else {
                                ?>
                                <div class="delMsgDiv">
                                    <span onclick="createMessage('confirm', 'Delete citation ?', 'Are you sure you want to delete this citation?', 'deletemsg', 'Delete', 'delID', '<?php echo $value['ID']; ?>')" class="delMsgSpan">Delete</span>
                                </div>
                            <?php
                            }
                            if ($value["status"] == 1 && (isset($_GET["mod"])  || isset($_GET["unverified"]))) {
                            ?>
                                <div class="delMsgDiv">
                                    <span onclick="createMessage('confirm', 'Verify citation ?', 'Are you sure you want to verify this citation?', 'verifymsg', 'Verify', 'verID', '<?php echo $value['ID']; ?>')" class="verMsgSpan">Verify</span>
                                </div>
                            <?php
                            } else if ($value["status"] > 1 && isset($_GET["mod"])) {
                            ?>
                                <div class="delMsgDiv">
                                    <span onclick="createMessage('confirm', 'Unverify citation ?', 'Are you sure you want to unverify this citation?', 'unverifymsg', 'Unverify', 'unverID', '<?php echo $value['ID']; ?>')" class="unverMsgSpan">Unverify</span>
                                </div>
                        <?php
                            }
                        }
                    }
                    echo "</div></div></li>";
                } else if (isset($_SESSION["connected"]) && $value["status"] >= 1) {
                    echo "<li id='cit" . $value["ID"] . "' class='" . ((isset($_SESSION["redirectToID"]) && $value["ID"] == $_SESSION["redirectToID"]) ? "selectedCitation" : "") . "'>
                <div class='citationZone zone'><span class='citation citationCommon'>\"" . $value["citation"] . "\"</div>
                <div class='authorDateZone zone adzCitation'>";
                    reactions($value["ID"], $db, $username);
                    echo "<div class='reactionButtonContainer'><span class='authorDate authorDateCommon'>" . $value["author"] . ", " . $formattedDate . "";
                    if (in_array($username, $promoted) || $username == $value["username"]) {
                        ?>
                        <div class="delMsgDiv">
                            <span onclick="createMessage('confirm', 'Delete citation ?', 'Are you sure you want to delete this citation?', 'deletemsg', 'Delete', 'delID', '<?php echo $value['ID']; ?>')" class="delMsgSpan">Delete</span>
                        </div>
            <?php
                    }
                    echo "</div></div></li>";
                } else if ($value["status"] >= 1) {
                    echo "<li id='cit" . $value["ID"] . "' class='" . ((isset($_SESSION["redirectToID"]) && $value["ID"] == $_SESSION["redirectToID"]) ? "selectedCitation" : "") . "'>
                <div class='citationZone zone'><span class='citation citationCommon'>\"" . $value["citation"] . "\"</div>
                <div class='authorDateZone zone adzCitation'>";
                    reactionsNotConnected($value["ID"], $db);
                    echo "<div class='reactionButtonContainer'><span class='authorDate authorDateCommon'>" . $value["author"] . ", " . $formattedDate . "";

                    echo "</div></div></li>";
                }
            }
            ?>
        </ul>
    </main>
    <script src="./scripts/common.js"></script>
    <?php

    if (isset($_SESSION["redirectToID"]) || isset($_SESSION["redirectToCitation"]) && isset($_SESSION["redirectToCitationAuthor"])) {
        echo '<script>document.getElementById("cit' . $_SESSION["redirectToID"] . '").scrollIntoView({
            behavior: "smooth",
            block: "center",
            inline: "nearest"
        });
        // window.scrollBy(0,-00); // x,y
        //window.history.pushState({}, "Citation n°' . $_SESSION["redirectToID"] . '", "/"+window.location.href.substring(window.location.href.lastIndexOf("c=' . $_SESSION["redirectToID"] . '") + 1).split("?")[0]);
        window.history.pushState({}, "Citation n°' . $_SESSION["redirectToID"] . '", location.protocol + "//" + location.host + location.pathname);
        </script>';
        unset($_SESSION["redirectToCitation"]);
        unset($_SESSION["redirectToCitationAuthor"]);
        unset($_SESSION["redirectToID"]);
    }
    ?>
    <script>
        function shareCitation(citationNumber) {
            let urlName = window.location.origin + window.location.pathname + `?c=${citationNumber}`
            createMessage("info", "URL Copiée !", `Le lien de la citation (${urlName}) a été copié dans votre presse papier !`)
            navigator.clipboard.writeText(urlName);
        }
    </script>
</body>

    </html>