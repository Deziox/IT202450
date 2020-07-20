<?php
        include("aws_config.php");
        require('config.php');
        session_start();
        if(isset($_GET['search'])){
            $searchstring = $_GET['search'];
            $query = 'SELECT * FROM Surveys WHERE tags LIKE CONCAT(\'%\',:searchstring,\'%\') AND published = 2 AND approved = 1 ORDER BY 
                            CASE
                                WHEN tags LIKE CONCAT(:searchstring,\'%\') THEN 1
                                WHEN tags LIKE CONCAT(\'%\',:searchstring) THEN 3
                                ELSE 2
                            END';
            if(isset($_SESSION['user']) && $_SESSION['user']['admin'] === '1'){
                $query = 'SELECT * FROM Surveys WHERE tags LIKE CONCAT(\'%\',:searchstring,\'%\') ORDER BY 
                            CASE
                                WHEN tags LIKE CONCAT(:searchstring,\'%\') THEN 1
                                WHEN tags LIKE CONCAT(\'%\',:searchstring) THEN 3
                                ELSE 2
                            END';
            }
            $h = 'outfits matching "'.$_GET['search'].'"';
            echo '<h1 class="content-header">'.$h.'</h1><hr>';
        }else{
            if(isset($_POST['date_sort'])){
                $sort = $_POST['date_sort'];
            }else{
                $sort = 'DESC';
            }
            $searchstring = '';
            $query = 'SELECT * FROM Surveys WHERE AND published = 2 AND approved = 1 ORDER BY created_at '.$sort;

            if(isset($_SESSION['user']) && $_SESSION['user']['admin'] === '1'){
                $query = 'SELECT * FROM Surveys ORDER BY created_at '.$sort;
            }
            $h = "recent outfits";

            echo '<h1 class="content-header">'.$h.'</h1><hr>';
            echo '<form action="/index.php" method="post">
                    <label>Sort by: </label>
                    <select id="date_sort" name="date_sort">
                      <option value="ASC"'. ($sort == 'ASC'?"selected":"") .'>ascending</option>
                      <option value="DESC"'.($sort == 'DESC'?"selected":"").'>descending</option>
                    </select>
                    <input type="submit">
                  </form>';
        }

        $sessionset = isset($_SESSION['user']);
        if($sessionset){
            $votestring = "vote";
        }else{
            $votestring = "login to vote";
        }

        try{
            $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
            $db = new PDO($connection_string,$dbuser,$dbpass);

            $stmt = $db->prepare($query);
            $r = $stmt->execute(array(":searchstring"=>$searchstring));
            $surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $s_i = 0;

            if(!$surveys){
                //echo var_export($_GET);
                echo "<h1>No Survey Results</h1>";
            }else{
                if($sessionset && $_SESSION['user']['admin'] === '0') {
                    //echo "test ".$_SESSION['user']['id']."</br>";
                    $stmt = $db->prepare("SELECT * FROM Users WHERE id = :id");
                    $r = $stmt->execute(array(":id" => $_SESSION['user']['id']));
                    $userresult = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

                    $answered = explode(',',$userresult['answered']);
                    //echo var_export($answered);
                }

                $result = $s3->listObjects(array('Bucket'=>'aestheticus'));

                if (!isset($_GET['p'])) {
                    $p = 1;
                } else {
                    $p = $_GET['p'];
                    if($p > ceil(sizeof($surveys)/2)){
                        $p = ceil(sizeof($surveys)/2);
                    }else if($p < 1){
                        $p = 1;
                    }
                }

                $offset = (((int)$p) * 2) - 2;
                $prev = $p-1;
                $next = $p+1;

                if ($offset < 0 || $offset >= sizeof($surveys)) {
                    $offset = 0;
                }
                $i = 2;

                foreach($surveys as $s) {
                    if($sessionset) {
                        //echo var_export($answered);
                        if (in_array($s['id'], $answered) && $_SESSION['user']['admin'] === '0') {
                            continue;
                        }
                    }
                    if ($offset != 0) {
                        $offset--;
                        continue;
                    }
                    if ($i == 0) {
                        break;
                    }

                    $i--;
                    $s_i++;
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
                    if($sessionset && $_SESSION['user']['admin'] === '0') {
                        echo '<form class="survey-form" method="post" action="survey.php?id=' . $s['id'] . '">'; //onsubmit="vote(top.value,bottom.value,'.$s['id'].')"
                    }else{
                        echo '<div class="survey-form">';
                    }
                    echo '<a href="survey.php?id='.$s['id'].'"><h1 class="survey-title">' . $s['title'];
                    if($s['published'] === '0'){
                        echo ' [draft]';
                    }else if($s['published'] === '1'){
                        echo ' [private]';
                    }
                    if($s['approved'] === '0'){
                        echo ' [unapproved]';
                    }
                    echo '</h1></a>';

                    $stmt = $db->prepare("SELECT * FROM Users WHERE id = :id");
                    $r = $stmt->execute(array(":id" => $s['user_id']));
                    $un = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['username'];

                    echo '<a href="profile.php?profile_id='.$s['user_id'].'"><h3>by: '.$un.'</h3></a>';

                    echo '<h3>created: '.$s['created_at'].'</h3>';
                    echo '<h3>tags: '.$s['tags'].'</h3>';
                    echo '<h4>total votes: ' . $s['votes'] . '</h4>';

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

                    if($sessionset && $_SESSION['user']['admin'] === '0') {
                        echo '<input class="vote-button" type="submit" value="vote">';
                        echo '</form>';
                    }else if($sessionset && $_SESSION['user']['admin'] === '1'){
                        echo '<input class="vote-button" type="button" onclick="window.location.href=\'survey.php?id='.$s['id'].'\'" value="edit"/>';
                        echo '</div>';
                    }else{
                        echo '<input class="vote-button" type="button" onclick="window.location.href=\'login.php\'" value="login to vote"/>';
                        echo '</div>';
                    }

                    echo '<div id="poll'.$s['id'].'"></div>';
                    echo '</div>';
                }
                if($s_i == 0){
                    echo "<h1>No Survey Results</h1>";
                }
            }
        }catch(Exception $e){
            echo "Connection failed = ".$e->getMessage();
        }
    ?>

<div class="main-arrows">
    <?php
    echo '<div style="text-align: center;"><h3>'.$p.'</h3></div>';
    if($prev > 0) {
        echo '<a href= "index.php?p=' . $prev.'" class="prev-main">&#8249;</a>';
    }
    if($next <= ceil(sizeof($surveys)/2)){
        echo '<a href= "index.php?p=' .$next.'" class="next-main">&#8250;</a>';
    }
    ?>
</div>
