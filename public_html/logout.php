<?php
    include("header.php");

    session_start();
    session_unset();
    session_destroy();
    echo "<h1 class='reglog-center'>logged out successfully</h1>";

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    include("footer.php");
?>