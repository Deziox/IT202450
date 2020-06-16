<?php
session_start();
require('config.php');

$errors = array('title'=>'','top_1'=>'','top_1_image'=>'','top_2'=>'','top_2_image'=>'','bottom_1'=>'','bottom_1_image'=>'','bottom_2'=>'','bottom_2_image'=>'');

if(isset($_POST['submit'])){
    if(empty($_POST['title'])){
        $errors['title'] = "A title is required for your aesthetic";
    }else if(empty($_POST['top_1'])){
        $errors['top_1'] = "A title is required for first top";
    }else if(empty($_POST['top_2'])){
        $errors['top_2'] = "A title is required for second top";
    }else if(empty($_POST['top_1'])){
        $errors['bottom_1'] = "A title is required for first bottom";
    }else if(empty($_POST['top_2'])){
        $errors['bottom_2'] = "A title is required for second bottom";
    }else {
        $title = $_POST['title'];
        $top_1 = $_POST['top_1'];
        $top_2 = $_POST['top_2'];
        $bottom_1 = $_POST['bottom_1'];
        $bottom_2 = $_POST['bottom_2'];

        if(!isset($_FILES['top_1_image']) || $_FILES['top_1_image']['size'] <= 0){
            $errors['top_1_image'] = "An image is required for first top";
        }else if(!isset($_FILES['top_2_image']) || $_FILES['top_2_image']['size'] <= 0){
            $errors['top_2_image'] = "An image is required for second top";
        }else if(!isset($_FILES['bottom_1_image']) || $_FILES['bottom_1_image']['size'] <= 0){
            $errors['bottom_1_image'] = "An image is required for first bottom";
        }else if(!isset($_FILES['bottom_2_image']) || $_FILES['bottom_2_image']['size'] <= 0){
            $errors['bottom_2_image'] = "An image is required for second bottom";
        }

        if($_POST['published'] == 'yes'){
            $published = '1';
        }else{
            $published = '0';
        }
    }

    //Database Managment
    if(!array_filter($errors)){
        //$result = $conn->query("SELECT id FROM Users WHERE email = $email");

        try{
            $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
            $db = new PDO($connection_string,$dbuser,$dbpass);

            $top_1_image = addslashes(file_get_contents($_FILES['top_1_image']['tmp_name']));
            $top_2_image = addslashes(file_get_contents($_FILES['top_2_image']['tmp_name']));
            $bottom_1_image = addslashes(file_get_contents($_FILES['bottom_1_image']['tmp_name']));
            $bottom_2_image = addslashes(file_get_contents($_FILES['bottom_2_image']['tmp_name']));
            /*$top_1_image = fopen($_FILES['top_1_image']['tmp_name'],'rb');
                $stmt->bindParam(':top_1_image',$top_1_image,PDO::PARAM_LOB);

                $top_2_image = fopen($_FILES['top_2_image']['tmp_name'],'rb');
                $stmt->bindParam(':top_2_image',$top_2_image,PDO::PARAM_LOB);

                $bottom_1_image = fopen($_FILES['bottom_1_image']['tmp_name'],'rb');
                $stmt->bindParam(':bottom_1_image',$bottom_1_image,PDO::PARAM_LOB);

                $bottom_2_image = fopen($_FILES['bottom_2_image']['tmp_name'],'rb');
                $stmt->bindParam(':bottom_2_image',$bottom_2_image,PDO::PARAM_LOB);*/
            if(!array_filter($errors)){
                $stmt = $db->prepare("INSERT INTO Surveys (user_id,title,top_1,top_1_image,top_2,top_2_image,bottom_1,bottom_1_image,bottom_2,bottom_2_image,published) VALUES 
                                                                    (:user_id,:title,:top_1,:top_1_image,:top_2,:top_2_image,:bottom_1,:bottom_1_image,:bottom_2,:bottom_2_image,:published)");
                $r = $stmt->execute(array(
                    ":user_id"=>$_SESSION['user']['id'],
                    ":title"=>$title,

                    ":top_1"=>$top_1,
                    ":top_1_image"=>$top_1_image,
                    ":top_2"=>$top_2,
                    ":top_2_image"=>$top_2_image,

                    ":bottom_1"=>$bottom_1,
                    ":bottom_1_image"=>$bottom_1_image,
                    ":bottom_2"=>$bottom_2,
                    ":bottom_2_image"=>$bottom_2_image,
                    ":published"=>$published
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
    <h2>REGISTER AN ACCOUNT</h2>
    <div class="register">
        <div class="reglog-switch">
            <h3>Create Survey Week 5 HW</h3>
        </div>
        <form action="create.php" method="post">
            <label>aesthetic title:</label>
            <?php echo "<div class=\"error\">".$errors['title']."</div>";?>
            <input type="text" name="title"><br/>

            <label>First Top</label>
            <?php echo "<div class=\"error\">".$errors['top_1']."</div>";?>
            <input type="text" name="top_1"><br/>
            <input type="file" name="top_1_image"> <?php echo "<div class=\"error\">".$errors['top_1_image']."</div>";?> <br/>

            <label>Second Top</label>
            <?php echo "<div class=\"error\">".$errors['top_2']."</div>";?>
            <input type="text" name="top_2"><br/>
            <input type="file" name="top_2_image"> <?php echo "<div class=\"error\">".$errors['top_2_image']."</div>";?> <br/>

            <label>First Bottom</label>
            <?php echo "<div class=\"error\">".$errors['bottom_1']."</div>";?>
            <input type="text" name="bottom_1"><br/>
            <input type="file" name="bottom_1_image"> <?php echo "<div class=\"error\">".$errors['bottom_1_image']."</div>";?> <br/>

            <label>Second Bottom</label>
            <?php echo "<div class=\"error\">".$errors['bottom_2']."</div>";?>
            <input type="text" name="bottom_2"><br/>
            <input type="file" name="bottom_2_image"> <?php echo "<div class=\"error\">".$errors['bottom_2_image']."</div>";?> <br/>

            <label>Published</label>
            <input type="checkbox" name="published" value="yes"><br/>

            <div style="margin: 20px;">
                <input class="register-button" type="submit" name="submit" value="find aesthetic">
            </div>
        </form>
    </div>
</section>
</body>
</html>
