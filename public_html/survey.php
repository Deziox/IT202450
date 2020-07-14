<?php
session_start();
include('header.php');
require('config.php');
include("aws_config.php");
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
        <!--<h2>results</h2>-->
        <div class="content">
        <?php
        try {
            if(!isset($_SESSION['user'])){
                header("location: login.php");
                session_abort();
            }else if(!isset($_GET['id'])){
                header("location: index.php");
            }else {
                $answered = explode(',',$_SESSION['user']['answered']);
                echo var_export($answered);

                $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
                $db = new PDO($connection_string, $dbuser, $dbpass);

                $query = "SELECT * FROM Surveys WHERE id = :id";
                $stmt = $db->prepare($query);
                $r = $stmt->execute(array(":id" => $_GET['id']));
                $s = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

                $top1_bottom1 = $s['top1_bottom1'];
                $top1_bottom2 = $s['top1_bottom2'];
                $top2_bottom1 = $s['top2_bottom1'];
                $top2_bottom2 = $s['top2_bottom2'];
                $votes = $s['votes'];

                if(in_array($_GET['id'],$answered)){
                    //results
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

                    echo '<div class="survey" id="survey_' . $s['id'] . '">';
                    echo '<h1 class="survey-title">' . $s['title'] . ' | results</h1>';
                    echo '<h3>created: ' . $s['created_at'] . '</h3>';
                    echo '<h3>tags: ' . $s['tags'] . '</h3>';
                    echo '<h4 class="result-label">total votes: '.$votes.'</h4>';
                    echo '<table class="survey-table">';

                    echo '<tr><th><h3 class="result-label">'.$s['top_1'].' + '.$s['bottom_1'].'</h3></th></tr><tr>';
                    echo '<th><img class="results-clothes" src="' . $t1 . '"></th>';
                    echo '<th><img class="results-clothes" src="' . $b1 . '"></th>';
                    echo '<th><h4 class="result-label">votes: '.$top1_bottom1.'/'.$votes.'</h4></th></tr>';

                    echo '<tr><th><h3 class="result-label">'.$s['top_1'].' + '.$s['bottom_2'].'</h3></th></tr><tr>';
                    echo '<th><img class="results-clothes" src="' . $t1 . '"></th>';
                    echo '<th><img class="results-clothes" src="' . $b2 . '"></th>';
                    echo '<th><h4 class="result-label">votes: '.$top1_bottom2.'/'.$votes.'</h4></th></tr>';

                    echo '<tr><th><h3 class="result-label">'.$s['top_2'].' + '.$s['bottom_1'].'</h3></th></tr><tr>';
                    echo '<th><img class="results-clothes" src="' . $t2 . '"></th>';
                    echo '<th><img class="results-clothes" src="' . $b1 . '"></th>';
                    echo '<th><h4 class="result-label">votes: '.$top2_bottom1.'/'.$votes.'</h4></th></tr>';

                    echo '<tr><th><h3 class="result-label">'.$s['top_2'].' + '.$s['bottom_2'].'</h3></th></tr><tr>';
                    echo '<th><img class="results-clothes" src="' . $t2 . '"></th>';
                    echo '<th><img class="results-clothes" src="' . $b2 . '"></th>';
                    echo '<th><h4 class="result-label">votes: '.$top2_bottom2.'/'.$votes.'</h4></th></tr>';

                    echo '</table>';
                }else {

                    if (!isset($_POST['top']) || !isset($_POST['bottom'])) {

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

                        echo '<div class="survey" id="survey_' . $s['id'] . '">';
                        echo '<form class="survey-form" method="post" action="survey.php?id=' . $s['id'] . '">'; //onsubmit="vote(top.value,bottom.value,'.$s['id'].')"
                        echo '<h1 class="survey-title">' . $s['title'] . '</h1>';

                        echo '<h3>created: ' . $s['created_at'] . '</h3>';
                        echo '<h3>tags: ' . $s['tags'] . '</h3>';
                        echo '<h4 class="result-label">total votes: '.$votes.'</h4>';

                        echo '<table class="survey-table">';
                        echo '<tr><th><h4 class="top">top: </h4></th></tr><tr>';
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

                        $sessionset = isset($_SESSION['user']);

                        if ($sessionset) {
                            echo '<input class="vote-button" type="submit" value="vote">';
                        } else {
                            echo '<input class="vote-button" type="button" onclick="window.location.href=\'login.php\'" value="login to vote"/>';
                        }

                        echo '</form>';
                        echo '<div id="poll' . $s['id'] . '"></div>';
                        echo '</div>';

                    } else {
                        $result = $_POST['top'] . "_" . $_POST['bottom'];

                        switch ($result) {
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
                        $votes++;

                        $stmt = $db->prepare("UPDATE Surveys SET top1_bottom1 = :top1_bottom1,
                                    top1_bottom2 = :top1_bottom2,
                                    top2_bottom1 = :top2_bottom1,
                                    top2_bottom2 = :top2_bottom2,
                                    votes = :votes WHERE id = :id");

                        $r = $stmt->execute(array(
                            ":top1_bottom1" => $top1_bottom1,
                            ":top1_bottom2" => $top1_bottom2,
                            ":top2_bottom1" => $top2_bottom1,
                            ":top2_bottom2" => $top2_bottom2,
                            ":votes" => $votes,
                            ":id" => $_GET['id']
                        ));

                        $stmt = $db->prepare("UPDATE Users SET answered = :answered WHERE id = :id");

                        $r = $stmt->execute(array(":answered" => $_SESSION['user']['answered'].','.$_GET['id'],":id"=>$_SESSION['user']['id']));
                        $_SESSION['user']['answered'] .= ','.$_GET['id'];

                        unset($_POST['top']);
                        unset($_POST['bottom']);

                    }
                }
            }
        }catch (Exception $e){
        }
        ?>
        </div>
        <?php include('footer.php');?>
    </div>
</body>
</html>