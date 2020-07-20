<?php

require("config.php");
session_start();

if(!isset($_SESSION['user'])){
    session_abort();
    header('location: index.php');
}else {
    if (!isset($_GET['id'])) {
        header('location: index.php');
    }else {


        $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";

        try {
            $db = new PDO($connection_string, $dbuser, $dbpass);

            $stmt = $db->prepare("SELECT * FROM Surveys WHERE id = :id");
            $r = $stmt->execute(array(":id" => $_GET['id']));
            $set = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(sizeof($set) < 1){
                header('location: index.php');
                die();
            }
            $s = $set[0];

            if($s['user_id'] !== $_SESSION['user']['id']){
                header('location: index.php');
                die();
            }

            $stmt = $db->prepare("DELETE FROM Surveys WHERE id = :id");
            $r = $stmt->execute(array(":id" => $_GET['id']));

            header('location: index.php');
            //echo 'test 2';
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
