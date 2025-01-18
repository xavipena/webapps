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
//--- Settings -----------------------

$_SESSION['pageID'] = PAGE_TEDDIES;
$sourceTable = "ted_teddies";
$database = $db4;
include "./includes/sideMenuHover_2.inc.php";
include "./includes/sideMenuHover_3.inc.php";

//--- new content -------------------- 

include "./includes/menu.inc.php"; 
include "./includes/settingsEnd.inc.php";

$c = 0;
echo "<div class='container'>";

$path = "./images/";
if (file_exists($path)) {

    $files = array_slice(scandir($path), 2);

    // display on page
    $c = 0;
    foreach ($files as $img) 
    {
        $isIMG = strPos($img, "IMG_") !== false;
        $isTed = strPos($img, "ted_") !== false;
        if ( $isIMG || $isTed ) {

            if ($isTed) {
                
                $id = substr($img, 0, strpos($img, "."));   // IMG_3333.jpg -> IMG_3333
                $id = substr($id, 4);                       // IMG_3333     -> 3333
                $sql = "select name from ted_teddies where IDted = $id";
                $result = mysqli_query($database, $sql);
                $name = mysqli_fetch_array($result)[0];
                echo "<img class='photo' title='$name' src='".$path.$img."' width='200px'>".PHP_EOL;
            }
            else {
                
                echo "<a href='setImages_1.php?img=$img'><img class='photo dimmed' title='Qui és?' src='".$path.$img."' width='200px'></a>".PHP_EOL;
            }
            
            $c += 1;
        }
    }  
}

echo "</div>";
echo $c." imatges";

// --- end content -------------------

include './includes/googleFooter.inc.php';
?>