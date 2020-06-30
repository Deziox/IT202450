<?php
session_start();
include('header.php');

if(isset($_SESSION['user'])){
    //echo "test 1";
}

if(isset($_SESSION['invalid_password'])) {
    $indexlink = 'login';
    unset($_SESSION['invalid_password']);
}else{
    $indexlink = 'survey_list';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>aestheticus|home</title>
    <script src="js/voting.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <!--<input type="button" onclick="window.location.href='register.php'" value="Click Me To Register"/>-->
    <?php //echo "<div>".$_SESSION['welcome']."</div>";?>
    <div class="container">
        <div class="content">
            <?php include($indexlink.".php");?>
        </div>
        <?php include('footer.php');?>
    </div>
</body>
</html>