<?php

    if(!isset($_GET['id'])){
        header('location: index.php');
    }

    include('config.php');
    try {
        $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
        $db = new PDO($connection_string, $dbuser, $dbpass);

        $stmt = $db->prepare("SELECT * FROM Users WHERE id = :id");
        $r = $stmt->execute(array(":id" => $_GET['id']));
        $userresult = $stmt->fetch(PDO::FETCH_ASSOC);
        $rcode = $userresult['rcode'];

        if (is_null($rcode)) {
            header('location: index.php');
        } else if (substr($rcode, 0, 1) !== '_') {
            header('location: index.php');
        }

        if(isset($_POST['npass'])){
            $uppercase = preg_match('@[A-Z]@', $_POST['npass']);
            $lowercase = preg_match('@[a-z]@', $_POST['npass']);
            $number    = preg_match('@[0-9]@', $_POST['npass']);
            $specialChars = preg_match('@[^\w]@', $_POST['npass']);

            if(empty($_POST['npass'])) {
                $errors['password'] = "Password cannot be empty";
            }
            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($_POST['npass']) < 8) {
                $errors['password'] = "Password must be at least 8 characters long and should have one upper case letter, one number, and one special character.";
            }

            if(!array_filter($errors)){

                if(!array_filter($errors)){
                    $stmt = $db->prepare("UPDATE Users SET password = :password WHERE id = :user_id");
                    $hash = password_hash($_POST['npass'],PASSWORD_BCRYPT);

                    $r = $stmt->execute(array(
                        ":password"=>$hash,
                        ":user_id"=>$_GET['id']
                    ));

                    $stmt = $db->prepare("UPDATE Users SET rcode = NULL WHERE id = :user_id");
                    $r = $stmt->execute(array(":user_id"=>$_GET['id']));

                    header("location: login.php");
                }
            }
        }

    } catch (Exception $e) {
        echo "Connection failed = " . $e->getMessage();
    }
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php include('header.php'); ?>
<section class="reglog-center">
    <h2>Enter your new password</h2>
    <div class="login">

        <?php echo '<form action="password_reset.php?id='.$_GET['id'].'" method="post">' ?>
        <label>New Password:</label>
        <?php echo "<div class=\"error\">".$errors['password']."</div>";?>
        <input type="password" name="npass"><br/>
        <div style="margin: 20px;">
            <input class="login-button" type="submit" name="submit" value="submit">
        </div>
        </form>
    </div>
</section>
</body>
</html>
