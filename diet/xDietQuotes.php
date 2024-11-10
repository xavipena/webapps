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
    //--- params --------------------------

    //--- Settings ------------------------

    $_SESSION['pageID'] = PAGE_DIET;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 

    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";

    //--- new content -------------------- 

    //echo "<div class='dietPageContainer'>";
    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

    echo "<div class='cardContainer'>";
    $sql =  "select * from diet_quotes where type = 'Q'";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        TextCard($row['author'], $row['quote'], $row['reference']);
    }
    echo "</div>";

    include './includes/googleFooter.inc.php';
?>