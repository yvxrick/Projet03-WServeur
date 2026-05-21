<?php
// Removes deprecation notice from strftime
error_reporting(E_ERROR);
setlocale(LC_ALL, 'fr_FR');
require_once $_SERVER['DOCUMENT_ROOT'] . "app/functions/session_manager.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "app/database/annonces.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "app/email/email.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "app/database/user.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email_to_contact = $_POST["email-to-contact"] ?? null;
    $msg = $_POST["msg"] ?? null;
    $email_author = $_POST["email-author"] ?? null;
    $ad_id = $_POST["ad_id"] ?? null;

    if ($email_to_contact == null || $msg == null || $email_author == null || $ad_id == null) {
        http_response_code(400);
        exit("Bad request.");
    }

    if (strlen($msg) < 25) {
        http_response_code(400);
        exit("Message is too short.");
    }

    $email_obj = new email();
    $ads_obj = new annonces();
    $ad_info = $ads_obj->get_ad($ad_id);
    $user_obj_author = new user($email_author);
    $user_obj_to_send = new user($email_to_contact);

    $now = strftime("%A %e %B %Y");

    $name_author = $user_obj_author->get_prenom() . " " . $user_obj_author->get_nom();
    $name_receiver = $user_obj_to_send->get_prenom();
    $ad_title = $ad_info["DescriptionAbregee"];

    $email_msg = "
<div style='font-family: Arial, sans-serif; font-size: 15px; color: #333; line-height: 1.6;'>

    <p>Bonjour <strong>$name_receiver</strong>,</p>

    <p>
        <strong>$name_author</strong> vous a contacté au sujet de votre annonce :
    </p>

    <p style='padding: 12px; background-color: #f5f5f5; border-left: 4px solid #007bff;'>
        <strong>$ad_title</strong>
    </p>

    <p>Voici son message :</p>

    <div style='padding: 15px; background-color: #fafafa; border: 1px solid #ddd; border-radius: 5px;'>
        $msg
    </div>

    <p style='margin-top: 25px; font-size: 13px; color: #777;'>
        Envoyé le $now
    </p>

</div>
";
    if ($email_obj->sendEmail($email_to_contact, "Quelqu'un vous a contacter pour votre annonce!", $email_msg)) {
        exit("OK");
    }
    http_response_code(500);
    exit("Internal server error.");
}
http_response_code(400);
echo "Only POST requests are allowed.";