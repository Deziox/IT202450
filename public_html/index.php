<?php
session_start();
include('header.php');

if(isset($_SESSION['user'])){
    //echo "test 1";
    if(!isset($_SESSION['welcome'])) {
        //$_SESSION['welcome'] = "<h1>login successful, welcome " . $_SESSION['user']['username'] . "</h1><br/>" . var_export($_SESSION, true) . '<br /><br /><a href="logout.php">Logout</a>';
        //echo "test 2";
    }else{
        //$_SESSION['welcome'] = '<br /><br /><a href="logout.php">Logout</a>';
        //echo "test 3";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>aestheticus|home</title>
    <script src="js/voting.js"></script>
</head>
<body>
    <!--<input type="button" onclick="window.location.href='register.php'" value="Click Me To Register"/>-->
    <?php //echo "<div>".$_SESSION['welcome']."</div>";?>
    <div class="container">
        <div class="content">

            <?php include('survey_list.php');?>

        </div>
        <?php include('footer.php');?>
    </div>
</body>
</html>