<?php
session_start();
$_SESSION = [];

/* Clears the session cookie.
If sessions are stored using cookies, then continue to delete that cookie.*/
if (ini_get("session.use_cookies")) {

    //This function returns all details about how PHP's session cookie was originally created.
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

//fully removes the session from the server.
session_destroy();

//sends the user back to the login page.
header("Location: login.php");

//ensures the script stops immediately after redirect.
exit;

/* the session cookie might still exist in the browser.That cookie could
allow the browser to reconnect to the same session on the server if it
wasn't deleted. So this block makes sure: Delete that cookie from the user's
browser so the session cannot be reused — even accidentally. */