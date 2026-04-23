<?php
$page = basename(__FILE__, ".php");
require_once "../app/functions/session_manager.php";
require_once "../app/database/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "app/database/annonces.php";
require "./navbars/navigation_signed_in.php";

$user_email = $_SESSION["email"];
$user_id = $_SESSION["user_id"];

$user_obj = new user($user_email);
$ads_obj = new annonces();

logout_if_no_session();
redirect_if_no_profile($user_email);

$user_obj = new user($user_email);
$user_fname = $user_obj->get_prenom();
$user_lname = $user_obj->get_nom();

$ads = $ads_obj->get_all_cards_ads();
annonces::sortByDDP_DESC($ads);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://projet03-wserveur.alwaysdata.net/private/css/style.css" rel="stylesheet">
</head>
<h3>Bonjour, <?php echo $user_fname ?>! </h3>
<div class="ads-wrapper">
    <div class="ads-container">
        <?php echo $ads_obj->load_cards_ads_html($ads); ?>
    </div>
</div>

<body>

</body>

</html>