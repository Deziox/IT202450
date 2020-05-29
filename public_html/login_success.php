<?php
    if(isset($_SESSION['username'])) {
        echo "<h1>login successful, welcome ".$_SESSION['username']."</h1>";
        echo '<br /><br /><a href="logout.php">Logout</a>';
    }else{
        header("location:login.php");
    }
?>