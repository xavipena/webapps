<?php
include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";
include "./includes/topHeader.inc.php";
$textID = 1;
$photo = empty($clean['photo']) ? "0" : $clean['photo'];
?>
<script>
    function ShowPic(link) {
        
        var img = document.getElementById("p<?php echo PHOTO?>");
        var parameters = "?piwigo=" + img.value + "&lnk=" + link;
        var sDialog ="./showPicture.php";
        sDialog += parameters;
        window.frames[0].location.href = sDialog;
    }
</script>
</head>
<body>
    <main>
        <div class="wrapperSingle">
<?php
    // functions
    include "./maintenance/add.php";
    if ($photo != 0) {

        // update session selection from image
        include "./includes/getSelection.inc.php";
        // and save
        $typeOfLine = FILTERING;
        include "./includes/movSession2Selection.inc.php";
    }

    echo "<form id='frmAdd' method='post' action='addPhoto.php'>";
    echo "<table>".PHP_EOL;
    
    echo $rowStart."Photo   ".$newCol."<input type='text' id='p".PHOTO."' name='p".PHOTO."' value='$photo' onChange='ShowPic()' readonly>".$rowEnd.PHP_EOL;

    echo $rowStart."Subject ".$newCol.MountSelect($db, SUBJECT, "select IDsubject, name from crono_subjects", $_SESSION['subject']).$rowEnd.PHP_EOL;
    echo $rowStart."Year    ".$newCol.MountDate(YEAR, $_SESSION['year']).$rowEnd.PHP_EOL;
    echo $rowStart."Month   ".$newCol.MountDate(MONTH, $_SESSION['month']).$rowEnd.PHP_EOL;
    echo $rowStart."Country ".$newCol.MountSelect($db, COUNTRY, "select IDcountry, name from countries", $_SESSION['country']).$rowEnd.PHP_EOL;
    echo $rowStart."City    ".$newCol.MountSelect($db, CITY, "select IDcity, name from crono_cities", $_SESSION['city']).$rowEnd.PHP_EOL;
    echo $rowStart."Event   ".$newCol.MountSelect($db, EVENT, "select IDevent, name from crono_events", $_SESSION['event']).$rowEnd.PHP_EOL;
    echo $rowStart."Person  ".$newCol.MountSelect($db, PERSON, "select IDperson, name from crono_persons", $_SESSION['person']).$rowEnd.PHP_EOL;
    echo $rowStart."Group   ".$newCol.MountSelect($db, GROUP, "select IDgroup, name from crono_groups", $_SESSION['group']).$rowEnd.PHP_EOL;
    echo $rowStart."Detall  ".$newCol.MountSelect($db, DETAIL, "select IDdetail, name from crono_details", $_SESSION['detail']).$rowEnd.PHP_EOL;
    echo $rowStart."Tipus   ".$newCol.MountSelect($db, TYPE, "select IDtype, name from crono_types", $_SESSION['type']).$rowEnd.PHP_EOL;
    
    echo "</table>";
    echo "<br>";
    echo "<input type='submit' value='OK'>";
    echo "<input type='button' value='Torna enrere' onclick='history.back()'>";
    echo "</form>";

    echo "<iframe id='photoImg' frameborder='0' height='400px' width='600px'></iframe>";

include "./includes/topFooter.inc.php";
?>
<script>
<?php
// Show image if received via parameter
if ($photo != "0") { echo "ShowPic(false);"; }?>
</script>
