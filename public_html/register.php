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

            $alphanum = preg_match("/^[a-zA-Z0-9]$/");
            $specialChars = preg_match("@[^\w]@");

            if (!$alphanum || !$specialChars || strlen($password) < 8) {
                $errors['password'] = "Password must have at least one special character and be at least 8 characters long";
            }
                //echo htmlspecialchars($_POST['email']) . "\n";
                //echo htmlspecialchars($_POST['username']) . "\n";
                //echo htmlspecialchars($_POST['password']) . "\n";
        }

        if(!array_filter($errors)){
            header('Location: confirm your email address or something');
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
                <div class="error"><?php echo $errors['email'];?></div>
                <input type="text" name="email"><br/>
                <label>Username:</label>
                <div class="error"><?php echo $errors['username'];?></div>
                <input type="text" name="username"><br/>
                <label>Password:</label>
                <div class="error"><?php echo $errors['password'];?></div>
                <input type="text" name="password"><br/>
                <div>
                    <input type="submit" name="submit" value="Register">
                </div>
            </form>
        </section>
        <!--<h1>REGISTER TODAY</h1>-->
    </body>
</html>
