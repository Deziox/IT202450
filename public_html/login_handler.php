<?php

require('config.php');

$errors = array('username'=>'','password'=>'');

if(isset($_POST['submit'])){
    if(empty($_POST['username'])){
        $errors['username'] = "Username cannot be empty";
    }else if(empty($_POST['password'])){
        $errors['password'] = "Password cannot be empty";
    }else {
        $username = $_POST['username'];
        $password = $_POST['password'];

    }

    //Database Managment
    if(!array_filter($errors)){
        //$result = $conn->query("SELECT id FROM Users WHERE email = $email");

        try{
            $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
            $db = new PDO($connection_string,$dbuser,$dbpass);


            $stmt = $db->prepare("SELECT * FROM Users WHERE username = :username");
            $r = $stmt->execute(array(":username"=>$username));
            $userresult = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($stmt->rowCount() < 1){
                $errors['username'] = "No account associated with that email";
            }else{

            }

            echo "SELECT user result: ".var_export($userresult, true)."<br/>";

        }catch(Exception $e){
            echo "Connection failed = ".$e->getMessage();
        }

        //echo '<h1>test string</h1>';
//        if(!$row){
//            //header('Location: confirm your email address or something');
//            echo '<h1>test string</h1>';
//
////            if($conn->query("INSERT INTO Users (email, username, password) VALUES ($email, $username, $password)") === TRUE){
////                echo htmlspecialchars($_POST['email']) . "\n";
////                echo htmlspecialchars($_POST['username']) . "\n";
////                echo htmlspecialchars($_POST['password']) . "\n";
////            }else{
////                echo "Error: Failed to add user to database <br/>".$conn->error;
////            }
//
//        }else{
//            echo '<h1>test string number 2</h1>';
//        }
    }
}
?>