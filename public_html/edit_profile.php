<?php
require('config.php');
session_start();
if(!isset($_SESSION['user'])) {
    header("location: index.php");
    session_abort();
    die();
}
include("aws_config.php");
$profile_id = $_SESSION['user']['id'];

try{
    $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
    $db = new PDO($connection_string,$dbuser,$dbpass);


    $stmt = $db->prepare("SELECT * FROM Users WHERE id = :id");
    $r = $stmt->execute(array(":id"=>$profile_id));
    $userresult = $stmt->fetch(PDO::FETCH_ASSOC);

    $uname = $userresult['username'];
    $bio = $userresult['bio'];

}catch(Exception $e){
    echo "Connection failed = ".$e->getMessage();
}

$result = $s3->listObjects(array('Bucket'=>'aestheticus'));

$profile_img = 'https://aestheticus.s3.amazonaws.com/defaultprofile.jpg';
foreach($result['Contents'] as $object){
    //echo var_export($object).'\n';
    if (strpos($object['Key'],$profile_id.'_profile') !== false) {
        $profile_img = 'https://aestheticus.s3.amazonaws.com/' . $object['Key'];
        break;
    }
}

?>
<html>
<head>
    <link rel="stylesheet" href="css/profile.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<?php include("header.php");?>

<form action="edit_profile.php" method="post" enctype="multipart/form-data">

<div class="profile-container">
    <h3 style="text-align: center;text-decoration: underline;">username:</h3>
    <?php echo '<h1 class="profile-name"><input type="text" name="username" value="'.$uname.'" style="text-align: center;font-size: 30px;"></h1>'?>
    <div class="profile-img-edit"><?php echo '<img class="profile-img" src="'.$profile_img.'"';?>
        <?php echo "<div class=\"error\">".$errors['profile-img']."</div>";?>
        <div style="text-align: center;margin-top: 3%;">
            <input type="file" name="profile-img">
        </div>
    </div>
    <div class="profile-bio-edit"><h3 style="text-align: center;text-decoration: underline;">bio:</h3><?php echo '<h3 class="profile-bio"><textarea name="bio" rows="8" cols="80" style="text-align: center;font-size: 20px;">'.$bio.'</textarea></h3>'?></div>
</div>

    <div style="margin: 30px; text-align: center;">
        <input class="create-button" type="submit" name="submit" value="save changes">
    </div>

</form>

<?php
$query = "SELECT * FROM Surveys WHERE user_id = :user_id ORDER BY created_at DESC";

$h = "edit your outfits:";
try{
//        $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
//        $db = new PDO($connection_string,$dbuser,$dbpass);

    $stmt = $db->prepare($query);
    $r = $stmt->execute(array(":user_id"=>$profile_id));
    $surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(!$surveys){
        $h = "you have no created outfits";
        echo '<h1 class="content-header">'.$h.'</h1><hr>';
    }else {
        echo '<h1 class="content-header">' . $h . '</h1><hr>';
        $result = $s3->listObjects(array('Bucket' => 'aestheticus'));

        foreach ($surveys as $s) {
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

            echo '<a href="edit_survey.php?id=' . $s['id'] . '"><div class="survey" id="survey_' . $s['id'] . '">';
            echo '<table class="survey-table">';
            //echo '<div style="margin-left: 94%; margin-top: 1.25%;">
            //        <a href="#" id="delete-survey"><img src="images/1214428.svg" style="height: 5%;"></a>
            //        </div>';
            echo '<tr> <h1 class="profile-survey-title">' . $s['title'];
            if($s['published'] === '0'){
                echo ' [draft]';
            }
            echo '</h1>
                        <th>
                            <img class="profile-clothes" src="' . $t1 . '">
                        </th>
                        <th>
                            <img class="profile-clothes" src="' . $t2 . '">
                        </th>
                        <th>
                            <img class="profile-clothes" src="' . $b1 . '">
                        </th>
                        <th>
                            <img class="profile-clothes" src="' . $b2 . '">
                        </th>
                      </tr>';
            echo '<tr><h3>created: ' . $s['created_at'] . '</h3></tr>';
            echo '<tr><h3>tags: ' . $s['tags'] . '</h3></tr>';

            echo '</table>';
            echo '<div id="poll' . $s['id'] . '"></div>';
            echo '</div></a>';
        }
    }

    }catch(Exception $e){
        echo "Connection failed = ".$e->getMessage();
    }
    ?>

    <?php include('footer.php'); ?>
</body>
</html>