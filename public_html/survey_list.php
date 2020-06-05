<?php
        require('config.php');
        session_start();

        try{
            $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
            $db = new PDO($connection_string,$dbuser,$dbpass);


            $stmt = $db->prepare("SELECT * FROM Surveys ORDER BY created_at");
            $r = $stmt->execute();
            $surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(!$surveys){
                echo "no surveys";
            }else{
                foreach($surveys as $s) {
                    //echo var_export($s,true);
                    echo '<div class="survey">';
                    echo '<h1 class="survey-title">' . $s['title'] . '</h1>';
                    echo '<table style="width:100%">';
                    echo '<tr><th><h4 class="top">top: </h4></th></tr><tr style="border:solid dodgerblue;border-width:0 1px">';
                    echo '<th><img class="clothes" src="data:image/png;base64,' . base64_encode($s['top_1_image']) . '"/></th>';
                    echo '<th><img class="clothes" src="data:image/png;base64,' . base64_encode($s['top_2_image']) . '"/></th>';
                    echo '</tr>';
                    echo '<tr><th><h4 class="bottom">bottom: </h4></th></tr><tr style="border:solid dodgerblue;border-width:0 1px">';
                    echo '<th><img class="clothes" src="data:image/png;base64,' . base64_encode($s['bottom_1_image']) . '"/></th>';
                    echo '<th><img class="clothes" src="data:image/png;base64,' . base64_encode($s['bottom_2_image']) . '"/></th>';
                    echo '</tr>';
                    echo '</table>';
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