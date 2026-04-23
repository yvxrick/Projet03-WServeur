<?php
require "../app/functions/session_manager.php";
require "../app/database/user.php";
logout_if_no_session();
$user_obj = new user($_SESSION["email"]);
echo "Bienvenue <br>";

