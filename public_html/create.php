<?php
include("aws_config.php");
session_start();
if(!isset($_SESSION['user'])){
    header("location: index.php");
}
require('config.php');
//print_r($_FILES);
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
        $tags = $_POST['tags'];
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

        if($_POST['published'] === 'public'){
            $published = "2";
        }else if($_POST['published'] === 'private'){
            $published = "1";
        }else{
            $published = "0";
        }
    }

    //Database Managment
    if(!array_filter($errors)){

        try{
            $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
            $db = new PDO($connection_string,$dbuser,$dbpass);
            $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

            $target_dir = "images/";


            $top_1_name = $_FILES['top_1_image']['name'];
            $top_2_name = $_FILES['top_2_image']['name'];
            $bottom_1_name = $_FILES['bottom_1_image']['name'];
            $bottom_2_name = $_FILES['bottom_2_image']['name'];

            $target_top1 = $target_dir . basename($_FILES["top_1_image"]["name"]);
            $target_top2 = $target_dir . basename($_FILES["top_2_image"]["name"]);
            $target_bottom1 = $target_dir . basename($_FILES["bottom_1_image"]["name"]);
            $target_bottom2 = $target_dir . basename($_FILES["bottom_2_image"]["name"]);

            $imageFileTypeTop1 = strtolower(pathinfo($target_top1,PATHINFO_EXTENSION));
            $imageFileTypeTop2 = strtolower(pathinfo($target_top2,PATHINFO_EXTENSION));
            $imageFileTypeBottom1 = strtolower(pathinfo($target_bottom1,PATHINFO_EXTENSION));
            $imageFileTypeBottom2 = strtolower(pathinfo($target_bottom2,PATHINFO_EXTENSION));

            if(!array_filter($errors)){
                $nextId = $db->query("SHOW TABLE STATUS LIKE 'Surveys'")->fetch(PDO::FETCH_ASSOC)['Auto_increment'];

                $stmt = $db->prepare("INSERT INTO Surveys (id,user_id,title,tags,top_1,top_2,bottom_1,bottom_2,published) VALUES 
                                                                   (:id,:user_id,:title,:tags,:top_1,:top_2,:bottom_1,:bottom_2,:published)");

                $r = $stmt->execute(array(
                        ":id"=>$nextId,
                    ":user_id"=>$_SESSION['user']['id'],
                    ":title"=>$title,
                    ":tags"=>$tags,

                    ":top_1"=>$top_1,
                    ":top_2"=>$top_2,

                    ":bottom_1"=>$bottom_1,
                    ":bottom_2"=>$bottom_2,

                    ":published"=>$published
                ));

                $s3->upload($bucket, $nextId.'t1.'.$imageFileTypeTop1, fopen($_FILES['top_1_image']['tmp_name'], 'rb'),'public-read');
                $s3->upload($bucket, $nextId.'t2.'.$imageFileTypeTop1, fopen($_FILES['top_2_image']['tmp_name'], 'rb'),'public-read');
                $s3->upload($bucket, $nextId.'b1.'.$imageFileTypeTop1, fopen($_FILES['bottom_1_image']['tmp_name'], 'rb'),'public-read');
                $s3->upload($bucket, $nextId.'b2.'.$imageFileTypeTop1, fopen($_FILES['bottom_2_image']['tmp_name'], 'rb'),'public-read');

                //header("location:index.php");
                echo var_export($r);
                echo var_export($nextId);
                echo 'published: '.var_export($published);


                //trigger_error("PDO errorInfo: ".$db->errorInfo());
                echo "PDO errorInfo: ".$db->errorInfo();
            }

        }catch(Exception $e){
            echo "PDO ERROR = ".$e->getMessage();
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
            <h3>create new aesthetic</h3>
        </div>
        <form action="create.php" method="post" enctype="multipart/form-data">
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

            <label>Tags (Separated by commas)</label>
            <?php echo "<div class=\"error\">".$errors['tags']."</div>";?>
            <input type="text" name="tags"><br/>

            <label>Visibility</label>
            <!--<input type="checkbox" name="published" value="yes">--><br/>
            <select id="date_sort" name="published">
                <option value="draft">draft</option>
                <option value="private">private</option>
                <option value="public" selected>public</option>
            </select>

            <div style="margin: 20px;">
                <input class="create-button" type="submit" name="submit" value="find aesthetic">
            </div>
        </form>
    </div>
</section>
</body>
</html>
