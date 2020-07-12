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
<head><link rel="stylesheet" href="css/profile.css"></head>
<body>
    <?php include("header.php");?>

    <div class="profile-container">
        <?php echo '<h1 class="profile-name">'.$uname.'</h1>'?>
        <div class="profile-img"><?php echo '<img class="profile-img" src="'.$profile_img.'"';?></div>
        <div class="profile-bio"><?php echo '<h3 class="profile-bio">'.$bio.'</h3>'?></div>
    </div>

    <?php
    if(isset($_GET['profile_id']))
        $query = "SELECT * FROM Surveys WHERE user_id = :user_id ORDER BY created_at DESC";
        $h = $uname."'s outfits";
        echo '<h1 class="content-header">'.$h.'</h1><hr>';

    try{
//        $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
//        $db = new PDO($connection_string,$dbuser,$dbpass);

        $stmt = $db->prepare($query);
        $r = $stmt->execute(array(":user_id"=>$profile_id));
        $surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(!$surveys){
            echo var_export($surveys)."\n";
            echo var_export($_GET);
            echo "no surveys";
        }else{
            $result = $s3->listObjects(array('Bucket'=>'aestheticus'));
            foreach($surveys as $s) {

                unset($b1,$b2,$t1,$t2);
                foreach($result['Contents'] as $object){
                    //echo var_export($object).'\n';
                    if (strpos($object['Key'],$s['id'].'t1') !== false) {
                        $t1 = 'https://aestheticus.s3.amazonaws.com/' . $object['Key'];
                    } else if (strpos($object['Key'],$s['id'].'t2') !== false) {
                        $t2 = 'https://aestheticus.s3.amazonaws.com/' . $object['Key'];
                    } else if (strpos($object['Key'],$s['id'].'b1') !== false) {
                        $b1 = 'https://aestheticus.s3.amazonaws.com/' . $object['Key'];
                    } else if (strpos($object['Key'],$s['id'].'b2') !== false) {
                        $b2 = 'https://aestheticus.s3.amazonaws.com/' . $object['Key'];
                    }
                }

                echo '<div class="survey" id="survey_'.$s['id'].'">';
                echo '<form class="survey-form" method="post" action="results.php?id='.$s['id'].'">'; //onsubmit="vote(top.value,bottom.value,'.$s['id'].')"
                echo '<h1 class="survey-title">' . $s['title'] . '</h1>';

                echo '<h3>created: '.$s['created_at'].'</h3>';
                echo '<h3>tags: '.$s['tags'].'</h3>';

                echo '<table class="survey-table">';
                echo '<tr><th><h4 class="top">top: </h4></th></tr><tr">';
                echo '<th><img class="clothes" src="' . $t1 . '"></th>';
                echo '<th><img class="clothes" src="' . $t2 . '"></th>';

                echo '</tr><tr>';
                echo '<th><input type="radio" id="top1" name="top" value="top1"></th>';
                echo '<th><input type="radio" id="top2" name="top" value="top2"></th></tr>';

                echo '<tr><th><h4 class="bottom">bottom: </h4></th></tr><tr>';
                echo '<th><img class="clothes" src="' . $b1 . '"></th>';
                echo '<th><img class="clothes" src="' . $b2 . '"></th>';

                echo '</tr><tr>';
                echo '<th><input type="radio" id="bottom1" name="bottom" value="bottom1"></th>';
                echo '<th><input type="radio" id="bottom2" name="bottom" value="bottom2"></th></tr>';

                echo '</table>';
                echo '</form>';
                echo '<div id="poll'.$s['id'].'"></div>';
                echo '</div>';
            }
        }
    }catch(Exception $e){
        echo "Connection failed = ".$e->getMessage();
    }
    ?>
    <?php
        if(isset($_SESSION['user'])) {
            if($_SESSION['user']['id'] === $_GET['profile_id']) {
                echo '
                <div class="reglog-center" >
                    <input class="login-button redtext" type = "button" onclick = "window.location.href=\'logout.php\'" value = "logout" />
                </div >';
            }
        }
    ?>
</body>
</html>