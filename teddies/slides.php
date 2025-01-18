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
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php 
//--- Settings -----------------------

$_SESSION['pageID'] = PAGE_TEDDIES;
$sourceTable = "ted_slides";
$database = $db4;
$slide = $clean['id'];
include "./includes/sideMenuHover_2.inc.php";
include "./includes/sideMenuHover_3.inc.php";

//--- functions --------------------- 

function getSlideCaption($slide, $c) {

    global $database;
    $sql = "select caption from ted_slide_captions where IDslide = $slide and sequence = $c";
    $result = mysqli_query($database, $sql);
    if ($row = mysqli_fetch_assoc($result)) {

        return $row['caption'];
    }
    else return "Slide $c";
}

//--- new content -------------------- 

include "./includes/menu.inc.php"; 
include "./includes/settingsEnd.inc.php";

$c = 0;
echo "<div class='container'>";

$dir = "./images";
$files = scandir($dir, SCANDIR_SORT_ASCENDING);

?>
<div id="slideContainer">
    <button id='prev' class="sliderBtn">&lt;
        <button id="next" class="sliderBtn">&gt;</button>
    </button>
<?php
    foreach ($files as $file) {
        
        $slideID = "slide_$slide";
        if (strpos($file, $slideID) !== false) {
        
            $c += 1;
            $class = $c == 1 ? "slide show" : "slide";
    
            echo "<div class='$class'>";
            echo "<img src='./images/" . $file . "' alt='slide".$c."' />";
            echo "<p>".getSlideCaption($slide, $c)."</p>";
            echo "</div>";
        }
    }   
?>
</div>
<script src='./js/script.js'></script>

<?php
echo "</div>";

// --- end content -------------------

include './includes/googleFooter.inc.php';
?>