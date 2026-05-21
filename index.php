<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "app/functions/session_manager.php";
// Load dynamically the requested page.
$page = $_GET["p"] ?? null;
// Sends user to main page if hes already logged in.
if (user_is_logged_in()) {
    header("Location: https://projet03-wserveur.alwaysdata.net/private/");
}

switch ($page) {
    case null:
        require "public/login.php";
        break;
    case "login":
        require "public/login.php";
        break;
    case "signup":
        require "public/signup.php";
        break;
    default:
        require "public/login.php";
        break;
}
