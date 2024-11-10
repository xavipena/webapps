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
    if (!empty($clean['mt'])) {

        include "./includes/background.inc.php";
    }
    else {
?>
    <style>
        main {
            background-repeat: no-repeat;
            background: linear-gradient(to left, transparent , #1f1f1f 35%), url("./images/pexels-markusspiske-1089438.jpg");
            background-size: cover;
        }
    </style>
<?php
    } 
?>
</head>
<body>
<?php 
    //--- Params ------------------------ 

    $myth = empty($clean['mt']) ? "" : $clean['mt'];

    //--- Settings ---------------------- 

    $_SESSION['pageID'] = PAGE_DIET_TOPIC;    
    $sourceTable ="";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    
    //--- functions ---------------------- 

    include "./includes/cards.inc.php";

    function ShowRecommend($text) {

        echo "<div class='container'>";
        $splitted = explode("<br><br>", $text);
        foreach ($splitted as $item) {
            
            echo "<div class='textCard box'>";
            echo "   <span class='coverCardText bigger'>$item</span>"; 
            echo "</div>";
        }
        echo "</div>";
    }

    function ShowMyth($db, $tp, $my) {

        $rowEnd = $GLOBALS['rowEnd'];
        echo "<h2>Mites</h2>";

        echo "<div class='container'>";
        $sql = "select * from diet_topic_myths where IDtopic = $tp and IDmyth = $my";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result))
        {
            ExplainCard($row['myth'], $row['explanation']);
        }
        echo "</div>";
    }

    function ShowFacts($db, $tp) {

        $rowEnd = $GLOBALS['rowEnd'];
        echo "<h2>Fets</h2>";

        echo "<div class='container'>";
        $c =0;
        $sql = "select * from diet where IDtopic = $tp and facts <> '' ";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result))
        {
            $c += 1;
            PlainTextCard($c, $row['facts'], $row['reference']);
        }
        echo "</div>";
    }

    function ShowProCons($db, $tp, $good) {

        $proCon = $good ? "Good <> ''" : "Bad <> ''";
        
        echo "<table cellpadding='5' width='80%'>";
        $c = 0;
        $sql = "select * from diet where IDtopic = $tp and facts = '' and $proCon";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result))
        {
            if ($good) {
                
                $text_1 = "Bo";
                $item_1 = $row['Good'];
                $item_2 =$row['TextGood'];
            }
            else {
                
                $text_1 = "Dolent";
                $item_1 = $row['Bad'];
                $item_2 =$row['textBad'];
            }
            if ($c == 0) echo "<tr><td></td><td><h2>$text_1</h2></td></tr>";
            echo "<tr>";
            echo "<td><div class='round'>$item_1</div></td><td>$item_2</td>";
            echo "</tr>";
            $c += 1;
        }
        echo "</table>";
    }

    //--- new content -------------------- 

    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 
    $menuType = MENU_TOPICS;
    include "./includes/dietMenu.inc.php";

    echo "<section>";
    $topic = "No trobat";
    $sql ="select * from diet_topics where IDtopic = ".$clean['id'];
    $result = mysqli_query($db, $sql);
    if ($row = mysqli_fetch_array($result)) {

        $topic = $row['name'];
        $texts = $row['remark'];
        $short = $row['shortDesc'];
    }

    echo "<h1>$topic</h1>";

    if (!empty($short)) {

        echo "<h2>Definició</h2>";
        echo $short;
    }
     
    if (!empty($texts)) {

        echo "<h2>Recomanacions</h2>";
        ShowRecommend($texts);
    }

    echo "</section>"; 
    
    if (!empty($myth)) {
        
        echo "<section>";
        ShowMyth($db, $clean['id'], $clean['mt']);
        echo "</section>";

        $buttonText = " Veure més sobre $topic ";
        echo "<input type='button' value='$buttonText' onclick='location.href=\"xDietTopics.php?id=".$clean['id']."\"'>";
    }
    else {

        echo "<section>";
        ShowProCons($db, $clean['id'], true);
        echo "</section><section>";
        ShowProCons($db, $clean['id'], false);
        echo "</section><section>";
        ShowFacts($db, $clean['id']);
        echo "</section>";
    }

    include './includes/googleFooter.inc.php';
?>