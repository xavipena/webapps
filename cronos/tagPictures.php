<?php
include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";
include "./includes/topHeader.inc.php";
include "./includes/functions.inc.php";
include "./includes/saveCallback.inc.php";
$textID = 10;
?>
<script>
    function ShowPic(photo, link)
    {
        var parameters = "?piwigo=" + photo + "&lnk=" + link;
        var sDialog ="./showPicture.php";
        sDialog += parameters;
        window.frames[0].location.href = sDialog;
    }
</script>
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
    <div class="wrapperSingle">
<?php
$sql =  "select * from crono_photos ".
        "where IDperson = 0 ".
        "  and IDgroup = 0 ".
        "  and IDdetail = 0 ".
        "  and IDtype = 0 limit 1";
if ($_SESSION['queryany'] == YES)
{
    $sql = str_replace(' and ',' or ', $sql);
}
$result = mysqli_query($db, $sql);
if ($row = mysqli_fetch_array($result)) 
{
    // Show tags
    $photo = $row['IDphoto'];
    $cnt = 1;

    $sql = "delete from crono_sel_work";
    mysqli_query($db, $sql);
    $sql =  "insert into crono_sel_work set ".
            " IDphoto   = ".$row['IDphoto'].
            ",IDsubject = ".$row['IDsubject'].
            ",year      = ".$row['year'].
            ",month     = ".$row['month'].
            ",country   ='".$row['country']."'".
            ",IDcity    = ".$row['IDcity'].
            ",IDevent   = ".$row['IDevent'].
            ",IDperson  = ".$row['IDperson'].
            ",IDgroup   = ".$row['IDgroup'].
            ",IDdetail  = ".$row['IDdetail'].
            ",IDtype    = ".$row['IDtype'];
    mysqli_query($db, $sql);

    // update current selection for TAGGING
    $sql = "select hash from crono_selection where IDline = ".TAGGING;
    $result = mysqli_query($db, $sql);
    $hash = mysqli_fetch_array($result)[0];
    if ($hash == HASH_EMPTY) {

        $hash = $row['IDsubject'].$row['year'].$row['month'].$row['country'].$row['IDcity'].
        $row['IDevent'].$row['IDperson'].$row['IDgroup'].$row['IDdetail'].$row['IDtype'];
        
        $sql =  "update crono_selection set ".
        " IDsubject = ".$row['IDsubject'].
        ",year      = ".$row['year'].
        ",month     = ".$row['month'].
        ",country   ='".$row['country']."'".
        ",IDcity    = ".$row['IDcity'].
        ",IDevent   = ".$row['IDevent'].
        ",IDperson  = ".$row['IDperson'].
        ",IDgroup   = ".$row['IDgroup'].
        ",IDdetail  = ".$row['IDdetail'].
        ",IDtype    = ".$row['IDtype'].
        ",hash      ='$hash'".
        " where IDline = ".TAGGING;
        mysqli_query($db, $sql);
    }
}

echo "<div id='contentframe' style='position:relative; top:10px; left:$wrapperLeft; height:500px; width:800px;$border'>";
echo "<iframe id='photoImg' frameborder='0' style='height:100%; width:100%; top:0px; bottom:0px;'></iframe>";
echo "</div>";

// -----------------------------
// wrapper close
// -----------------------------
echo "</div>"; 

// -----------------------------
// Tags selector
// -----------------------------
echo "<div style='$divAssignMetadata'>";
    include "./includes/tags.inc.php";
echo "</div>";

// Uses params:
//      $cnt, $step, $where, $leftMenu
// $cnt = 1;
$step  = ASSIGN;
$where = "";
$leftMenu = false;
include "./includes/menuOptions.inc.php";
include "./includes/topFooter.inc.php";

echo "<script>";
// Show image if received via parameter
if ($photo != "0") { echo "ShowPic($photo, false);"; }
echo "</script>";
?>
