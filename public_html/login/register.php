<?php
    include('register_handler.php');
?>

<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <?php include('../header.php'); ?>
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
    </body>
</html>
