<?php
    include('register_handler.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <script>
            $(document).ready(function(){
                //trigger/container variables
                var trigger = $('.register'), container = $('.content');

                //fire on click
                trigger.on('click','.login-button',function(){
                    var $this = $(this), target = $this.data('target');
                    container.load(target + '.php');
                    return false;
                });
            });
        </script>
    </head>
    <body>
        <?php //include('header.php'); ?>
        <section class="reglog-center">
            <h2>REGISTER AN ACCOUNT</h2>
            <div class="register">
                <div class="reglog-switch">
                    <h1>Already Have an account?</h1>
                    <!--<input type="button" onclick="window.location.href='login.php'" value="Login"/>-->
                    <a class="login-button" href="#" data-target="login">login</a>
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
                    <div style="margin: 20px;">
                        <input class="register-button" type="submit" name="submit" value="register">
                    </div>
                </form>
            </div>
        </section>
    </body>
</html>
