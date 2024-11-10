<?php 
    function SetTableName($id) {

        $tableName  = "";
        $fieldID    = "";
        switch ($id)
        {
            case SUBJECT:
                $tableName = "crono_subjects";
                $fieldID = "IDsubject";
                break;
            case COUNTRY:
                $tableName = "countries";
                $fieldID = "IDcountry";
                break;
            case CITY:
                $tableName = "crono_cities";
                $fieldID = "IDcity";
                break;
            case EVENT:
                $tableName = "crono_events";
                $fieldID = "IDevent";
                break;
            case PERSON:
                $tableName = "crono_persons";
                $fieldID = "IDperson";
                break;
            case GROUP:
                $tableName = "crono_groups";
                $fieldID = "IDgroup";
                break;        
            case DETAIL:
                $tableName = "crono_details";
                $fieldID = "IDdetail";
                break;
            case TYPE:
                $tableName = "crono_types";
                $fieldID = "IDtype";
                break;
        }
        return [$tableName, $fieldID];
    }

    function WriteCurrentSelector($db, $id, $value, $link, $format, $destDiv, $selected) {

        $rowStart = $GLOBALS['rowStart']; 
        $rowEnd   = $GLOBALS['rowEnd'];
        $newCol   = $GLOBALS['newCol'];

        $format = empty($format) ? "list" : $format;
        $valueName = locale("strAll");
        $fieldID = "";
        echo $rowStart;
        switch ($id)
        {
            case SUBJECT:
                $filterName = locale("strSubject");
                $tableName = "crono_subjects";
                $fieldID = "IDsubject";
                $sql = "select name from crono_subjects where IDsubject = $value";
                break;
            case YEAR:
                $filterName = locale("strYear");
                if ($value > 0) $valueName = $value;
                break;
            case MONTH:
                $filterName = locale("strMonth");
                if ($value > 0) $valueName = date('F', mktime(0, 0, 0, $value, 10));
                break;
            case COUNTRY:
                $filterName = locale("strCountry");
                $tableName = "countries";
                $fieldID = "IDcountry";
                $sql = empty($value) ? "" : "select name from countries where IDcountry = '$value'";
                break;
            case CITY:
                $filterName = locale("strCity");
                $tableName = "crono_cities";
                $fieldID = "IDcity";
                $sql = "select name from crono_cities where IDcity = $value";
                break;
            case EVENT:
                $filterName = locale("strEvent");
                $tableName = "crono_events";
                $fieldID = "IDevent";
                $sql = "select name from crono_events where IDevent = $value";
                break;
            case PERSON:
                $filterName =locale("strPerson");
                $tableName = "crono_persons";
                $fieldID = "IDperson";
                $sql = "select name from crono_persons where IDperson = $value";
                break;
            case GROUP:
                $filterName = locale("strGroup");
                $tableName = "crono_groups";
                $fieldID = "IDgroup";
                $sql = "select name from crono_groups where IDgroup = $value";
                break;        
            case DETAIL:
                $filterName = locale("strDetail");
                $tableName = "crono_details";
                $fieldID = "IDdetail";
                $sql = "select name from crono_details where IDdetail = $value";
                break;
            case TYPE:
                $filterName = locale("strType");
                $tableName = "crono_types";
                $fieldID = "IDtype";
                $sql = "select name from crono_types where IDtype = $value";
                break;
        }
        if (!empty($sql))
        {
            $result = mysqli_query($db, $sql);
            if ($row = mysqli_fetch_array($result)) 
            {
                $valueName = $row['name'];
            }
        }
        if ($link)
        {
            if ($id == YEAR || $id == MONTH) 
            {
                echo "<a href='javascript:GetDates($id, \"table\", \"filterData\",\"\",true,\"".locale("strAll")."\",".FILTERING.")'>".$filterName."</a>".$newCol.$valueName;
            }
            else
            {
                //function GetDetails(table, fieldID, fieldName, link, format, destDiv, selected) 
                echo "<a href='javascript:GetDetails(\"$tableName\", \"$fieldID\", \"name\", true,\"$format\",\"\",\"$selected\",\"Tots\",".FILTERING.")'>".$filterName."</a>".$newCol.$valueName;
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
// Write current filter values
// --------------------

    echo "<table>";
    echo "<caption>Dimensió seleccionada: $dimension</caption>".$rowStart;    
    $sql = "select * from crono_selection where IDline = ".FILTERING;
    $result = mysqli_query($db, $sql);
    if ($row = mysqli_fetch_array($result)) 
    {
        echo "<table width='250px'>";
        // ($db, $id, $value, $link, $format, $destDiv, $selected)
        WriteCurrentSelector($db, SUBJECT, $row['IDsubject'], true, "table", "filterDataNav","");
        WriteCurrentSelector($db, DETAIL, $row['IDdetail'], true, "table", "filterDataNav", "");
        WriteCurrentSelector($db, TYPE, $row['IDtype'], true, "table", "filterDataNav", "");
        echo "</table>";

        echo $newCol;
        echo "Canviar a:<br>";
        ?>
        <a href="javascript:CallPage('<?php echo $element?>','navigation.php?dimension=time')">Temporal</a><br>
        <a href="javascript:CallPage('<?php echo $element?>','navigation.php?dimension=place')">Ubicación</a><br>
        <a href="javascript:CallPage('<?php echo $element?>','navigation.php?dimension=people')">Personas</a>
<?php
/*
        switch($dimension)
        {
            case "time":

                echo $newCol."<table width='200px'>";
                WriteCurrentSelector($db, COUNTRY, $row['country'], false, "", "", $_SESSION['country']);
                WriteCurrentSelector($db, CITY, $row['IDcity'], false, "", "", $_SESSION['city']);
                WriteCurrentSelector($db, EVENT, $row['IDevent'], false, "", "", $_SESSION['event']);
                echo "</table>";
                
                echo $newCol."<table width='200px'>";
                WriteCurrentSelector($db, PERSON, $row['IDperson'], false, "", "", "");
                WriteCurrentSelector($db, GROUP, $row['IDgroup'], false, "", "", "");
                echo "</table>";
                break;

            case "place":

                echo $newCol."<table width='200px'>";
                WriteCurrentSelector($db, YEAR, $row['year'], false, "", "", "");
                WriteCurrentSelector($db, MONTH, $row['month'], false, "", "", "");
                echo "</table>";
                
                echo $newCol."<table width='200px'>";
                WriteCurrentSelector($db, PERSON, $row['IDperson'], false, "", "", "");
                WriteCurrentSelector($db, GROUP, $row['IDgroup'], false, "", "", "");
                echo "</table>";
                break;

            case "people":
                
                echo $newCol."<table width='200px'>";
                WriteCurrentSelector($db, COUNTRY, $row['country'], false, "", "", "");
                WriteCurrentSelector($db, CITY, $row['IDcity'], false, "", "", "");
                WriteCurrentSelector($db, EVENT, $row['IDevent'], false, "", "", "");
                echo "</table>";
                
                echo $newCol."<table width='200px'>";
                WriteCurrentSelector($db, YEAR, $row['year'], false, "", "", "");
                WriteCurrentSelector($db, MONTH, $row['month'], false, "", "", "");
                echo "</table>";
                break;
        }
        */
    }
    
    echo $rowEnd."</table>";
?>