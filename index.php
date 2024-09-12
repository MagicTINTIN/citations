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
} else if (isset($_POST["deletemsg"]) && isset($_POST["delID"])) {
    $sqlQuery = 'UPDATE citations SET status = :status WHERE ID = :ID';

    $updatePlates = $db->prepare($sqlQuery);
    $updatePlates->execute([
        'ID' => htmlspecialchars($_POST["delID"]),
        'status' => 0
    ]);

    header("Refresh:0");
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

    <script src="./scripts/commonhead.js"></script>
    <link href="./styles/animations.css" rel="stylesheet">
    <link href="./styles/vars.css" rel="stylesheet">
    <link href="./styles/common.css" rel="stylesheet">
    <meta name="author" content="MagicTINTIN">
    <meta name="description" content="Un site pour recenser les pépites entendues en CM">

    <link rel="icon" type="image/x-icon" href="images/favicon.png">

    <meta property="og:type" content="website" />
    <meta property="og:title" content="Citations Magistrales">
    <meta property="og:description" content="Un site pour recenser les pépites entendues en CM">

    <meta property="og:image" content="https://etud.insa-toulouse.fr/~serviere/citations/images/favicon.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:alt" content="Logo of Citations Magistrales">

    <meta property="og:url" content="https://etud.insa-toulouse.fr/~serviere/ProjectSupport/<?php echo $websiteTagSuffix ?>" />
    <meta data-react-helmet="true" name="theme-color" content="#FAA916" />
</head>

<body>
<?php include_once("./includes/nojs.php"); ?>
<?php include_once("./includes/infoanderror.php"); ?>
    <main>
        <ul>
            <?php
            $db = dbConnect();
            $citationsStatement = $db->prepare('SELECT * FROM citations');
            $citationsStatement->execute();
            $citations = $citationsStatement->fetchAll();

            foreach ($citations as $key => $value) {
                if ($value["status"] == 1) {
                    echo "<li>
                <div class='citationZone'><span class='citation citationCommon'>\"" . $value["citation"] . "\"</div>
                <div class='authorDateZone'><span class='authorDate authorDateCommon'>" . $value["author"] . " - " . $value["date"] . "</div>";
                    if (in_array($username, array('serviere', 'v_lasser')) || $username == $value["username"]) {
            ?>
                        <div class="delMsgDiv">
                            <span onclick="createMessage('confirm', 'Delete citation ?', 'Are you sure you want to delete this citation?', 'deletemsg', 'Delete', 'delID', '<?php echo $value['ID']; ?>')" class="delMsgSpan">Delete</span>
                        </div>
            <?php
                    }
                    echo "</li>";
                }
            }
            ?>
        </ul>
        <form method="post" class="citationForm">
            <textarea oninput="autoGrow(this)" class="citationInput citationCommon" name="citationInput" id="citationInput" required maxlength="1024"></textarea>
            <br>
            <input type="text" class="authorDateInput authorDateCommon" name="authorInput" id="authorInput" required maxlength="250">
            <input type="date" class="authorDateInput authorDateCommon" id="start" name="dateInput" value="<?php echo date('Y-m-d') ?>" required>
            <br>
            <input type="submit" class="citationSubmit" id="newCitationSubmit" value="Add citation" name="newCitationSubmit">

        </form>
    </main>
    <script src="./scripts/common.js"></script>
</body>

</html>