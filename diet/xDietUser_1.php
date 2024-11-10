<?php
    session_start();
    $runner_id ="";
    $page = "diet";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];

    if (empty($_SESSION['diet_user'])) {

        header("Location: ./login/login.php");
    }

    include './includes/dbConnect.inc.php';
    include './includes/googleHeader.inc.php';
    include './includes/googleSecurity.inc.php';
    include "./includes/settingsStart.inc.php";
    include "./includes/sideMenuHover_1.inc.php";
?>
    <style>
        main {
            background-repeat: no-repeat;
            background: linear-gradient(to left, transparent , #1f1f1f 35%), url("./images/pexels-cottonbro-8090132.jpg");
            background-size: cover;
        }
        .red {
            color: red; 
        }
    </style>
</head>
<body>
<?php
    // to inform to update user un case no tmb completed
    $control = 0;

    $_SESSION['pageID'] = PAGE_DIET_MENU;
    $sourceTable = "diet_users";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    
    //--- functions ---------------------- 

    include "./includes/cards.inc.php";

    function ListWeight($db) {

        $rowStart = $GLOBALS['rowStart'];
        $rowEnd = $GLOBALS['rowEnd'];
        $newColNum = $GLOBALS['newColNum'];

        $c = 0;
        $output  = "<table class='dietcard'>";
        $output .= "<thead><tr><th>".locale("strDate")."</th><th>".locale("strWeight")."</th></tr></thead>";
        $sql ="select * from diet_user_data where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period']." order by date desc limit 10";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result))
        {
            $output .= $rowStart.$row['date'].$newColNum.$row['weight'].$rowEnd;
            $c += 1;
        }    
        if ($c == 0) $output .= $rowStart."Sense dades".$newColNum.$rowEnd;
        $output .= "</table>";
        return $output;
    }

    function GetUserData($db) {

        $newColNum = $GLOBALS['newColNum'];
        $rowEnd = $GLOBALS['rowEnd'];

        $w = "";
        $h = "";

        $output = "<table class='dietcard'>";

        $sql ="select * from diet_user_periods where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period'];
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result))
        {
            $w = $row['weight'];
            $h = $row['height'];

            /*
            $output .= "<tr><td>Pes</td><td class='number'>".$row['weight']." kg</td></tr>";
            $output .= "<tr><td>Altura</td><td class='number'>".$row['height']." cm</td></tr>";
            $output .= "<tr><td>Edat</td><td class='number'>".$row['age']." anys</td></tr>";
            */
            $class = $row['basal'] == 0 ? "red" : "";
            $output .= "<tr class='$class'><td>".locale("strCalories")." <a href='xDietGlossary_1.php?term=2'>".locale("strBasal")."</a>".$newColNum.$row['basal']." Kcal".$rowEnd;
            
            $class = $row['thermogenesis'] == 0 ? "red" : "";
            $output .= "<tr class='$class'><td>".locale("strCalories")." <a href='xDietGlossary_1.php?term=1'>".locale("strTermogen")."</a>".$newColNum.$row['thermogenesis']." Kcal".$rowEnd;
            
            $class = $row['exercise'] == 0 ? "red" : "";
            $output .= "<tr class='$class'><td>".locale("strCalories")." <a href='xDietGlossary_1.php?term=3'>".locale("strbyActivity")."</a>".$newColNum.$row['exercise']." Kcal".$rowEnd;
            $output .= "<tr><td>".locale("strCalories")." ".locale("strRecommended")."</td><td class='number'>".$row['recommended']." Kcal".$rowEnd;
            
            $class = $row['lossDiet'] == 0 ? "red" : "";
            $output .= "<tr class='$class'><td>".locale("strLimit").$newColNum.$row['limited']." Kcal".$rowEnd;
            
            $timestamp = strtotime($row['sysdate']);
            $formattedDate = date('d-m-Y', $timestamp);
            $output .= "<tr><td>".locale("strToDate")."</td><td>".$formattedDate.$rowEnd;
            $tar = "";
            switch ($row['target'])
            {
                case "A":
                    $tar = locale("strText_1");
                    break;
                case "B":
                    $tar = locale("strText_2");
                    break;
                case "C":
                    $tar = locale("strText_3");
                    break;
            }
            $output .= "<tr><td>".locale("strTarget")."</td><td>".$tar.$rowEnd;
            if ($row['target'] != "B") 
            {
                if ($row['lossKg'] > 0) 
                {
                    $dietType = "??";
                    $output .= "<tr><td>Pes a perdre".$newColNum.$row['lossKg']." kg".$rowEnd;
                    $output .= "<tr><td>En setmanes".$newColNum.$row['lossWeeks'].$rowEnd;
                    $class = $row['lossDiet'] == 0 ? "red" : "";
                    switch ($row['dietType'])
                    {
                        case 500:
                            $dietType = locale("strLight");
                            break;
                        case 600:
                            $dietType = locale("strMedium");
                            break;
                        case 700:
                            $dietType = locale("strStricted");
                            break;
                    }
                    $output .= "<tr class='$class'><td>".locale("strText_4")."</td><td>".$dietType.$rowEnd;
                }
            }
        }    
        $output .= "</table>";
        if ($h >0 && $w >0) {
        ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                var obj = document.getElementById('imc');
                obj.innerHTML = CalcBMI(<?php echo $h.", ".$w?>);
            });
            </script>
        <?php
        }
        return $output;
    }
    
    function GetStats($db) {

        $output = "<table class='dietcard'>";

        $sql = "select count(distinct date) from diet_user_meals where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period'];
        $res = mysqli_query($db, $sql);
        $cnt = mysqli_fetch_array($res)[0];

        $output .= "<tr><td>".locale("strNumMeals")."</td><td class='number'>$cnt</td></tr>";

        $sql ="select * from diet_periods where IDuser = ".$_SESSION['diet_user']. " and IDperiod = ".$_SESSION['diet_period'];
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($result);

        $sql =  "select count(distinct date) from diet_user_meals ".
                "where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period']." and date between '".$row['beginDate']."' and '".$row['endDate']."'";
        $res = mysqli_query($db, $sql);
        $cnt = mysqli_fetch_array($res)[0];

        $output .= "<tr><td>".locale("strNumMealsPer")."</td><td class='number'>$cnt</td></tr>";
        $output .= "<tr><td>IMC</td><td class='number' id='imc'>0</td></tr>";
        $output .= "</table>";

        return $output;
    }
    
    function FormPeriodSelector($db) {

        $output  = "<form id='dietPer' method='post' action='xDietUser_6.php'>";
        $output .= "<fieldset>";
        $output .= "<legend>".locale("strText_5")."</legend>";
        $output .= "<label for='dtPeriod'>".locale("strPeriod").": </label>";
        $output .= "<select name='dtPeriod'>";

        $sql ="select * from diet_periods where IDuser = ".$_SESSION['diet_user'];
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result))
        {
            $selected = $_SESSION['diet_period'] == $row['IDperiod'] ? "selected" : "";
            $output .= "<option $selected value='".$row['IDperiod']."'>".$row['description']."</option>";
        }

        $output .= "</select>";
        $output .= "<br><br><input type='submit' value=' ".locale("strChange")." '>";
        $output .= "&nbsp;<input type='button' value=' ".locale("strNewPeriod")." ' onclick='location.href=\"xDietUser_7.php?op=add\"'>";
        $output .= "&nbsp;<input type='button' value=' ".locale("strModify")." ' onclick='location.href=\"xDietUser_7.php?op=edit\"'>";
        $output .= "</fieldset>";
        $output .= "</form>";
        return $output;
    }

    function PeriodDetails($db) {

        $sql ="select * from diet_user_periods where IDuser = ".$_SESSION['diet_user']. " and IDperiod = ".$_SESSION['diet_period'];
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($result);

        $GLOBALS['control'] = $row['weight'];
        $output  = "<table class='dietcard'>";
        $output .= "<tr><td>".locale("strWeight")."</td><td class='number'>".$row['weight']." kg</td></tr>";
        $output .= "<tr><td>".locale("strHeight")."</td><td class='number'>".$row['height']." cm</td></tr>";
        $output .= "<tr><td>".locale("strAge")."</td><td class='number'>".$row['age']." ".locale("strYears")."</td></tr>";

        $sql ="select * from diet_periods where IDuser = ".$_SESSION['diet_user']. " and IDperiod = ".$_SESSION['diet_period'];
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($result);

        $timestamp = strtotime($row['beginDate']);
        $formattedDate = date('d-m-Y', $timestamp);

        $output .= "<tr><td>".locale("strFrom")."</td><td>$formattedDate</td></tr>";

        $timestamp = strtotime($row['endDate']);
        $formattedDate = date('d-m-Y', $timestamp);

        $output .= "<tr><td>".locale("strTo")."</td><td>$formattedDate</td></tr>";
        $output .= "</table>";
        return $output;
    }

    function DietDays($db) {

        $output = "Determinació del periode de control de la ingesta per a veure resultats.<br><br>";

        $sql ="select * from diet_periods where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period'];
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($result);

        $begin = strtotime($row['beginDate']);
        $end   = strtotime($row['endDate']);
        $datediff = $end - $begin;
        
        $output .= round($datediff / (60 * 60 * 24)) + 1;
        $output .= " ".locale("strDietDays");
        return $output;
    }

    function FormUserWeight() {
        
        $output = "<form id='dietWgt' method='post' action='xDietUser_5.php'>";
        $output .= "<fieldset>";
        $output .= "<legend>".locale("strText_5")."</legend>";
        $output .= "<label for='dtDate'>".locale("strDate").": </label>";
        $output .= "<input type='date' id='dtDate' name='dtDate' value='".date("Y-m-d")."'/><br>";
        $output .= "<label for='dtWeight'>".locale("strWeight").": </label>";
        $output .= "<input class='number-input' type='number' id='dtWeight' name='dtWeight' value='0' step='.01' required><br>";
        $output .= "<br><input type='submit' value=' ".locale("strSave")." '>";
        $output .= "</fieldset>";
        $output .= "</form>";
        return $output;
    }

    function AccountOptions() {

        $output  = "<form id='dietWgt' method='post' action='xDietUser_5.php'>";
        $output .= "<fieldset>";
        $output .= "<legend>".locale("strAccount")."</legend>";
        $output .= "<br>";
        $output .= "<input class='center-vertical' type='button' onclick='location.href=\"xDietAccountLan.php\"' value='".locale("strLanguage")."'>";
        $output .= "<input class='center-vertical' type='button' onclick='location.href=\"xDietAccountDel.php\"' value='".locale("strDelete")."'>";
        $output .= "<input class='center-vertical' type='button' onclick='location.href=\"xDietAccountPss.php\"' value='".locale("strChangePass")."'>";
        $output .= "</fieldset>";
        $output .= "</form>";
        $output .= "<br>";
        return $output;    
    }
    
    //--- new content -------------------- 
    
    $date = empty($clean['dt']) ? "" : $clean['dt'];
    include "./includes/menu.inc.php"; 
    $menuType = MENU_CALCULATE;
    $selectedMenuOption = 2;
    include "./includes/dietMenu.inc.php";
    
    echo "<h2>".locale("strDietUser")."</h2>";
    
    if (isset($_SESSION['diet_user'])) {
        
        if ($_SESSION['diet_user'] != "") {
            
            echo "<div class='container'>";
                echo FormPeriodSelector($db);
                echo PeriodDetails($db);
                echo "<div>&nbsp;&nbsp;</div>";
                echo DietDays($db);
                if ($control == 0) {

                    AddCard("xDietCalc_2.php", "diet", locale("strBiometrics"), locale("strCardHelp_1"), false);
                }
            echo "</div>";

            echo "<hr>";
            if (!empty($_SESSION['username'])) {
            
                echo "<div class='round'>Usuari ".$_SESSION['username']; 
            }
            else {

                echo "<div class='round'>".locale("strUserVoid"); 
            }
            echo "</div><br>";
            
            echo "<div class='container atop'>";
                echo "<div class='table details-inline'>".GetUserData($db)."</div>";
                echo "<div class='table details-inline'>".ListWeight($db);
                echo "  <div class='parent-div-150'><input class='center-vertical' type='button' onclick='location.href=\"xDietTrace.php\"' value='".locale("strPlotWeight")."'>";
                echo "  </div></div>";
                echo "<div class='table details-inline'>".GetStats($db)."</div>";
                echo "<div class='table details-inline'>".FormUserWeight()."</div>";
                echo "<div class='table details-inline'>".AccountOptions()."</div>";
            echo "</div>";
            
        }
    }

include './includes/googleFooter.inc.php';
?>