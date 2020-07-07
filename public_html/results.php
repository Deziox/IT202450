<?php
session_start();
include('header.php');
require('config.php');
if(!isset($_SESSION['user'])){
    header("location: index.php");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>aestheticus|home</title>
    <script src="js/voting.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>results</h2>
        <div class="content">
        <?php
        try {
            $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
            $db = new PDO($connection_string,$dbuser,$dbpass);

            $query = "SELECT * FROM Surveys WHERE id = :id";
            $stmt = $db->prepare($query);
            $r = $stmt->execute(array(":id"=>$_GET['id']));
            $s = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

            $top1_bottom1 = $s['top1_bottom1'];
            $top1_bottom2 = $s['top1_bottom2'];
            $top2_bottom1 = $s['top2_bottom1'];
            $top2_bottom2 = $s['top2_bottom2'];

            if (!isset($_POST['top']) || !isset($_POST['bottom'])) {

                echo '<div class="survey" id="survey_' . $s['id'] . '">';
                echo '<form class="survey-form" method="post" action="results.php?id=' . $s['id'] . '">'; //onsubmit="vote(top.value,bottom.value,'.$s['id'].')"
                echo '<h1 class="survey-title">' . $s['title'] . '</h1>';

                echo '<h3>created: ' . $s['created_at'] . '</h3>';
                echo '<h3>tags: ' . $s['tags'] . '</h3>';

                echo '<table class="survey-table">';
                echo '<tr><th><h4 class="top">top: </h4></th></tr><tr">';
                echo '<th><img class="clothes" src="' . $s['top_1_image'] . '"/></th>';
                echo '<th><img class="clothes" src="' . $s['top_2_image'] . '"/></th>';

                echo '</tr><tr>';
                echo '<th><input type="radio" id="top1" name="top" value="top1"></th>';
                echo '<th><input type="radio" id="top2" name="top" value="top2"></th></tr>';

                echo '<tr><th><h4 class="bottom">bottom: </h4></th></tr><tr>';
                echo '<th><img class="clothes" src="' . $s['bottom_1_image'] . '"/></th>';
                echo '<th><img class="clothes" src="' . $s['bottom_2_image'] . '"/></th>';

                echo '</tr><tr>';
                echo '<th><input type="radio" id="bottom1" name="bottom" value="bottom1"></th>';
                echo '<th><input type="radio" id="bottom2" name="bottom" value="bottom2"></th></tr>';

                echo '</table>';

                if ($sessionset) {
                    echo '<input class="vote-button" type="submit" value="vote">';
                } else {
                    echo '<input class="vote-button" type="button" onclick="window.location.href=\'login.php\'" value="login to vote"/>';
                }

                echo '</form>';
            } else {
                $result = $_POST['top']."_".$_POST['bottom'];

                switch($result){
                    case "top1_bottom1":
                        $top1_bottom1++;
                        break;
                    case "top1_bottom2":
                        $top1_bottom2++;
                        break;
                    case "top2_bottom1":
                        $top2_bottom1++;
                        break;
                    case "top2_bottom2":
                        $top2_bottom2++;
                        break;
                }

                $stmt = $db->prepare("UPDATE Surveys SET top1_bottom1 = :top1_bottom1,
                                    top1_bottom2 = :top1_bottom2,
                                    top2_bottom1 = :top2_bottom1,
                                    top2_bottom2 = :top2_bottom2 WHERE id = :id");

                $r = $stmt->execute(array(
                    ":top1_bottom1"=>$top1_bottom1,
                    ":top1_bottom2"=>$top1_bottom2,
                    ":top2_bottom1"=>$top2_bottom1,
                    ":top2_bottom2"=>$top2_bottom2,
                    ":id"=>$_GET['id']
                ));

                unset($_POST['top']);
                unset($_POST['bottom']);
            }
        }catch (Exception $e){

        }
        ?>
        </div>
        <?php include('footer.php');?>
    </div>
</body>
</html>