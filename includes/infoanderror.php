<div id="popUpErrorMessage" role="alert" class="popUpMessage">
    <div class="popUpText">
        <span id="contentErrorTitle" class="popUpTitle"></span>
        <span id="contentErrorMessage" class="popUpContent"></span>
    </div>
    <span id="popUpErrorMessageClose" class="closePopUp" onclick="closeMsg(this)"><span>X</span></span>
</div>
<div id="popUpInfoMessage" role="alert" class="popUpMessage">
    <div class="popUpText">
        <span id="contentInfoTitle" class="popUpTitle"></span>
        <span id="contentInfoMessage" class="popUpContent"></span>
    </div>
    <span id="popUpInfoMessageClose" class="closePopUp" onclick="closeMsg(this)"><span>X</span></span>
</div>
<div id="popUpConfirmMessage" role="alert" class="popUpMessage">
    <div class="popUpText">
        <span id="contentConfirmTitle" class="popUpTitle"></span>
        <span id="contentConfirmMessage" class="popUpContent"></span>

        <form class='confirmForm' method="post">
            <input type="hidden" id="hiddenInput" name="" value="" required>
            <input type="submit" id="confirmInput" class="popUpConfirmSub popUpButton" value="" name="">
            <span class="cancelPopUp popUpButton" id="popUpConfirmMessageCncel" onclick="closeMsg(this)"><span>Cancel</span></span>
        </form>
    </div>
    <span id="popUpConfirmMessageClose" class="closePopUp" onclick="closeMsg(this)"><span>X</span></span>
</div>
<script>
    function closeMsg(toclose) {
        document.getElementById(toclose.id.slice(0, -5)).style.transform = "scale(0)";
    }

    function createMessage(type, title, message = "", action = "", btnName="Confirm", name = "", value = "") {
        typeid = type[0].toUpperCase() + type.substr(1).toLowerCase();
        let popup = document.getElementById(`popUp${typeid}Message`)
        document.getElementById(`content${typeid}Title`).innerText = title.toUpperCase();

        if (message != "")
            document.getElementById(`content${typeid}Message`).innerText = '\n' + message;

        document.getElementById("hiddenInput").name = name;
        document.getElementById("hiddenInput").value = value;

        document.getElementById("confirmInput").name = action;
        document.getElementById("confirmInput").value = btnName;

        popup.style.display = "flex";
        popup.style.transform = "scale(1) rotate(0deg)";
        animateCSS(`popUp${typeid}Message`, `anim${typeid}`, 800);
    }

    <?php
    if (isset($_SESSION["error"])) {
        echo 'createMessage("error", "' . $_SESSION["error"] . (isset($_SESSION["detailedError"]) ? '", "' . $_SESSION["detailedError"]  . '"' : '"') . ');';
        unset($_SESSION["error"], $_SESSION["detailedError"]);
    }
    if (isset($_SESSION["info"])) {
        echo 'createMessage("info", "' . $_SESSION["info"] . (isset($_SESSION["detailedInfo"]) ? '", "' . $_SESSION["detailedInfo"]  . '"' : '"') . ');';
        unset($_SESSION["info"], $_SESSION["detailedInfo"]);
    }
    ?>
</script>