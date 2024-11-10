<?php 

    function WriteCurrentSelector($db, $id, $value)
    {
        $rowStart = $GLOBALS['rowStart']; 
        $rowEnd   = $GLOBALS['rowEnd'];
        $newCol   = $GLOBALS['newCol'];

        echo $rowStart;

        $array = GetDescription($db, $value, $id, FILTERING);
        $filterName = $array[0];
        $tableName  = $array[1];
        $fieldID    = $array[2];
        $valueName  = $array[3];
        
        if ($valueName == locale("strAll"))
        {
            $valueName = "<span style='color:darkgray;'>".$valueName."</span>";
        }
        
        if ($id == YEAR || $id == MONTH) 
        {
            echo "<a href='javascript:GetDates($id, \"table\", \"\",\"\",true,\"".locale("strAll")."\",".FILTERING.")'>".$filterName."</a>".$newCol.$valueName;
        }
        else 
        {
            echo "<a href='javascript:GetDetails(\"$tableName\", \"$fieldID\", \"name\",true,\"table\",\"\",\"\",\"".locale("strAll")."\",".FILTERING.")'>".$filterName."</a>".$newCol.$valueName;
        }
        echo $rowEnd;
    }

    function GetFilterData($db)
    {
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

    echo "<table>";
    $sql = "select * from crono_selection where IDline = ".FILTERING;
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

        $_SESSION['url'] = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        echo "<tr><td colspan='2'><br>".locale("strOptions").$rowEnd;
        echo "<tr><td colspan='2'><a href='./background/resetSelection.php?type=".FILTERING."'>Sense filtres</a>$rowEnd";
    }
    echo "</table>";
?>