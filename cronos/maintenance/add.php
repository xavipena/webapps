<?php

function MountSelect($db, $id, $sql, $currentValue)
{
    $output = "<select id='p$id' name='p$id'>";
    $output .= "<option value= '0'>* Tots *</option>";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    {
        $selected = $row[0] == $currentValue ? "selected" : "";
        $output .= "<option $selected value='".$row[0]."'>".$row[1]."</option>";
    }
    $output .= "</select>";
    return $output;
}

function MountDate($what, $currentValue)
{
    $output = "<select id='p$what' name='p$what'>";
    switch($what)
    {
        case YEAR:
            for ($i = 2005; $i <= 2023; $i++)
            {
                $selected = $i == $currentValue ? "selected" : "";
                $output .= "<option $selected value='$i'>$i</option>";
            }
            break;
            
            case MONTH:
                for ($i = 1; $i <= 12; $i++)
                {
                    $selected = $i == $currentValue ? "selected" : "";
                    $valueName = date('F', mktime(0, 0, 0, $i, 10));
                $output .= "<option $selected value='$i'>".$valueName."</option>";
            }
            break;
    }
    $output .= "</select>";
    return $output;
}

?>