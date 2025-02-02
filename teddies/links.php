<?php
    session_start();
    if (empty($_SESSION['runner_id'])) header("Location: login.php");

    $page = "teddies";
    include "./includes/dbConnect.inc.php";
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
    
     include "./includes/functions.inc.php";

    //--- new content -------------------- 
     
    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

    echo "<div class='cardContainer'>";

    $sql ="select * from ted_links";
    $result = mysqli_query($db4, $sql);
    if ($row = mysqli_fetch_array($result))
    {
        LinkCard($row['IDlink'], $row['url'], $row['name'], $row['description']);
    }

    echo "</div>";

    include './includes/googleFooter.inc.php';
?>