<?php
require('config.php');
session_start();
if(!isset($_GET['profile_id'])){
    if(isset($_SESSION['user'])) {
        header("location: profile.php?profile_id=".$_SESSION['user']['id']);
    }else{
        header("location: index.php");
    }
}
include("aws_config.php");
$profile_id = $_GET['profile_id'];

try{
    $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
    $db = new PDO($connection_string,$dbuser,$dbpass);


    $stmt = $db->prepare("SELECT * FROM Users WHERE id = :id");
    $r = $stmt->execute(array(":id"=>$profile_id));
    $userresult = $stmt->fetch(PDO::FETCH_ASSOC);
    if($userresult['id'] !== $_GET['profile_id']){
        header("location: page_not_found.php");
    }

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

<div class="profile-container">
    <?php echo '<h1 class="profile-name">'.$uname.'</h1>'?>
    <div class="profile-img"><?php echo '<img class="profile-img" src="'.$profile_img.'"';?></div>
    <div class="profile-bio"><?php echo '<h3 class="profile-bio">'.$bio.'</h3>'?></div>
    <?php
    if(isset($_SESSION['user'])) {
        if($_SESSION['user']['id'] === $_GET['profile_id']) {
            echo '
                <div class="reglog-center" >
                    <input class="login-button redtext" type = "button" onclick = "window.location.href=\'edit_profile.php?profile_id='.$profile_id.'\'" value = "edit profile" />
                </div >
                <div class="reglog-center" >
                    <input class="login-button redtext" type = "button" onclick = "window.location.href=\'logout.php\'" value = "logout" />
                </div >';
        }
    }
    ?>
</div>

<?php
if(isset($_GET['profile_id']))

    $query = "SELECT * FROM Surveys WHERE user_id = :user_id AND (published = 2 OR published = 1) AND approved = 1 ORDER BY created_at DESC";
if(isset($_SESSION['user'])) {
    if($_SESSION['user']['id'] === $_GET['profile_id']) {
        $query = "SELECT * FROM Surveys WHERE user_id = :user_id ORDER BY created_at DESC";
    }
}
$h = $uname."'s outfits";

try{
//        $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
//        $db = new PDO($connection_string,$dbuser,$dbpass);

    $stmt = $db->prepare($query);
    $r = $stmt->execute(array(":user_id"=>$profile_id));
    $surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(!$surveys){
        $h = $uname." has no public outfits";
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

            echo '<div class="survey" id="survey_' . $s['id'] . '">';
            echo '<table class="survey-table">';
            echo '<div style="margin-left: 94%; margin-top: 1.25%;">
                    <a href="#"><img src="images/1214428.svg" style="height: 5%;"></a>
                    </div>';
            echo '<tr> <h1 class="profile-survey-title">' . $s['title'] . '</h1>
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
            echo '</div>';
        }
    }

    }catch(Exception $e){
        echo "Connection failed = ".$e->getMessage();
    }
    ?>

    <?php include('footer.php'); ?>
</body>
</html>