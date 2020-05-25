<?php
    require("config.php");

    $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";

    try{
        $db = new PDO($connection_string,$dbuser,$dbpass);
        $stmt = $db->prepare("DELETE from Users WHERE username = :username");
        $r = $stmt->execute(array(":username" => "username"));
        echo var_export($stmt->errorInfo(),true);
        echo var_export($r,true);

    }catch(Exception $e){
        echo $e->getMessage();
    }
?>

<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <?php include('header.php'); ?>
        <section>
            <h2>Delete Tester</h2>
            <?php echo "<div>Deleted user: $r</div>"?>
        </section>
    </body>
</html>
