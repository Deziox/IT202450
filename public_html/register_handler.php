<?php

require('config.php');

$errors = array('email'=>'','username'=>'','password'=>'');

if(isset($_POST['submit'])){
    if(empty($_POST['email'])){
        $errors['email'] = "An email is required";
    }else if(empty($_POST['username'])){
        $errors['username'] = "A username is required";
    }else if(empty($_POST['password'])){
        $errors['password'] = "Password cannot be empty";
    }else {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Not a Valid Email";
        }

        if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
            $errors['username'] = "Username cannot have any special symbols";
        }

        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            $errors['password'] = "Password must be at least 8 characters long and should have one upper case letter, one number, and one special character.";
        }
    }

    //Database Managment
    if(!array_filter($errors)){
        //$result = $conn->query("SELECT id FROM Users WHERE email = $email");

        try{
            $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
            $db = new PDO($connection_string,$dbuser,$dbpass);

            $stmt = $db->prepare("SELECT * FROM Users WHERE email = :email");
            $r = $stmt->execute(array(":email"=>$email));
            $emailresult = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($stmt->rowCount() > 0){
                $errors['email'] = "Email already exists";
            }

            $stmt = $db->prepare("SELECT * FROM Users WHERE username = :username");
            $r = $stmt->execute(array(":username"=>$username));
            $userresult = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($stmt->rowCount() > 0){
                $errors['username'] = "Username already taken, choose another";
            }

            //echo "SELECT email result: ".var_export($emailresult, true)."<br/>";
            //echo "SELECT user result: ".var_export($userresult, true)."<br/>";

            if(!array_filter($errors)){
                $stmt = $db->prepare("INSERT INTO Users (email,username,password) VALUES (:email,:username,:password)");
                $hash = password_hash($password,PASSWORD_BCRYPT);
                $r = $stmt->execute(array(
                    ":email"=>$email,
                    ":username"=>$username,
                    ":password"=>$hash
                ));
                header("location:login.php");
            }

        }catch(Exception $e){
            echo "Connection failed = ".$e->getMessage();
        }
    }
}
?>