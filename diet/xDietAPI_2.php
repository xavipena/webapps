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
?>
</head>
<body>
<?php 
    include "./includes/loader.inc.php";

    //--- Params ----------------------- 
    
    $api = empty($clean['api']) ? API_FS : $clean['api'];
    
    //--- Settings ------------------------

    $_SESSION['pageID'] = PAGE_DIET;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";
    
    //--- new content -------------------- 

    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

    // set loader element visible dependin on selected type
    switch ($loaderType) {

        case BLACK_HOLE:

            $element = "loading";
            break;

        case DRAGONFLY:

            $element = "loading-wave";
            break;
    }

    $action = "";
    switch ($api) {

        case API_FDC:
            
            $action = "xDietAPI_2_get.php";
            $provider = "Food Data Central";
            break;

        case API_FS:

            $action = "xDietAPI_3.php";
            $provider = "FatSecret";
            break;
        }    

    echo "<div class='cardSelector'>";
    echo "<fieldset>";
    echo "<legend>Cerca de producte</legend>";
    echo "API de $provider";
    echo "<form id='fdc' method='post' action='javascript:CallURL(\"".$element."\",\"".$action."\")'>";
    echo "<input type='text' value='' name='srch' size='40' placeholder='Patata' required>";
    echo "<input type='submit' value=' Cerca '>";
    echo "</form>";
    echo "</fieldset>";
    echo "</div>";

    include './includes/googleFooter.inc.php';
?>