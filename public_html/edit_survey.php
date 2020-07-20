<?php
include("aws_config.php");
session_start();
if(!isset($_SESSION['user'])){
    header("location: index.php");
    session_abort();
    die();
}
require('config.php');
//print_r($_FILES);
$errors = array('title'=>'','top_1'=>'','top_1_image'=>'','top_2'=>'','top_2_image'=>'','bottom_1'=>'','bottom_1_image'=>'','bottom_2'=>'','bottom_2_image'=>'');

if(!isset($_GET['id'])){
    header("location: index.php");
    die();
}

$id = $_GET['id'];

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
        $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
        $tags = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);
        $top_1 = filter_var($_POST['top_1'], FILTER_SANITIZE_STRING);
        $top_2 = filter_var($_POST['top_2'], FILTER_SANITIZE_STRING);
        $bottom_1 = filter_var($_POST['bottom_1'], FILTER_SANITIZE_STRING);
        $bottom_2 = filter_var($_POST['bottom_2'], FILTER_SANITIZE_STRING);

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
            //$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

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
                //$nextId = $db->query("SHOW TABLE STATUS LIKE 'Surveys'")->fetch(PDO::FETCH_ASSOC)['Auto_increment'] + 1;

                $stmt = $db->prepare("UPDATE Surveys SET (title,tags,top_1,top_2,bottom_1,bottom_2,published,approved) VALUES 
                                                                   (:title,:tags,:top_1,:top_2,:bottom_1,:bottom_2,:published,0)");

                $r = $stmt->execute(array(
                    ":title"=>$title,
                    ":tags"=>$tags,

                    ":top_1"=>$top_1,
                    ":top_2"=>$top_2,

                    ":bottom_1"=>$bottom_1,
                    ":bottom_2"=>$bottom_2,

                    ":published"=>$published
                ));

                if($_FILES['top_1_image']['size'] > 0) {
                    $s3->upload($bucket, $id . 't1.' . $imageFileTypeTop1, fopen($_FILES['top_1_image']['tmp_name'], 'rb'), 'public-read');
                }
                if($_FILES['top_2_image']['size'] > 0) {
                    $s3->upload($bucket, $id . 't2.' . $imageFileTypeTop1, fopen($_FILES['top_2_image']['tmp_name'], 'rb'), 'public-read');
                }
                if($_FILES['bottom_1_image']['size'] > 0) {
                    $s3->upload($bucket, $id . 'b1.' . $imageFileTypeTop1, fopen($_FILES['bottom_1_image']['tmp_name'], 'rb'), 'public-read');
                }
                if($_FILES['bottom_2_image']['size'] > 0) {
                    $s3->upload($bucket, $id . 'b2.' . $imageFileTypeTop1, fopen($_FILES['bottom_2_image']['tmp_name'], 'rb'), 'public-read');
                }
                header("location: GameHW.php");
                die();
//                echo var_export($r);
//                echo var_export($nextId);
//                echo 'published: '.var_export($published);


                //trigger_error("PDO errorInfo: ".$db->errorInfo());
                //echo "PDO errorInfo: ".$db->errorInfo();
            }

        }catch(Exception $e){
            echo "PDO ERROR = ".$e->getMessage();
        }
    }
}

