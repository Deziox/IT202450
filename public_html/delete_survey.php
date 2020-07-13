<?php
session_start();
require("config.php");

if(!isset($_SESSION['user'])){
    header('location: index.php');
    session_abort();
}

if(!isset($_GET['id'])){
    header('location: index.php');
}

$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";

try {
    $db = new PDO($connection_string, $dbuser, $dbpass);

    $surveys = explode(',',$_SESSION['user']['surveys']);
    if(!in_array($_GET['id'],$surveys)){
        header('location: index.php');
    }

    $stmt = $db->prepare("DELETE Surveys WHERE id = :id");
    $r = $stmt->execute(array(":id" => $_GET['id']));

    unset($surveys[array_search($_GET['id'],$surveys)]);
    $_SESSION['user']['surveys'] = $surveys;

    $stmt = $db->prepare("UPDATE Users SET surveys = :surveys WHERE id = :id");
    $r = $stmt->execute(array(":surveys"=>join(',',$surveys),":id" => $_SESSION['user']['id']));

} catch (Exception $e) {
    echo $e->getMessage();
}
