<?php
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
            if (!preg_match("/^[A-Z].*[A-Z].*[A-Z]$/", $password) || preg_match("/^\s.*\s.*\s$/", $password)) {
                $errors['password'] = "Password Must have at least one uppercase letter and no spaces";
            }
                //echo htmlspecialchars($_POST['email']) . "\n";
                //echo htmlspecialchars($_POST['username']) . "\n";
                //echo htmlspecialchars($_POST['password']) . "\n";
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
                <div class="error"><?php echo $errors['email'];?></div>
                <label>Username:</label>
                <input type="text" name="username">
                <div class="error"><?php echo $errors['username'];?></div>
                <label>Password:</label>
                <input type="text" name="password">
                <div class="error"><?php echo $errors['password'];?></div>
                <div>
                    <input type="submit" name="submit" value="Register">
                </div>
            </form>
        </section>
        <!--<h1>REGISTER TODAY</h1>-->
    </body>
</html>
