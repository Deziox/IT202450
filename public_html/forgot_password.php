<?php
    if(isset($_POST['submit'])){
        if(empty($_POST['email'])){
            $errors['email'] = "Email cannot be empty";
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Not a Valid Email";
        }

        if(!array_filter($errors)){
            include('mailer-config.php');
            $alphnum = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $rcode = '';
            for($i = 0; $i < 6; $i++){
                $random_char = $alphnum[mt_rand(0,strlen($alphnum) - 1)];
                $rcode .= $random_char;
            }

            $mail->Subject = 'a e s t h e t i c u s password reset code';
            $mail->Body = '<html>
                                <h1>your password reset code: </h1>
                                '.$rcode.'
                           </html>';
            $mail->AddAddress($_POST['email']);

            $mail->send();
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php include('header.php'); ?>
<section class="reglog-center">
    <h2>Login</h2>
    <div class="login">

        <form action="login.php" method="post">
            <label>Forgot your password? Enter your email: </label>
            <?php echo "<div class=\"error\">".$errors['email']."</div>";?>
            <input type="text" name="email"><br/>
            <div style="margin: 20px;">
                <input class="login-button" type="submit" name="submit" value="send code">
            </div>
        </form>
    </div>
</section>
</body>
</html>
