<?php
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $page = "teddies";
    include "./includes/dbConnect.inc.php";    // $db4
    include "./includes/dbConnect.inc.php";    // $db
    include './includes/googleHeader.inc.php';
    include './includes/googleSecurity.inc.php';
    include "./includes/settingsStart.inc.php";
    include "./includes/sideMenuHover_1.inc.php";
?>
</head>
<body>
<?php 

    //--- Settings ------------------------

    $_SESSION['pageID'] = PAGE_TEDDIES;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 
    

    //--- new content -------------------- 
     
    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

    echo "<div class='cardContainer'>";

    $sql ="select * from ted_stories where IDstory = ".$clean['ids'];
    $result = mysqli_query($db4, $sql);
    if ($row = mysqli_fetch_array($result))
    {
        $IDtopic = $row['IDstory'];
        $title   = $row['title'];
        $extext  = $row['story'];

        echo "<p>".$title."</p>";
        echo "<p>&nbsp;</p>";
        $extext = str_replace("\n\n", "<br><br>", $extext);
        echo "<p>".$extext."</p>";
    }

    echo "</div>";

    include './includes/googleFooter.inc.php';
?>