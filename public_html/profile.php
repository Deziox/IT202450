<?php
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
<head></head>
<body>
    <?php include("header.php");?>

    <div class="profile-container">
        <div class="profile-img"><?php echo '<img class="profile-img" src="'.$profile_img.'"';?></div>
        <div class="profile-bio"></div>
    </div>

    <div class="reglog-center">
        <input class="login-button redtext" type="button" onclick="window.location.href='logout.php'" value="logout"/>
    </div>
</body>
</html>