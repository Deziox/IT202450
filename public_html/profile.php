<?php
    session_start();
    if(!isset($_GET['profile_id'])){
        if(isset($_SESSION['user'])) {
            header("location: profile.php?profile_id=".$_SESSION['user']['id']);
        }else{
            header("location: index.php");
        }
    }
    include("aws_config.php");
    $profile_id = $_GET['profile_id'];
?>
<html>
<head></head>
<body>
    <?php include("header.php");?>

    <div class="reglog-center">
        <input class="login-button redtext" type="button" onclick="window.location.href='logout.php'" value="logout"/>
    </div>
</body>
</html>