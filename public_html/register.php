<?php
    if(isset($_GET['submit'])){
        echo $_GET['email'];

    }
?>

<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <?php include('header.php'); ?>
        <section>
            <h2>REGISTER AN ACCOUNT</h2>
            <form action="register.php" method="get">
                <label>Email:</label>
                <input type="text" name="email">
                <label>Username:</label>
                <input type="text" name="username">
                <label>Password:</label>
                <input type="text" name="password">
                <div>
                    <input type="submit" name="submit" value="Register">
                </div>
            </form>
        </section>
        <!--<h1>REGISTER TODAY</h1>-->
    </body>
</html>
