<?php
$login_page_link = "https://projet03-wserveur.alwaysdata.net/index.php?p=login";
/**
 * Logs out a user by destroying his session.
 * @return bool
 */
function logout_user()
{
    session_start();
    session_unset();
    session_destroy();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    return true;
}
/**
 * Starts the user's session.
 * @param $id User's id
 * @return bool
 */
function login_user($id = null, $email = null) {
    if ($id == null || $email == null) {
        return false;
    }

    session_start();
    $_SESSION["user_id"] = $id;
    $_SESSION["email"] = $email;
    return true;
}

/**
 * Logs out a user if he dosen't have an outgoing session. If that's the case, user is sent to login page.
 * @return bool Returns `True` if he was logged out, else `False`
 */
function logout_if_no_session() {
    global $login_page_link;
    session_start();
    if (!(isset($_SESSION["user_id"]))) {
        logout_user();
        header("Location: $login_page_link");
        return true;
    }
    return false;
}

/**
 * Returns a boolean indicating if the user is already logged in, meaning he has an outgoing session.
 * @return bool
 */
function user_is_logged_in() {
    session_start();
    if (isset($_SESSION) && count($_SESSION) > 0) {
        return true;
    }
    return false;
}