<?php
    session_start();
    if(isset($_SESSION['user'])) {
        echo "<h1>login successful, welcome ".$_SESSION['username']."</h1>";
        echo var_export($_SESSION,true);
        echo '<br /><br /><a href="logout.php">Logout</a>';
    }else{
        echo "TEST";
        //header("location:login.php");
    }
?>