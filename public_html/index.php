<?php

include('weapons.php');

function worldGreet()
{
    echo "Hello, World!";
}

if(array_key_exists('worldgreet',$_POST)){
    worldGreet();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Danzel Test Site</title>
</head>
<body>
    <h1>Header One</h1>
    <input type="submit" name="worldgreet" class="button" value="Click Me"/>
</body>
</html>