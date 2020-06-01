<?php
    session_start();
    if(isset($_SESSION['user'])) {
        header("location:index.php");
    }else{
        echo "TEST";
        //header("location:login.php");
    }
?>