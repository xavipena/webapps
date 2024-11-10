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
?>
    <style>
        main {
            background-repeat: no-repeat;
            background: linear-gradient(to left, transparent , #1f1f1f 45%), url("./images/pexels-scottwebb-28054.jpg");
            background-size: cover;
        }
    </style>
<script>
    function goNext() {

        var prValue = "";
        var profile = document.getElementsByName("profile");
        if (profile == undefined) {

            alert("Selecciona un perfil");
            return false;
        };
        for(i = 0; i < profile.length; i++) {
         
            if (profile[i].checked) {

                prValue = profile[i].value;
                break;
            }
        }
        if (prValue == "") {

            alert("Selecciona un perfil");
            return false;
        };
        document.location.href = "xDietCalcMethods.php?profile="+prValue;
    }
</script>
</head>
<body>
<?php 
    $_SESSION['pageID'] = PAGE_DIET_MENU;    
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    
    //--- functions ---------------------- 

    include "./includes/cards.inc.php";

    //--- new content -------------------- 
    
    include "./includes/menu.inc.php"; 
    $menuType = MENU_CALCULATE;
    $selectedMenuOption = 4;
    include "./includes/dietMenu.inc.php";

    if (!isset($_SESSION['diet_user'])) 
    {
        echo "<a href='xDietUser_1.php'>".locale("strStartSession")."</a> ".locale("strText_6")."<br></br>";
    }
    echo "<div class='container'>";
?>
<form action="xDietCalcMethods.php" method="post" onsubmit="return false;">
    <p>Selecciona el perfil que més et defineix:</p>
    <fieldset>
        <legend><?php echo locale("strProfile")?>:</legend>
        <input type="radio" name="profile" id="proA" value="A" /><?php echo locale("strProfile_1")?><br>
        <input type="radio" name="profile" id="proB" value="B" /><?php echo locale("strProfile_2")?><br>
        <input type="radio" name="profile" id="proC" value="C" /><?php echo locale("strProfile_3")?><br>
    </fieldset>
    <input type="submit" value="<?php echo locale("strNext")?>" onclick="javascript:goNext()"/>
</form>
<br>
<?php
/*
    Ecuación de Harris Benedict.
    Hombres: TMB = (10 x peso en kg) + (6.25 x altura en cm) – (5 x edad en años) + 5
    Mujeres: TMB = (10 x peso en kg) + (6.25 x altura en cm) – (5 x edad en años) – 161

    Cunningham et al.
    Mayor utilidad y capacidad en determinados grupos de población como los deportistas

    Gasto energético diario = FA x (370 + 21.6 x Masa Libre de Grasa (kg))

    Factor de actividad (FA)
    Sedentario (poco o ningún ejercicio): FA = 1.2
    Actividad ligera (ejercicio ligero o deporte 1-3 días a la semana): FA = 1.375
    Actividad moderada (ejercicio moderado o deporte 3-5 días a la semana): FA = 1.55
    Actividad intensa (ejercicio intenso o deporte 6-7 días a la semana): FA = 1.725
    Actividad muy intensa (ejercicio muy intenso o trabajo físico y ejercicio diario): FA = 1.9

    Tinsley et al.
    La calculadora de Tinsley supone una alternativa más ajustada a personas, hombres y mujeres, que entrenan habitualmente y tienen un buen desarrollo muscular.

    Gasto energético diario = FA x (284 + 25.9 x Masa Libre de Grasa (kg))

    Sedentario (poco o ningún ejercicio): FA = 1.2
    Actividad ligera (ejercicio ligero o deporte 1-3 días a la semana): FA = 1.375
    Actividad moderada (ejercicio moderado o deporte 3-5 días a la semana): FA = 1.55
    Actividad intensa (ejercicio intenso o deporte 6-7 días a la semana): FA = 1.725
    Actividad muy intensa (ejercicio muy intenso o trabajo físico y ejercicio diario): FA = 1.9
 */

    // cards in vertical list
    echo "<div class='vcontainer'>";
        $glossary_TMB = 29;
        $sql = "select * from diet_glossary where IDterm = $glossary_TMB";
        $result = mysqli_query($db, $sql);
        if ($row = mysqli_fetch_array($result))
        {
            //function AddCard($url, $prefix, $name, $description, $hasArrow = false, $loader = false, $isMenu = false) {
            AddCard("xDietGlossary_1.php?term=".$row['IDterm'],"glossary",$row['title'],$row['inShort']);
        }
    echo "</div>"; // vcontainer
    echo "</div>"; // container

    echo "<script>";
    if (isset($_SESSION['diet_user'])) {

        if ($_SESSION['diet_user'] != "") {

            $sql = "select formula from diet_user_periods where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period'];
            $result = mysqli_query($db, $sql);
            if ($row = mysqli_fetch_array($result)) {

                if ($row['formula'] != "") {
                
                    echo "document.getElementById('pro".$row['formula']."').checked = true;";
                }
            }
        }
    }
    echo "</script>";

    include './includes/googleFooter.inc.php';
?>