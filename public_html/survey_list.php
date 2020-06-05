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
            foreach($surveys as $s){
                echo var_export($s,true);
            }
        }
    }catch(Exception $e){
        echo "Connection failed = ".$e->getMessage();
    }
?>

<div class="survey">
    <div class="top">top</div>
    <div class="bottom">bottom</div>
</div>