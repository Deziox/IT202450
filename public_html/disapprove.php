<?php
session_start();
require('config.php');

if(!isset($_SESSION['user'])){
    header('location: index.php');
    session_abort();
    die();
}
if($_SESSION['user']['admin'] === '0'){
    header('location: index.php');
    die();
}

try{
    $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
    $db = new PDO($connection_string,$dbuser,$dbpass);

    if(!array_filter($errors)){
        $stmt = $db->prepare("UPDATE Surveys SET approved = 0 WHERE id = :id");

        $r = $stmt->execute(array(
            ":id"=>$_POST['id']
        ));
        header("location: survey.php?id=".$_POST['id']);
    }

}catch(Exception $e){
    echo "Connection failed = ".$e->getMessage();
}
?>
