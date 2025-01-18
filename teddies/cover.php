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

    $startHere  = "Llistat de la col·lecció dels teddies";
    $c = countOrfan(); 
    if ( $c > 0) {

        $startHere .= "<br><br>Encara tens $c imatges per assignar!";
    }
    $stories    = "Seqüències d'imatges que expliquen històries amb els tesddies";
    $stuff      = "Coses curioses amb pandes";
    $logIn      = "";
    $logged     = "";
    $user = "";

    echo "<div class='cardContainer'>";

    CoverCard("main.php","Inici","Comença aquí",$startHere);
    CoverCard("stories.php","Stories","Aventures",$stories);


    $sql ="select * from ted_stories order by dateShown limit 1";
    $result = mysqli_query($db4, $sql);
    if ($row = mysqli_fetch_array($result))
    {
        $IDtopic = $row['IDstory'];
        $title   = $row['title'];
        $extext  = $row['story'];

        $sql =  "update ted_stories set dateShown = '".date("Y-m-d")."' ".
                "where IDstory = $IDtopic";
        mysqli_query($db4, $sql);

        $url = "story.php?ids=$IDtopic";
        $extext = str_replace("\n\n", "<br><br>", $extext);
        $myth = substr($extext, 0, 200)."... <a href='$url'>Més</a>";
        CoverCard("", "Curiòs", $title, $myth);
    }

    CoverCard("stuff.php","Coses","Coses de pandes",$stuff);

    echo "</div>";

    include './includes/googleFooter.inc.php';
?>