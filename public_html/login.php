<?php
include('login_handler.php');
?>

<!DOCTYPE html>
<html>
<head></head>
<body>
<?php include('header.php'); ?>
<section>
    <h2>Login</h2>
    <div>
        <h1>Don't Have an account?</h1>
        <input type="button" onclick="window.location.href='register.php'" value="Register"/>
    </div>
    <form action="login.php" method="post">
        <label>Username:</label>
        <?php echo "<div class=\"error\">".$errors['username']."</div>";?>
        <input type="text" name="username"><br/>
        <label>Password:</label>
        <?php echo "<div class=\"error\">".$errors['password']."</div>";?>
        <input type="password" name="password"><br/>
        <div>
            <input type="submit" name="submit" value="Login">
        </div>
    </form>
</section>
</body>
</html>
