<?php

require("config.php");
session_start();

if(!isset($_SESSION['user'])){
    session_abort();
    header('location: GameHW.php');
}else {
    if (!isset($_GET['id'])) {
        header('location: index.php');
    }else {


        $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";

        try {
            $db = new PDO($connection_string, $dbuser, $dbpass);

            $surveys = explode(',', $_SESSION['user']['surveys']);
            //echo var_export($surveys);
            if (!in_array($_GET['id'], $surveys)) {
                header('location: index.php');
                //echo 'test 1';
            }else {

                $stmt = $db->prepare("DELETE FROM Surveys WHERE id = :id");
                $r = $stmt->execute(array(":id" => $_GET['id']));

                unset($surveys[array_search($_GET['id'], $surveys)]);
                $_SESSION['user']['surveys'] = $surveys;

                $stmt = $db->prepare("UPDATE Users SET surveys = :surveys WHERE id = :id");
                $r = $stmt->execute(array(":surveys" => join(',', $surveys), ":id" => $_SESSION['user']['id']));

                header('location: index.php');
                //echo 'test 2';
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
