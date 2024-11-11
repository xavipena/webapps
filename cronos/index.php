<?php
include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";

    // Header for top level
    $dd = "";
    $dd .= "     _   _                  _       _   _           _   _             _ ".PHP_EOL;
    $dd .= "  __| | (_)   __ _   _ __  (_)   __| | (_)   __ _  (_) | |_    __ _  | |".PHP_EOL;
    $dd .= " / _` | | |  / _` | | '__| | |  / _` | | |  / _` | | | | __|  / _` | | |".PHP_EOL;
    $dd .= "| (_| | | | | (_| | | |    | | | (_| | | | | (_| | | | | |_  | (_| | | |".PHP_EOL;
    $dd .= " \__,_| |_|  \__,_| |_|    |_|  \__,_| |_|  \__, | |_|  \__|  \__,_| |_|".PHP_EOL;
    $dd .= "                                            |___/                       ".PHP_EOL;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Diari Digital * Cronos</title>  

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Diari Digital">
    <meta name="abstract" content="Diari digital Album. Fotografia, Viatges, Cami de Sant jaume, Cims i rutes">
    <meta name="keywords" content="Fotografia, Viatges, Cami de Sant jaume, Cims i rutes">
    <meta name="lang" content="<?php echo $lang?>_ES">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./css/cronos.css">
    <link rel="stylesheet" href="./css/cover.css">
    <script type="text/javascript" src="./js/cronos.js"></script>
    <!--
<?php echo $dd;?>
    -->
</head>
<body>
    <?php // loader to wait while next page loads 
    include "./includes/loader.inc.php";
    ?>

    <div class="logo"></div>
    <div class="container">
        <h1>CRONOS</h1>
        <h2>Navegador d'imatges</h2>
    </div>
    <div class="bbottom">
        <button class="pushable" onclick="javascript:CallPage('<?php echo $element?>','main.php');">
            <span class="shadow"></span>
            <span class="edge"></span>
            <span class="front">Comença</span>
        </button>
    </div>
</body>
</html>