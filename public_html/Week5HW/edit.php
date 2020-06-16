<?php
session_start();
require('config.php');

$errors = array('title'=>'','surveyid'=>'');

if(isset($_POST['submit'])){
    if(empty($_POST['title'])){
        $errors['title'] = "A title is required for your aesthetic";
    }else if(empty($_POST['surveyid'])){
        $errors['top_1'] = "A title is required for first top";
    }else {
        $title = $_POST['title'];
        $surveyid = $_POST['surveyid'];
    }

    //Database Managment
    if(!array_filter($errors)){
        //$result = $conn->query("SELECT id FROM Users WHERE email = $email");

        try{
            $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
            $db = new PDO($connection_string,$dbuser,$dbpass);

            if(!array_filter($errors)){
                $stmt = $db->prepare("UPDATE Surveys SET title = :title WHERE user_id = :user_id AND id = :surveyid");


                $r = $stmt->execute(array(
                    ":title"=>$title,
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
            <h3>Edit Title Week 5 HW</h3>
        </div>
        <form action="create.php" method="post" enctype="multipart/form-data">

            <label>survey id:</label>
            <?php echo "<div class=\"error\">".$errors['surveyid']."</div>";?>
            <input type="text" name="surveyid"><br/>

            <label>new title:</label>
            <?php echo "<div class=\"error\">".$errors['title']."</div>";?>
            <input type="text" name="title"><br/>

            <div style="margin: 20px;">
                <input class="register-button" type="submit" name="submit" value="update title">
            </div>
        </form>
    </div>
</section>
</body>
</html>
