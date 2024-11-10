<?php
// --------------------
// Div's definition
// --------------------
$rightMenuSize       = "200px";
$leftMenuSize       = "200px";

$border              = ""; //"border:1px dotted black;";
$divDescription 	 = "position:absolute;width:500px;height:100px;color:#eeeeee;font-size:small;".$border;

$divFilterSelection  = "position:fixed;width:200px;z-index:9;".$border;
$divFilterData  	 = "position:absolute;".$border;
$divFilterDataNav  	 = "position:absolute;".$border;
$divMetadataMenu  	 = "position:absolute;".$border;
$divGalleryMenu  	 = "position:fixed;width:$rightMenuSize;z-index:9;text-align:left;".$border;
$divAssignMetadata   = "position:fixed;".$border;
$divLeftList         = "position:fixed;width:$leftMenuSize;text-align:left;".$border;

$divSliderData_1  	 = "position:absolute;".$border;
$divSliderData_2  	 = "position:absolute;".$border;
$divSliderData_3  	 = "position:absolute;".$border;

$wrapperLeft = "18%";

// els que no tenen sessió són per gestionar els DIVs
$sql = "select * from crono_settings where IDsetting < 100 and session = ''";
$result = mysqli_query($db, $sql);
while( $row = mysqli_fetch_array($result))
{
    switch($row['description'])
    {
        case "divDescription":
            $hidden = $row['value'] == "Y" ? "none" : "inline";
            $divDescription .= "display:$hidden;";
            break;
    }
}

// posicionament dels DIVs
$sql = "select * from crono_settings where IDsetting >= 100 and type <> 'bool'";
$result = mysqli_query($db, $sql);
while( $row = mysqli_fetch_array($result))
{
    switch($row['description'])
    {
        //case "divDescription":
        //    $divDescription .= $row['type'].":".$row['value']."px;";
        //    break;
        case "divFilterSelection":
            $divFilterSelection .= $row['type'].":".$row['value']."px;";
            break;
        case "divFilterData":
            $divFilterData .= $row['type'].":".$row['value']."px;";
            break;
        case "divFilterDataNav":
            $divFilterDataNav .= $row['type'].":".$row['value']."px;";
            break;
        case "divMetadataMenu":
            $divMetadataMenu .= $row['type'].":".$row['value']."px;";
            break;
        case "divAssignMetadata":
            $divAssignMetadata .= $row['type'].":".$row['value']."px;";
            break;

        case "divGalleryMenu":
            $divGalleryMenu .= $row['type'].":".$row['value']."px;";
            break;
        case "divLeftList":
            $divLeftList .= $row['type'].":".$row['value']."px;";
            break;

        case "divSliderData_1":
            $divSliderData_1 .= $row['type'].":".$row['value']."px;";
            break;
        case "divSliderData_2":
            $divSliderData_2 .= $row['type'].":".$row['value']."px;";
            break;
        case "divSliderData_3":
            $divSliderData_3 .= $row['type'].":".$row['value']."px;";
            break;
    } 
}    

?>