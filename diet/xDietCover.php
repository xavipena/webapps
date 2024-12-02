<?php
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $page = "diet";
    include './includes/dbConnect.inc.php';
    include './includes/googleSecurity.inc.php';
    include './includes/googleHeader.inc.php';
    include "./includes/settingsStart.inc.php";
    include "./includes/sideMenuHover_1.inc.php";
?>
    <style>
        main {
            background-repeat: no-repeat;
            background: linear-gradient(to top, transparent , #1f1f1f 50%), url("./images/pexels-rpphotography-693794.jpg");
            background-size: cover;
        }
    </style>
</head>
<body>
<?php 
    //--- Settings ------------------------

    $_SESSION['pageID'] = PAGE_DIET_COVER;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";
     
    //--- new content -------------------- 
     
    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

    if ($lang == "ca") {

        $startHere  = "Segueix aquests passos per començar a entrar les teves dades i la teva dieta per a veure la despesa calòrica.".
                    "<br><br>".
                    "Calcula la teva TMB i informa dels àpats que fas per a fer un seguiment.";
        $logIn      = "Si estàs registrat, identifica't per gestionar les teves dades. O crea u compte per començar.";
        $logged     = "Entra al menú principal de l'app per començar a entrar els teus àpats avui.";
        $mobil      = "Des del mòbil, menú principal per entrar els àpats de forma més directa. Accedeix des del mòbil per https://diaridigital.net/diet/apps";

        $cover_1a = "Inici";    $cover_1b = "Comença aquí";
        $cover_2a = "Menú";     $cover_2b = "Identifica't";
        $cover_3a = "Mites";    $cover_3b = "";
        $cover_4a = "Mòbil";    $cover_4b = "Des del mòbil";
    }
    else {

        $startHere  = "sigue estos pasos para empezar a entrar tus datos y tu dieta para ver el gasto calórico.".
                    "<br><br>".
                    "Calcula tu TMB e informa de las comidas que realizas para realizar un seguimiento.";
        $logIn      = "Si estás registrado, identifícate para gestionar tus datos. O crea una cuenta para empezar.";
        $logged     = "Entra en el menú principal de la app para empezar a entrar tus comidas hoy.";
        $mobil      = "Desde el móvil, menú principal para entrar las comidas de forma más directa. Accede desde el móvil por https://diaridigital.net/diet/apps";

            $cover_1a = "Inicio";   $cover_1b = "Empieza aquí";
            $cover_2a = "Menú";     $cover_2b = "Identifícate";
            $cover_3a = "Mitos";    $cover_3b = "";
            $cover_4a = "Móvil";    $cover_4b = "Desde el móvil";
    }
    $user = "";

    echo "<div class='cardContainer'>";
    CoverCard("xDietManual.php",$cover_1a,$cover_1b,$startHere);

    if (isset($_SESSION['diet_user'])) {

        if ($_SESSION['diet_user'] != "") {
    
            if (!empty($_SESSION['username'])) {
            
                $user = $_SESSION['username'];
            }
        }
    }
    if ($user != "") 
    {
        CoverCard("xDiet.php","Menú","Hola, ".$user,$logged,"bright");
    }
    else 
    {
        CoverCard("xDietUser_1.php","Log In",$cover_2b,$logIn);
    }

    $sql ="select * from diet_topic_myths order by dateShown limit 1";
    $result = mysqli_query($db, $sql);
    if ($row = mysqli_fetch_array($result))
    {
        $IDtopic = $row['IDtopic'];
        $IDmyth  = $row['IDmyth'];
        $extext  = $row['explanation'];
        $exmyth  = $row['myth'];

        $sql =  "update diet_topic_myths set dateShown = '".date("Y-m-d")."' ".
                "where IDtopic = $IDtopic and IDmyth = $IDmyth";
        mysqli_query($db, $sql);

        $url = "xDietTopics.php?id=$IDtopic&mt=$IDmyth";
        $myth = substr($extext, 0, 200)."... <a href='$url'>Més</a>";
        CoverCard($url, $cover_3a, $exmyth, $myth);
    }

    CoverCard("https://diaridigital.net/diet/apps/menu.php",$cover_4a,$cover_4b,$mobil);

    echo "</div>";
    include './includes/googleFooter.inc.php';
?>