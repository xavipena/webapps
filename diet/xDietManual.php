<?php
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $page = "diet";
    include './includes/dbConnect.inc.php';
    include './includes/googleHeader.inc.php';
    include './includes/googleSecurity.inc.php';
    include "./includes/settingsStart.inc.php";
    include "./includes/sideMenuHover_1.inc.php";
    include "./includes/background.inc.php";
?>
<style>
    .paragraph {
        width: 550px;
        text-align: justify;
    }
</style>
</head>
<body>
<?php 
    //--- params --------------------------

    $step = empty($clean['step']) ? 0 : $clean['step'];

    //--- Settings ------------------------

    $_SESSION['pageID'] = PAGE_DIET;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    // Load texts from manual
    $texts = "";
    $sql ="select * from diet_manual where IDstep = $step and lang = '$lang'";
    $result = mysqli_query($db, $sql);
    if ($row = mysqli_fetch_array($result)) {
    
        $texts = $row['manual'];
    }

    //--- functions -------------------- 

    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";

    function ShowSection($db, $num, $range, $arrows, $text, $allowDisabled = false) {

        $i = 0;
        echo "<div class='container'>";
        BigNUmberCard($num, $text);
        echo "</div>";

        if ($num == 0) {

            $rTitle = locale("strDisclaimer");
        }
        else {

            $rTitle = locale("strUseThis");
        }
        echo "<br><div class='roundTitle'>$rTitle</div><br>";

        if ($range == "") {
            
            if ($GLOBALS['lang'] == 'ca') {

            echo "<p class='paragraph'>L'objectiu d'aquesta app és el de poder fer una anàlisi nutricional de la ingesta. Per aconseguir-ho cal tenir la informació de tots els àpats ".
                "fets i així procedir a analitzar els nutrients i veure quina diferència hi ha respecte al que es considera una dieta saludable. ".
                "No es pretén que sigui, a priori, un procediment per a perdre pes, sino una manera de poder regular la dieta i, en conseqüència, aconseguir el ".
                "pes correcte.</p>".
                "<p class='paragraph'>Un dels principis bàsics per a perdre pes, en una persona sana, és el dèficit calòric, és a dir, cremar més calories de les que es generen amb ".
                "els aliments. Per això l'app permet, a partir de les dades biomètriques, estimar la ingesta calòrica recomanada i establir uns punts de referència. ".
                "Hi ha tota una documentació associada que ha servit de base per a muntar l'app i per a fer les anàlisis finals.</p>";
            }
            else {

                echo "<p class='paragraph'>El objetivo de esta app es el de poder hacer un análisis nutricional de la ingesta. Para conseguirlo es necesario tener la información de todas las comidas ".
                "hechos y así proceder a analizar los nutrientes y ver qué diferencia hay respecto a lo que se considera una dieta saludable. ".
                "No se pretende que sea, a priori, un procedimiento para perder peso, sino una forma de poder regular la dieta y, en consecuencia, conseguir el ".
                "peso correcto.</p>".
                "<p class='paragraph'>Uno de los principios básicos para perder peso, en una persona sana, es el déficit calórico, es decir, quemar más calorías de las que se generan con ".
                "los alimentos. Por eso la app permite, a partir de los datos biométricos, estimar la ingesta calórica recomendada y establecer unos puntos de referencia. ".
                "Existe toda una documentación asociada que ha servido de base para montar la app y para realizar los análisis finales.</p>";
            }
        }
        else {
            
            $disabled = false;
            if ($allowDisabled) $disabled = !isset($_SESSION['diet_user']);

            echo "<div class='container'>";
            $sql =  "select * from diet_menu ".
                    "where status = 'A' and lang = '".$GLOBALS['lang']."' and IDmenu in ($range) ".
                    "order by sequence";
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($result))
            {
                $i += 1;
                AddCard($row['page'], "diet", $row['name'], $row['description'], $i <= $arrows, false, false, $disabled);
            }
            echo "</div>";
        }
    }

    function ShowSelectors($db, $num, $text, $query, $allowDisabled = false) {

        $disabled = false;
        if ($allowDisabled) $disabled = !isset($_SESSION['diet_user']);

        $i = 0;
        echo "<div class='container'>";
        BigNUmberCard($num, $text);
        echo "</div>";

        echo "<br><div class='roundTitle'>".locale("strUseThis")."</div><br>";

        echo "<div class='container'>";
        $sql =  "select * from diet_menu ".
                "where status = 'A' and lang = '".$GLOBALS['lang']."' and $query ".
                "order by sequence";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result))
        {
            $i += 1;
            AddCard($row['page'], "diet", $row['name'], $row['description'], false, false, false, $disabled);
        }
        echo "</div>";
    }

    //--- new content -------------------- 

    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

    $numSteps = 5;
    switch ($step) {
        case 0:
            ShowSection($db, $step, "", 0, $texts, false);
            break;
        case 1:
            ShowSection($db, $step, "2, 48", 0, $texts, false);
            break;
        case 2:
            ShowSection($db, $step, "4,5,6", 2, $texts, true);
            break;
        case 3:
            ShowSection($db, $step, "1,3", 1, $texts, true);
            break;
        case 4:
            ShowSelectors($db, $step, $texts, "isSelector = 1", false);
            break;
        case 5:
            ShowSelectors($db, $step, $texts, "isAnalytics = 1", true);
            break;
    }
    if ($step < $numSteps) {

        $btnText = $step == 0 ? locale("strStart") : locale("strNextStep");
        $step += 1;
        echo "<input type='button' value=' $btnText ' onclick='location.href=\"xDietManual.php?step=$step\"'>";
    }
    else {

        echo "<input type='button' value=' ".locale("strHome")."  ' onclick='location.href=\"xDietCover.php\"'>";
    }

    include './includes/googleFooter.inc.php';
?>