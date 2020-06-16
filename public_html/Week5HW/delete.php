<?php
session_start();
require('config.php');

$errors = array('title'=>'','surveyid'=>'');

if(isset($_POST['submit'])){
    if(empty($_POST['surveyid'])){
        $errors['top_1'] = "A survey id is required";
    }else {
        $surveyid = $_POST['surveyid'];
    }

    //Database Managment
    if(!array_filter($errors)){
        //$result = $conn->query("SELECT id FROM Users WHERE email = $email");

        try{
            $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
            $db = new PDO($connection_string,$dbuser,$dbpass);

            if(!array_filter($errors)){
                $stmt = $db->prepare("DELETE FROM Surveys WHERE user_id = :user_id AND id = :surveyid");

                $r = $stmt->execute(array(
                    ":user_id"=>$_SESSION['user']['id'],
                    ":surveyid"=>$surveyid
                ));
                header("location:../index.php");
            }

        }catch(Exception $e){
            echo "Connection failed = ".$e->getMessage();
        }
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
    <div class="register">
        <div class="reglog-switch">
            <h3>Delete Survey Week 5 HW</h3>
        </div>
        <form action="delete.php" method="post" enctype="multipart/form-data">

            <label>survey id:</label>
            <?php echo "<div class=\"error\">".$errors['surveyid']."</div>";?>
            <input type="text" name="surveyid"><br/>

            <div style="margin: 20px;">
                <input class="register-button" type="submit" name="submit" value="delete">
            </div>
        </form>
    </div>
</section>
</body>
</html>
