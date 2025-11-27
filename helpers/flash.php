<?php
function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = [
        'type'    => $type,
        'message' => $message
    ];
}

function showFlash(): void {
    if (!empty($_SESSION['flash'])) {
        $type = htmlspecialchars($_SESSION['flash']['type']);
        $msg  = htmlspecialchars($_SESSION['flash']['message']);

        echo "<div class='alert alert-$type flash-message'>$msg</div>";

        unset($_SESSION['flash']);
    }
}
