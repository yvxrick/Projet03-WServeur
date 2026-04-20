<?php
// Load dynamically the requested page.
$page = $_GET["p"] ?? null;

switch ($page) {
    case null:
        require "public/signup.php";
        break;
    case "login":
        require "public/login.php";
        break;
    case "signup":
        require "public/signup.php";
        break;
}
