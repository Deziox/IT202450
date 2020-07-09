<?php
session_start();
    if(isset($_SESSION['user'])){
        $logreg = $_SESSION['user']['username'];
        $logregdatatarget = "profile";
    }else{
        $logreg = "login/register";
        $logregdatatarget = "register";
    }
?>

<head>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Space+Mono">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/content.css">
    <link rel="stylesheet" href="css/surveys.css">
    <link rel="stylesheet" href="css/login.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
           //trigger/container variables
           var trigger = $('.header-right'), container = $('.content');

           //fire on click
           trigger.on('click','#nav',function(){
               var $this = $(this), target = $this.data('target');
               container.load(target + '.php');
               document.getElementsByClassName("active").item(0).removeAttribute("class");
               document.getElementsByName(target).item(0).className = "active";
               document.getElementsByTagName("title").item(0).innerHTML = "aestheticus|" + target;
               return false;
           });
        });
    </script>
</head>
<div class="header">
    <a id="nav" href="index.php" data-target="survey_list" class="logo">a e s t h e t i c u s</a>
    <div class="header-right">
        <a id="nav" name="survey_list" href="index.php" data-target="survey_list" class="active">home</a>
        <a id="nav" name="outfits" href="search.php">outfits</a>
        <?php
            if(isset($_SESSION['user'])) {
                echo '<a id="nav" name="create" href="#" data-target="create">create</a>';
                echo '<a id="nav" name="profile" href="#" data-target="profile">'.$_SESSION["user"]["username"].'</a>';
            }else{
                echo '<a id="nav" name="register" href="register.php">login/register</a>';
            }
        ?>
    </div>
</div>