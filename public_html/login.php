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
                document.getElementsByTagName("title").item(0).innerHTML = "aestheticus|" + target;
                return false;
            });


        });
    </script>
</head>
<body>
<?php //include('header.php'); ?>
<section class="reglog-center">
    <h2>Login</h2>
    <div class="login">
        <div class="reglog-switch">
            <h3>Don't Have an account?</h3>
            <!--<input type="button" onclick="window.location.href='register.php'" value="Register"/>-->
            <a class="register-button" href="#" data-target="register">register</a>
        </div>

        <form action="index.php" method="post">
            <label>Username:</label>
            <?php echo "<div class=\"error\">".$errors['username']."</div>";?>
            <input type="text" name="username"><br/>
            <label>Password:</label>
            <?php echo "<div class=\"error\">".$errors['password']."</div>";?>
            <input type="password" name="password"><br/>
            <div style="margin: 20px;">
                <input class="login-button" type="submit" name="submit" value="login">
            </div>
        </form>
    </div>
</section>
</body>
</html>
