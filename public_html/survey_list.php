<?php
        require('config.php');
        session_start();

        try{
            $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
            $db = new PDO($connection_string,$dbuser,$dbpass);


            $stmt = $db->prepare("SELECT * FROM Surveys ORDER BY created_at DESC");
            $r = $stmt->execute();
            $surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(!$surveys){
                echo "no surveys";
            }else{
                foreach($surveys as $s) {
                    //echo var_export($s,true);
                    echo '<div class="survey" id="survey_'.$s['id'].'">';
                    echo '<form method="post" onsubmit="vote(top.value,bottom.value,'.$s['id'].')">';
                    echo '<h1 class="survey-title">' . $s['title'] . '</h1>';
                    echo '<table class="survey-table">';
                    echo '<tr><th><h4 class="top">top test: </h4></th></tr><tr">';
                    echo '<th><img class="clothes" src="data:image/png;base64,' . base64_encode($s['top_1_image']) . '"/></th>';
                    echo '<th><img class="clothes" src="data:image/png;base64,' . base64_encode($s['top_2_image']) . '"/></th>';

                    echo '</tr><tr>';
                    echo '<th><input type="radio" id="top1" name="top" value="top1"></th>';
                    echo '<th><input type="radio" id="top2" name="top" value="top2"></th></tr>';

                    echo '<tr><th><h4 class="bottom">bottom: </h4></th></tr><tr>';
                    echo '<th><img class="clothes" src="data:image/png;base64,' . base64_encode($s['bottom_1_image']) . '"/></th>';
                    echo '<th><img class="clothes" src="data:image/png;base64,' . base64_encode($s['bottom_2_image']) . '"/></th>';

                    echo '</tr><tr>';
                    echo '<th><input type="radio" id="bottom1" name="bottom" value="bottom1"></th>';
                    echo '<th><input type="radio" id="bottom2" name="bottom" value="bottom2"></th></tr>';

                    echo '</table>';
                    echo '<input type="submit" value="Submit">';
                    echo '</form>';
                    echo '<div id="poll'.$s['id'].'"></div>';
                    echo '</div>';
                }
            }
        }catch(Exception $e){
            echo "Connection failed = ".$e->getMessage();
        }
    ?>
<!--
<div class="survey">
    <div class="top">top</div>
    <div class="bottom">bottom</div>
</div>-->