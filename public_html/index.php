<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Danzel Test Site</title>
</head>
<body>
    <h1>Header One</h1>
    <?php
        function worldGreet()
        {
            echo "Hello, World!";
        }

        if(array_key_exists('button1',$_POST)){
            worldGreet();
        }
    ?>
    <input type="submit" name="button1" class="button" value="Click Me"/>
</body>
</html>