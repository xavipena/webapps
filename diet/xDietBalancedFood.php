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
    <script>
        function ListSelection() 
        {
            d = document.getElementById("catSelect").value;
            location.href="xDietBalancedFood.php?cat=" + d;
        }
    </script>
</head>
<body>
    <?php 
    //--- Params ----------------------- 
    
    $cat = empty($clean['cat']) ? "" : $clean['cat'];
    
    //--- Settings ------------------------

    $_SESSION['pageID'] = PAGE_DIET;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";
    
    function CatSelector($db, $current)
    {
        $catSelector  = "<select onchange='ListSelection()' id='catSelect' name='catSelect'>";
        $catSelector .= "<option value='0'>Selecciona un</option>";
        $sql ="select * from diet_product_categories order by name";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result)) 
        {
            $selected = $row['IDcat'] == $current ? "selected" : ""; 
            $catSelector .= "<option $selected value='".$row['IDcat']."'>".$row['name']."</option>";
        }
        $catSelector .= "</select>";
        return $catSelector;
    }

    //--- new content -------------------- 

    //echo "<div class='dietPageContainer'>";
    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

    echo "Grup d'aliments ".CatSelector($db, $cat);
    if ($cat != "")
    {
        $sql = "select * from diet_product_categories where IDcat = $cat";
        $result = mysqli_query($db, $sql);
        if ($row = mysqli_fetch_array($result)) 
        {
            echo "<h2>".$row['name']."</h2>";

            $where = "";
            if (strlen($row['IDsubgroup']) == 2) $where = "where IDgroup = ".$row['IDsubgroup'];
            if (strlen($row['IDsubgroup']) == 4) $where = "where IDsubgroup = ".$row['IDsubgroup'];

            $sql = "select * from diet_food_groups $where";
            $res = mysqli_query($db, $sql);
            if ($grp = mysqli_fetch_array($res)) 
            {
                echo "Grup principal ".$grp['name']."<br>";
                echo $grp['description']."<br>";
            }
            echo $row['recommended'].$row['units']."<br>".$row['details']."<br>";
        }
    }

    include './includes/googleFooter.inc.php';
?>