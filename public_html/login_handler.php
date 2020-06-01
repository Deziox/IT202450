<?php
session_start();
if(isset($_SESSION['username'])){
    header("location:index.php");
    //echo "there is a session";
}

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
        $hash = password_hash($password,PASSWORD_BCRYPT);
    }

    //Database Managment
    if(!array_filter($errors)){

        try{
            $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
            $db = new PDO($connection_string,$dbuser,$dbpass);


            $stmt = $db->prepare("SELECT * FROM Users WHERE username = :username");
            $r = $stmt->execute(array(":username"=>$username));
            $userresult = $stmt->fetch(PDO::FETCH_ASSOC);
            $rpass = $userresult['password'];

            if(!$userresult){
                $errors['username'] = "Username and/or Password is invalid";
            }else{
                if(password_verify($password,$rpass)) {
                    $_SESSION['user'] = array(
                        "id"=>$userresult['id'],
                        "email"=>$userresult['email'],
                        "username"=>$userresult['username']);

                    header("location:login_success.php");
                }else{
                    $errors['password'] = "Password is invalid";
                }
            }

            echo "SELECT user result: ".var_export($userresult, true)."<br/>";

        }catch(Exception $e){
            echo "Connection failed = ".$e->getMessage();
        }
    }
}
?>