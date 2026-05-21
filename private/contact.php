<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacter une personne</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://projet03-wserveur.alwaysdata.net/private/css/style.css?v=2" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../favicons/user.ico">
</head>

<?php
require_once "../app/functions/session_manager.php";
require_once "../app/database/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "app/database/annonces.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "app/database/categories.php";
require "./navbars/navigation_signed_in.php";
logout_if_no_session();
redirect_if_no_profile($_SESSION["email"]);
$ad_id = $_GET["id"] ?? null;
$ads_obj = new annonces();
$user_obj = new user($_SESSION["email"]);

// A user cant contact if its his own ad
if ($ads_obj->is_users_ad($user_obj->get_id(), $ad_id) || !$ads_obj->ad_exists($ad_id)) {
    header("Location: https://projet03-wserveur.alwaysdata.net/private/");
    exit;
}

$ad = $ads_obj->get_ad($ad_id);
$ad_author_email = $ad["Courriel"];


?>

<body>
    <div hidden id="ad_upload_status_msg">
            <span id="msg">Votre message a été envoyé au courriel '<?= $ad_author_email?>'. Vous allez être redirigé.</span>
            <button style="padding-left: 20px " onclick="closeStatusMsg()" type="button" class="btn-close"
                aria-label="Close"></button>
        </div>
    <div id="container">
        <h3 style="text-align: center">Contacter une personne</h3>
        <p>Email de la personne</p>
        <input disabled style="width: 250px" type="text" class="form-control" value="<?= $ad_author_email?>">
        <p>Votre message</p>
        <textarea placeholder="Votre message doit contenir minimum 25 caractères" id="msg-content" class="form-control" style="max-height: 300px; min-height: 150px;"></textarea>
        <label hidden id="err-msg" style="color: red">Votre message doit contenir minimum 25 caractères</label>
        <input id="send-msg-btn" onclick="verifyMessage()" class="btn btn-primary mt-2" type="button" value="Envoyer ↑">
        <a href="https://projet03-wserveur.alwaysdata.net/private" class="btn btn-dark"> Retour ←</a>
    </div>
    <script>
        let err_msg = document.getElementById("err-msg")
        let status_msg = document.getElementById("ad_upload_status_msg")
        let btn_send = document.getElementById("send-msg-btn")
        function verifyMessage() {
            let msg = document.getElementById("msg-content").value
            let email_to_contact = "<?=$ad_author_email?>"
            if (msg.length < 25) {
                err_msg.hidden = false
            } else {
                let formData = new FormData;
                formData.append("email-to-contact", email_to_contact)
                formData.append("msg", msg)
                formData.append("email-author", "<?=$_SESSION["email"]?>")
                formData.append("ad_id", "<?=$ad_id?>")
                err_msg.hidden = true
                sendMessage(formData)
            }
        }
        function sendMessage(formData) {
            btn_send.disabled = true
            btn_send.value = "En cours d'envoie..."
            fetch("https://projet03-wserveur.alwaysdata.net/app/auth/send_contact_message.php", {
                method: "POST",
                body: formData
            }).then((response) => response.text())
            .then((response) => showResponse(response))
        }

        function showResponse(response) {
            const status = {
                OK: "OK",
                MSG_TOO_SHORT: "Message is too short."
            }
            switch (response) {
                case status.OK:
                    status_msg.hidden = false;
                    btn_send.value = "Envoyé"
                    setTimeout(() => {
                        window.location.href = "https://projet03-wserveur.alwaysdata.net/private/"
                    }, 2000)
                    break;
                case status.MSG_TOO_SHORT:
                    btn_send.disabled = false
                    btn_send.value = "Envoyer ↑"
                    alert("Votre message est trop court.")
                    break;
            }
        }

        function closeStatusMsg() {
            status_msg.hidden = true
        }
    </script>
</body>
</html>

