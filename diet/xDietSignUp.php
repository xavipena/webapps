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

    //--- Settings ------------------------

    $_SESSION['pageID'] = PAGE_DIET;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 

    function FormUser()
    {
        $output = "<form id='dietUsr' method='post' action='xDietSignUp_1.php'>";
        $output .= "<fieldset>";
        $output .= "<legend>Nou usuari</legend>";
        $output .= "<label for='dtName'>Nom: </label>";
        $output .= "<input type='text' id='dtName' name='dtName' value='' size='20'/><br>";
        $output .= "<br><input type='submit' value=' Grava '>";
        $output .= "</fieldset>";
        $output .= "</form>";
        return $output;
    }

    function ShowError($e)
    {
        switch ($e) {
            case 1:
                $etext = "El nom ja existeix";
                break;
        }
        $output = "<div class='showError'><p class='center-vertical'>$etext</p></div>";
        return $output;
    }

    //--- new content -------------------- 

    //echo "<div class='dietPageContainer'>";
    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 
    //include "./includes/___Menu.inc.php";

    echo FormUser();
    if (!empty($clean['err'])) 
    {
        echo ShowError($clean['err']);
    }
    /*
    $sql = "select * from ...";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    {
    }
    */
    include './includes/googleFooter.inc.php';
?>