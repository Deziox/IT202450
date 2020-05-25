<?php
    require("config.php");

    $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";

    try{
        $db = new PDO($connection_string,$dbuser,$dbpass);
        $stmt = $db->prepare("UPDATE Users set email=:email, username=:username, password=:password WHERE username = :username");

        $u = 'username';
        $stmt->bindValue(":email","totallyreal@emailchanged.com");
        $stmt->bindValue(":username",$u);
        $stmt->bindValue(":password","Totallysecurechangedpassword8!");
        $r = $stmt->execute();
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
            <?php echo "<div>Updated user $u</div>"?>
        </section>
    </body>
</html>
