<?php 
include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";
include "./includes/topHeader.inc.php";
include "./includes/functions.inc.php";
$textID = 10;
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
// read tags to update
$sql = "select * from crono_selection where IDline = ".TAGGING;
$result = mysqli_query($db, $sql);
$sel = mysqli_fetch_array($result);

// update crono_photos based on crono_sel_work, overwriting populated values on crono_selection

$c = 0;
$sql = "select * from crono_sel_work";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result))
{
    $sql = "update crono_photos set IDphoto = ".$row['IDphoto'];
    if ($sel['IDsubject'] > 0)  $sql .= ",IDsubject = ".$sel['IDsubject'];
    if ($sel['year'] > 0)       $sql .= ",year      = ".$sel['year'];
    if ($sel['month'] > 0)      $sql .= ",month     = ".$sel['month'];
    if ($sel['country'] != '')  $sql .= ",country   = '".$sel['country']."'";
    if ($sel['IDcity'] > 0)     $sql .= ",IDcity    = ".$sel['IDcity'];
    if ($sel['IDevent'] > 0)    $sql .= ",IDevent   = ".$sel['IDevent'];
    if ($sel['IDperson'] > 0)   $sql .= ",IDperson  = ".$sel['IDperson'];
    if ($sel['IDgroup'] > 0)    $sql .= ",IDgroup   = ".$sel['IDgroup'];
    if ($sel['IDdetail'] > 0)   $sql .= ",IDdetail  = ".$sel['IDdetail'];
    if ($sel['IDtype'] > 0)     $sql .= ",IDtype    = ".$sel['IDtype'];
    $sql .= " where IDphoto = ".$row['IDphoto'];
    //echo $sql."<br>";
    if (mysqli_query($db, $sql))
    {
        $c += 1;
    }
}

// clear selection?
$sql = "delete from crono_sel_work";

switch ($c) {

    case 0:
        echo "Cap foto actualitzada";
        break;
    case 1:
        echo "$c foto actualitzada";
        break;
    default:
        echo "$c fotos actualitzades";
        break;
}
echo "&nbsp;&nbsp;<button type='button' onclick='location.href=\"tagPictures.php\"'>Següent foto</button>";
echo "</div>"; // wrapper close

// call web service to update screen

// -----------------------------
// Tags selector
// -----------------------------
echo "<div style='$divAssignMetadata'>";
    include "./includes/tags.inc.php";
echo "</div>";

// Uses params:
//      $cnt, $step, $where
$cnt   = 1;
$step  = SAVE;
$where = "";
$leftMenu = false;
include "./includes/menuOptions.inc.php";
include "./includes/topFooter.inc.php";
?>