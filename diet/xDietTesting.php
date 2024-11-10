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
</head>
<body>
<?php 
    include "./includes/loader.inc.php";

    //--- Settings ------------------------

    $_SESSION['pageID'] = PAGE_DIET;
    $sourceTable = "";
    $prefix = "test";

    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    // local files

    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";
    
    //--- new content -------------------- 

    //echo "<div class='dietPageContainer'>";
    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php";

    $sql ="select count(*) from diet_users";
    $result = mysqli_query($db, $sql);
    $cnt = mysqli_fetch_array($result)[0];

    echo "<div class='round'>Pròximament</div>";
    echo "<div class='container'>";
        AddTestCard("xDietProductsFilter.php",$prefix,"Productes","Consulta de productes per composició", 0);
        AddTestCard("xDietBalanced.php",$prefix,"Dieta","Comprovar si porto una dieta equilibrada per grups d'aliments", 0);
        echo "</div>";
        
    echo "<div class='round'>APIs</div>";
    echo "<div class='container'>";
        AddTestCard("xDietAPI_1.php",$prefix,"API","API de menjars - TheMealDB", 0, true);
        AddTestCard("xDietAPI_2.php?api=".API_FDC,$prefix,"API","API de menjars - FoodData Central", 0, false);
        AddTestCard("xDietAPI_2.php?api=".API_FS,$prefix,"API","API nutricional - Fat Secret", 0, false);
    echo "</div>";

    echo "<div class='round'>Manteniments</div>";
    echo "<div class='container'>";
        AddTestCard("xDietMaintenance_1.php",$prefix,"Productes","Actualització de la categoria de alimentaria", 0);
    echo "</div>";
    
    echo "<div class='round'>Compra</div>";        
    echo "<div class='container'>";
        AddCard("xDietShopping.php",$prefix,"Actualitzar","Actualitza la compra i prepara la llista del que cal comprar");
        AddCard("xDietShoppingLst.php",$prefix,"Llista de la compra","Preparar la llista de la compra");
    echo "</div>";

    echo "<div class='round'>Més raro</div>";
    echo "<div class='container'>";
        AddTestCard("xDietGenCat.php",$prefix,"GenCat","Recomanacions", 0);
        AddTestCard("xDietBalancedFood.php",$prefix,"Grups d'aliments","Recomanacions 2", 0);
        AddTestCard("xDietDiagrams.php",$prefix,"Diagrames","Fluxes del metabolisme", 0);
    echo "</div>";

    echo "<div class='round'>Documentació</div>";
    echo "<div class='container'>";
        AddTestCard("xDietDocumentation.php",$prefix,"Documentació","Temes de lectura, paginats", 0);
        AddTestCard("xDietQuotes.php?type=P",$prefix,"Preguntes","Preguntes i respostes sobre nutrició", 0);
    echo "</div>";

    include './includes/googleFooter.inc.php';
?>