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
<style>
        main {
            background-repeat: no-repeat;
            background: linear-gradient(to left, transparent , #1f1f1f 35%), url("./images/pexels-tima-miroshnichenko-7567554.jpg");
            background-size: cover;
        }
    </style>
</head>
<body>
<?php 
    include "./includes/loader.inc.php";

    //--- Settings ------------------------

    $_SESSION['pageID'] = PAGE_DIET_AUDIT;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 

    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";

    //--- new content -------------------- 

    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

    echo "<div class='round'>Audit de la dieta</div>";
    echo "<div class='container'>";

    $sql = "select * from diet_menu where IDsection = ".PAGE_DIET_AUDIT." and lang = '$lang'";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {

        AddCard($row['page'],"diet",$row['name'],$row['description'], false, true);
    }
    echo "</div>";

    include './includes/googleFooter.inc.php';
?>