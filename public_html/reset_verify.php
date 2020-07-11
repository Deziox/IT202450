<?php
if(isset($_POST['submit'])){
    if(!isset($_POST['rcode'])){
        header('location: index.html');
    }

    if(empty($_POST['email'])){
        $errors['code'] = "The code is obviously not empty...";
        $_POST['tries']++;
    }else if ($_POST['rcode'] !== $_POST['code']) {
        $errors['code'] = "Incorrect verification code";
        $_POST['tries']++;
    }

    if(!array_filter($errors)){
        header('location: GameHW.php');
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

        <form action="reset_verify.php" method="post">
            <label>Verify your password reset code:</label>
            <?php echo "<div class=\"error\">".$errors['code']."</div>";?>
            <input type="text" name="code"><br/>
            <div style="margin: 20px;">
                <input class="login-button" type="submit" name="submit" value="verify">
            </div>
        </form>
    </div>
</section>
</body>
</html>
