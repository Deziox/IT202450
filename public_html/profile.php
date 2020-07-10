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

        if(!isset($userresult['username'])){header("location: page_not_found.php");}
        $uname = $userresult['username'];
        echo $uname;

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
<head><link rel="stylesheet" href="css/profile.css"></head>
<body>
    <?php include("header.php");?>

    <div class="profile-container">
        <?php echo '<h3 class="profile-name">'.$uname.'</h3>'?>
        <div class="profile-img"><?php echo '<img class="profile-img" src="'.$profile_img.'"';?></div>
        <div class="profile-bio"></div>
    </div>

    <div class="reglog-center">
        <input class="login-button redtext" type="button" onclick="window.location.href='logout.php'" value="logout"/>
    </div>
</body>
</html>