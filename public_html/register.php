<?php
    include('register_handler.php');
?>

<!DOCTYPE html>
<html>
    <head>

    </head>
    <body>
        <?php //include('header.php'); ?>
        <section>
            <h2>REGISTER AN ACCOUNT</h2>
            <div>
                <h1>Already Have an account?</h1>
                <input type="button" onclick="window.location.href='login.php'" value="Login"/>
            </div>
            <form action="register.php" method="post">
                <label>Email:</label>
                <?php echo "<div class=\"error\">".$errors['email']."</div>";?>
                <input type="text" name="email"><br/>
                <label>Username:</label>
                <?php echo "<div class=\"error\">".$errors['username']."</div>";?>
                <input type="text" name="username"><br/>
                <label>Password:</label>
                <?php echo "<div class=\"error\">".$errors['password']."</div>";?>
                <input type="password" name="password"><br/>
                <div>
                    <input type="submit" name="submit" value="Register">
                </div>
            </form>
        </section>
    </body>
</html>
