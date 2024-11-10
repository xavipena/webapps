<?php 
include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";
include "./includes/topHeader.inc.php";
include "./includes/functions.inc.php";
include "./includes/paginatorStart.inc.php";
?>
</head>
<body>
    <main>
<?php 
// -----------------------------
// popup for tag selection 
// -----------------------------
include "./includes/popup.inc.php";
// -----------------------------
// loader to wait while next page loads 
// -----------------------------
include "./includes/loader.inc.php";
?>
        <div class="wrapper">
<?php 
// -----------------------------
// Paginator
// -----------------------------
$page = $page == 0 ? 1 : $page;
$items_per_page = PER_PAGE;
$offset = ($page - 1) * $items_per_page;

$sql = "select count(*) from crono_sel_work";
$result = mysqli_query($db, $sql);
$total_rows = mysqli_fetch_array($result)[0];
$total_pages = ceil($total_rows / $items_per_page);

$sql = "select IDphoto from crono_sel_work limit $offset, $items_per_page";    
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result)) 
{
    $image      = $row['IDphoto'];
    $imgArray   = GetPicture($image);

    echo "<div id='$image' class='item'>".PHP_EOL;
    if ($_SESSION['deletion'] == YES)
    {
        echo "        <a href='javascript:CallPage(\"$element\",\"delSelected.php?photo=$image\")'>".PHP_EOL;
    } else {
        echo "        <a id='a$image' href='javascript:DelSelected(\"$image\")'>".PHP_EOL;
    }
    echo "           <img src='".$imgArray[0]."' width='".$imgArray[2]."' height='".$imgArray[3]."'>".PHP_EOL;
    echo "        </a>".PHP_EOL;
    echo "</div>".PHP_EOL;
}

// Params for right menu stats
$step = SELECTION;
$cnt = $total_rows;
if ($cnt == 0)
{
    echo "No hi ha cap imatge seleccionada |&nbsp;";
    echo "<a href='javascript:CallPage(\"$element\",\"selectPicturesToTag.php\")'>Seleccionar fotos</a>";
    $cnt = HIGH_VALUE;
    $step = NONE;
}

// -----------------------------
// wrapper close
// -----------------------------
echo "</div>"; 

// call web service to update screen

$_SESSION['url'] = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

include "./includes/menuOptions.inc.php";
include "./includes/paginatorEnd.inc.php";
include "./includes/topFooter.inc.php";
?>
