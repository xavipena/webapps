<?php
    session_start();
    $runner_id = "";
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
        margin-left: 10px;
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
            
            $sql =  "select disclaimer from diet_manual where IDstep = 0 and lang = '".$GLOBALS['lang']."'";
            $result = mysqli_query($db, $sql);
            $row = mysqli_fetch_array($result);
            echo $row['disclaimer'];
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

        echo "<input type='button' value=' ".locale("strHomeStart")."  ' onclick='location.href=\"xDietCover.php\"'>";
    }

    include './includes/googleFooter.inc.php';
?>