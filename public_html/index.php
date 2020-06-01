<?php

include('header.php');

if(isset($_SESSION['user'])){
    if(!isset($_SESSION['welcome'])) {
        $_SESSION['welcome'] = "<h1>login successful, welcome " . $_SESSION['user']['username'] . "</h1><br/>" . var_export($_SESSION, true) . '<br /><br /><a href="logout.php">Logout</a>';
    }else{
        $_SESSION['welcome'] = "<div></div>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Danzel Test Site</title>
</head>
<body>
    <h1>Header One</h1>
    <input type="button" onclick="window.location.href='register.php'" value="Click Me To Register"/>
    <?php echo "<div>".$_SESSION['welcome']."</div>";?>
</body>
</html>