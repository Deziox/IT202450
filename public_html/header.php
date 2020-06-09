<?php
    if(isset($_SESSION['user'])){
        $logreg = $_SESSION['user']['username'];
    }else{
        $logreg = "login/register";
    }
?>

<head>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Space+Mono">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/content.css">
    <link rel="stylesheet" href="css/surveys.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
           //trigger/container variables
           var trigger = $('.header-right'), container = $('.content');

           //fire on click
           trigger.on('click','#nav',function(){
               var $this = $(this), target = $this.data('target');
               container.load(target + '.php');
               return false;
           });
        });
    </script>
</head>
<div class="header">
    <a id="nav" href="index.php" data-target="survey-list" class="logo">a e s t h e t i c u s</a>
    <div class="header-right">
        <a id="nav" href="index.php" data-target="survey-list" class="active">home</a>
        <a id="nav" href="#" data-target="outfits">outfits</a>
        <?php echo '<a id="nav" href="#" data-target="register">'.$logreg.'</a>'?>
    </div>
</div>