<?php
    include('login_handler.php');
?>

<!DOCTYPE html>
<html>
<head>
    <script>
        $(document).ready(function(){
            //trigger/container variables
            var trigger = $('.login'), container = $('.content');

            //fire on click
            trigger.on('click','.register-button',function(){
                var $this = $(this), target = $this.data('target');
                container.load(target + '.php');
                return false;
            });
        });
    </script>
</head>
<body>
<?php //include('header.php'); ?>
<section>
    <h2>Login</h2>
    <div class="login">
        <h4>Don't Have an account?</h4>
        <!--<input type="button" onclick="window.location.href='register.php'" value="Register"/>-->
        <a class="register-button" href="#" data-target="register">register</a>
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
    </div>
</section>
</body>
</html>