try {

//    header("location: GameHW.php");
//    die();
    $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
    $db = new PDO($connection_string,$dbuser,$dbpass);

        $query = "SELECT * FROM Surveys WHERE id = :id";
        $stmt = $db->prepare($query);
        $r = $stmt->execute(array(":id" => $_GET['id']));
        $set = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(sizeof($set) < 1){
            header('location: index.php');
            die();
        }

        $s = $set[0];

        $result = $s3->listObjects(array('Bucket' => 'aestheticus'));

        unset($b1, $b2, $t1, $t2);
        foreach ($result['Contents'] as $object) {
            //echo var_export($object).'\n';
            if (strpos($object['Key'], $s['id'] . 't1') !== false) {
                $t1 = 'https://aestheticus.s3.amazonaws.com/' . $object['Key'];
            } else if (strpos($object['Key'], $s['id'] . 't2') !== false) {
                $t2 = 'https://aestheticus.s3.amazonaws.com/' . $object['Key'];
            } else if (strpos($object['Key'], $s['id'] . 'b1') !== false) {
                $b1 = 'https://aestheticus.s3.amazonaws.com/' . $object['Key'];
            } else if (strpos($object['Key'], $s['id'] . 'b2') !== false) {
                $b2 = 'https://aestheticus.s3.amazonaws.com/' . $object['Key'];
            }
        }


}catch (Exception $e){
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
            <h3>edit aesthetic</h3>
        </div>
        <?php echo '<form action="edit_survey.php?id='.$id.'" method="post" enctype="multipart/form-data">'?>
            <label>aesthetic title:</label>
            <?php echo "<div class=\"error\">".$errors['title']."</div>";?>
            <input type="text" name="title" value="<?php echo $s['title']?>"><br/>

            <div class="survey">
            <label>First Top</label>
            <?php echo "<div class=\"error\">".$errors['top_1']."</div>";?>
            <input type="text" name="top_1" value="<?php echo $s['top_1']?>"><br/>
            <img src="<?php echo $t1;?>" class="clothes" style="height: 100px;">
            <input type="file" name="top_1_image"> <?php echo "<div class=\"error\">".$errors['top_1_image']."</div>";?></div> <br/>

            <label>Second Top</label>
            <?php echo "<div class=\"error\">".$errors['top_2']."</div>";?>
            <input type="text" name="top_2" value="<?php echo $s['top_2']?>"><br/>
            <img src="<?php echo $t2;?>" class="clothes" style="height: 100px;">
            <input type="file" name="top_2_image"> <?php echo "<div class=\"error\">".$errors['top_2_image']."</div>";?> <br/>

            <div class="survey">
            <label>First Bottom</label>
            <?php echo "<div class=\"error\">".$errors['bottom_1']."</div>";?>
            <input type="text" name="bottom_1" value="<?php echo $s['bottom_1']?>"><br/>
            <img src="<?php echo $b1;?>" class="clothes" style="height: 100px;">
            <input type="file" name="bottom_1_image"> <?php echo "<div class=\"error\">".$errors['bottom_1_image']."</div>";?></div><br/>

            <label>Second Bottom</label>
            <?php echo "<div class=\"error\">".$errors['bottom_2']."</div>";?>
            <input type="text" name="bottom_2" value="<?php echo $s['bottom_2']?>"><br/>
            <img src="<?php echo $b2;?>" class="clothes" style="height: 100px;">
            <input type="file" name="bottom_2_image"> <?php echo "<div class=\"error\">".$errors['bottom_2_image']."</div>";?> <br/>

            <label>Tags (Separated by commas)</label>
            <?php echo "<div class=\"error\">".$errors['tags']."</div>";?>
            <input type="text" name="tags" value="<?php echo $s['tags']?>"><br/>

            <label>Visibility</label>
            <!--<input type="checkbox" name="published" value="yes">--><br/>
            <select id="date_sort" name="published">
                <option value="draft" <?php echo ($s['published'] == '0'?'selected':'');?>>draft</option>
                <option value="private" <?php echo ($s['published'] == '1'?'selected':'');?>>private</option>
                <option value="public" <?php echo ($s['published'] == '2'?'selected':'');?>>public</option>
            </select>

            <div style="margin: 20px;">
                <input class="create-button" type="submit" name="submit" value="find aesthetic">
            </div>
        </form>

        <div style="margin: 10px;">
            <?php echo '<input class="delete-button" type="submit"  value="delete aesthetic" onclick="window.location.href=\'delete_survey.php?id='.$s['id'].'\'">';?>
        </div>
    </div>
</section>
</body>
</html>
