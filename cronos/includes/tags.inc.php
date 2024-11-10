<?php 

    function WriteCurrentSelector($db, $id, $value, $link = true) {

        $rowStart = $GLOBALS['rowStart']; 
        $rowEnd   = $GLOBALS['rowEnd'];
        $newCol   = $GLOBALS['newCol'];

        echo $rowStart;

        $array = GetDescription($db, $value, $id, TAGGING);
        $filterName = $array[0];
        $tableName  = $array[1];
        $fieldID    = $array[2];
        $valueName  = $array[3];

        if ($valueName == locale("strNone"))
        {
            $valueName = "<span style='color:darkgray;'>".$valueName."</span>";
        }
        
        if ($link)
        {
            if ($id == YEAR || $id == MONTH) 
            {
                echo "<a href='javascript:GetDates($id, \"table\", \"\",\"\",true,\"".locale("strNone")."\",".TAGGING.")'>".$filterName."</a>".$newCol.$valueName;
            }
            else 
            {
                echo "<a href='javascript:GetDetails(\"$tableName\", \"$fieldID\", \"name\",true,\"table\",\"\",\"\",\"".locale("strNone")."\",".TAGGING.")'>".$filterName."</a>".$newCol.$valueName;
            }
        }
        else echo $filterName.$newCol.$valueName;                
        echo $rowEnd;
    }

    function GetFilterData($db) {

        $rowStart = $GLOBALS['rowStart']; 
        $rowEnd   = $GLOBALS['rowEnd'];
        $newCol   = $GLOBALS['newCol'];

        $tableName = "crono_subjects";

        $output = "<table>";
        $sql = "select name from $tableName";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result)) 
        {
            $output = $rowStart.$row['name'].$rowEnd;
        }
        $output = "</table>";
        return $output;
    }

// --------------------
// Main
// --------------------

echo "Tags<hr><div id='dTags'>";
echo "<table>";
$sql = "select * from crono_selection where IDline = ".TAGGING;
$result = mysqli_query($db, $sql);
if ($row = mysqli_fetch_array($result)) 
{
    WriteCurrentSelector($db, SUBJECT, $row['IDsubject']);
    WriteCurrentSelector($db, YEAR, $row['year']);
    WriteCurrentSelector($db, MONTH, $row['month']);
    WriteCurrentSelector($db, COUNTRY, $row['country']);
    WriteCurrentSelector($db, CITY, $row['IDcity']);
    WriteCurrentSelector($db, EVENT, $row['IDevent']);
    WriteCurrentSelector($db, PERSON, $row['IDperson']);
    WriteCurrentSelector($db, GROUP, $row['IDgroup']);
    WriteCurrentSelector($db, DETAIL, $row['IDdetail']);
    WriteCurrentSelector($db, TYPE, $row['IDtype']);
}
echo "</table></div>";
$_SESSION['url'] = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
echo "<br><a href='javascript:CallPage(\"$element\",\"./background/resetSelection.php?type=".TAGGING."\")'>".locale("strResetTags")."</a>";

if ($showFilters) {

    echo "<br><br>";
    echo "Filtres<hr><div id='dFilters'>";
    echo "<table>";
    $sql = "select * from crono_selection where IDline = ".FILTERING;
    $result = mysqli_query($db, $sql);
    if ($row = mysqli_fetch_array($result)) 
    {
        WriteCurrentSelector($db, SUBJECT, $row['IDsubject'], false);
        WriteCurrentSelector($db, YEAR, $row['year'], false);
        WriteCurrentSelector($db, MONTH, $row['month'], false);
        WriteCurrentSelector($db, COUNTRY, $row['country'], false);
        WriteCurrentSelector($db, CITY, $row['IDcity'], false);
        WriteCurrentSelector($db, EVENT, $row['IDevent'], false);
        WriteCurrentSelector($db, PERSON, $row['IDperson'], false);
        WriteCurrentSelector($db, GROUP, $row['IDgroup'], false);
        WriteCurrentSelector($db, DETAIL, $row['IDdetail'], false);
        WriteCurrentSelector($db, TYPE, $row['IDtype'], false);

        include __DIR__."/saveCallback.inc.php";
        echo "<tr><td colspan='2'><br><a href='./background/copyToTags.php'>".locale("strCopy2Tags")."</a></td></tr>";
    }
}
echo "</table></div>";
?>