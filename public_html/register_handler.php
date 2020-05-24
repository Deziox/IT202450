<?php

require('config.php');
//$conn = new mysqli($dbhost,$dbuser,$dbpass,$dbdatabase);
//if($conn->connect_error){
//    die("Connection failed: ".$conn->connect_error);
//}

try {
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbdatabase", $dbuser, $dbpass);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}

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
        if(true){
            //header('Location: confirm your email address or something');
            echo '<h1>test string<h1/>';

//            if($conn->query("INSERT INTO Users (email, username, password) VALUES ($email, $username, $password)") === TRUE){
//                echo htmlspecialchars($_POST['email']) . "\n";
//                echo htmlspecialchars($_POST['username']) . "\n";
//                echo htmlspecialchars($_POST['password']) . "\n";
//            }else{
//                echo "Error: Failed to add user to database <br/>".$conn->error;
//            }

        }else{
            echo '<h1>test string number 2<h1/>';
        }
    }
}
?>