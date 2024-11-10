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
        .green {
            color: lightgreen;
        }
        .round {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<?php 
    //--- Params ----------------------- 
    
    $data = empty($clean['data']) ? 0 : $clean['data'];
    
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

    $aux = -1;
    $sql =  "select dr.*, dt.name tname from diet_references dr ".
            "left join diet_topics dt ".
            "  on dt.IDtopic = dr.IDtopic ".
            "order by dt.Idtopic, dt.name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    {
        if ($aux != $row['IDtopic'])
        {
            echo "<h2>".$row['tname']."</h2>";
            $aux = $row['IDtopic'];
        }
        echo "<div class='round'><span class='green'>".$row['name']."</span><br>".$row['description']."<br><a href='".$row['url']."'>".$row['url']."</a></div>";
    }
    $page = ""; // remove references link to avoid loop
    include './includes/googleFooter.inc.php';
?>