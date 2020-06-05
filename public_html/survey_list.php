<div class="survey">

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
                echo '<table style="width:100%">';
                foreach($surveys as $s){
                    //echo var_export($s,true);
                    echo '<tr>';
                    echo '<th><img class="clothes" src="data:image/png;base64,'.base64_encode($s['top_1_image']).'"/></th>';
                    echo '<th><img class="clothes" src="data:image/png;base64,'.base64_encode($s['top_2_image']).'"/></th>';
                    echo '</tr>';
                }
                echo '</table>';
            }
        }catch(Exception $e){
            echo "Connection failed = ".$e->getMessage();
        }
    ?>

    <div class="top">top</div>
    <div class="bottom">bottom</div>
</div>