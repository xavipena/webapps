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
    //--- settings --------------------- 

    $_SESSION['pageID'] = PAGE_DIET_MENU;    
    $page ="diet";
    $sourceTable = "diet_links";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    
    //--- functions -------------------- 

    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";

    //--- new content -------------------- 
    
    //echo "<div class='dietPageContainer'>";
    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

    $section = 0;
    $sql = "select diet_links.*, diet_link_types.name as lname from diet_links ".
        "join diet_link_types ".
        "  on diet_links.linkType = diet_link_types.IDtype ".
        "order by diet_links.linkType";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        if ($section != $row['linkType'])
        {
            if ($section > 0)
            {
                echo "</div>";
            }
            echo "<br><div class='round'>".$row['lname']."</div>";        
            echo "<div class='container'>";
            $section = $row['linkType'];
        }
        AddCard($row['url'], "link", $row['name'], $row['description']);
    }
    echo "</div>";

    echo "<br><div class='round'>Referencias</div>";        
    echo "<div class='container'>";
    AddCard("xDietReferences.php", "link", "Referències", "Lista de enllaços de informació sonbre la que ha estat creat aquesta web");
    echo "</div>";

include './includes/googleFooter.inc.php';
?>