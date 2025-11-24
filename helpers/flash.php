<?php
// Set a flash message
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,     // success, danger, warning, info
        'message' => $message
    ];
}

// Display & clear the flash message
function showFlash() {
    if (!empty($_SESSION['flash'])) {
        $type = $_SESSION['flash']['type'];
        $msg  = $_SESSION['flash']['message'];

        echo "<div class='alert alert-$type flash-message'>$msg</div>";

        unset($_SESSION['flash']);
    }
}
