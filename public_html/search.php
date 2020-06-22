<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php include('header.php'); ?>
<section class="reglog-center">
    <h2>search</h2>
    <div class="login">
        <div class="reglog-switch">
            <h1>searching for specific tags</h1>
        </div>
        <form action="index.php" method="get">
            <label>search: </label>
            <input type="text" name="search"><br/>
            <div style="margin: 20px;">
                <input class="login-button" type="submit" name="submit" value="search">
            </div>
        </form>
    </div>
</section>
</body>
