<?php
require('../vendor/autoload.php');
// this will simply read AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY from env vars
$s3 = new Aws\S3\S3Client([
    'version'  => 'latest',
    'region'   => 'us-east-1',
    'credentials' => [
        'key'    => getenv('AWS_ACCESS_KEY_ID'),
        'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
    ]
]);
$bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
?>
<?php
        require('config.php');
        session_start();

        if(isset($_GET['search'])){
            $searchstring = $_GET['search'];
            $query = "SELECT * FROM Surveys WHERE tags LIKE CONCAT('%',:searchstring,'%') ORDER BY 
                            CASE
                                WHEN tags LIKE CONCAT(:searchstring,'%') THEN 1
                                WHEN tags LIKE CONCAT('%',:searchstring) THEN 3
                                ELSE 2
                            END";
            $h = 'outfits matching "'.$_GET['search'].'"';
            echo '<h1 class="content-header">'.$h.'</h1><hr>';
        }else{
            if(isset($_POST['date_sort'])){
                $sort = $_POST['date_sort'];
            }else{
                $sort = 'DESC';
            }
            $searchstring = '';
            $query = "SELECT * FROM Surveys ORDER BY created_at ".$sort;
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

            if(!$surveys){
                echo var_export($_GET);
                echo "no surveys";
            }else{
                $result = $s3->listObjects(array('Bucket'=>'aestheticus'));
                foreach($surveys as $s) {

                    foreach($result['Contents'] as $object){
                        //echo var_export($object).'\n';
                        if(strpos($object['Key'],$s['id']) === 0) {
                            if (strpos($object['Key'], 't1') !== false) {
                                $t1 = 'https://aestheticus.s3.amazonaws.com/' . $object['Key'];
                            } else if (strpos($object['Key'], 't2') !== false) {
                                $t2 = 'https://aestheticus.s3.amazonaws.com/' . $object['Key'];
                            } else if (strpos($object['Key'], 'b1') !== false) {
                                $b1 = 'https://aestheticus.s3.amazonaws.com/' . $object['Key'];
                            } else if (strpos($object['Key'], 'b2') !== false) {
                                $b2 = 'https://aestheticus.s3.amazonaws.com/' . $object['Key'];
                            }
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

                    if($sessionset) {
                        echo '<input class="vote-button" type="submit" value="vote">';
                    }else{
                        echo '<input class="vote-button" type="button" onclick="window.location.href=\'login.php\'" value="login to vote"/>';
                    }

                    echo '</form>';
                    echo '<div id="poll'.$s['id'].'"></div>';
                    echo '</div>';
                }
            }
        }catch(Exception $e){
            echo "Connection failed = ".$e->getMessage();
        }
    ?>