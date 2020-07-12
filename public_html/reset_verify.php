<?php
    if(!isset($_GET['id'])){
        header('location: index.php');
    }

    include('config.php');
    try{
        $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
        $db = new PDO($connection_string,$dbuser,$dbpass);

        $stmt = $db->prepare("SELECT * FROM Users WHERE id = :id");
        $r = $stmt->execute(array(":id"=>$_GET['id']));
        $userresult = $stmt->fetch(PDO::FETCH_ASSOC);
        $rcode = $userresult['rcode'];

        if(is_null($rcode)){
            header('location: index.php');
        }

    }catch(Exception $e){
        echo "Connection failed = ".$e->getMessage();
    }


    if(isset($_POST['submit'])){

        if(empty($_POST['code'])){
            $errors['code'] = "The code is obviously not empty...";
            $_POST['tries']++;
        }else if ($rcode !== $_POST['code']) {
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
    <h2>Verify</h2>
    <div class="login">

        <?php echo '<form action="reset_verify.php?id='.$_GET['id'].'" method="post">' ?>
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
