<?php
//    if(isset($_GET['submit'])){
//        echo $_GET['email'];
//    }
    if(isset($_POST['submit'])){
        if(empty($_POST['email'])){
            echo "An email is required <br/>";
        }else if(empty($_POST['username'])){
            echo "A username is required";
        }else if(empty($_POST['password'])){
            echo "Password cannot be empty";
        }else {
            echo htmlspecialchars($_POST['email']);
            echo htmlspecialchars($_POST['username']);
            echo htmlspecialchars($_POST['password']);
        }
    }
?>

<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <?php include('header.php'); ?>
        <section>
            <h2>REGISTER AN ACCOUNT</h2>
            <form action="register.php" method="post">
                <label>Email:</label>
                <input type="text" name="email">
                <label>Username:</label>
                <input type="text" name="username">
                <label>Password:</label>
                <input type="text" name="password">
                <div>
                    <input type="submit" name="submit" value="Register">
                </div>
            </form>
        </section>
        <!--<h1>REGISTER TODAY</h1>-->
    </body>
</html>
