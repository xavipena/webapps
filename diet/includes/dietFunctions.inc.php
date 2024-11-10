<?php
/* 
    Constants
*/
define("CALS_PER_KG",    7700);
/* 
    Functions
*/

/*
    ReadCallback($db)
    params
        $db --> database handler
    return $url
*/
function ReadCallback($db)
{
    $url = "";
    $sql = "select value from diet_config where IDconfig = 'url'";
    $result = mysqli_query($db, $sql);
    if ($row = mysqli_fetch_array($result)) {
    
        $url = $row['value'];
    }
    return $url;
}

/*
    SaveCallback($db)
    params
        $db --> database handler
*/
function SaveCallback($db)
{
    $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $sql = "update diet_config set value = '$actual_link' where IDconfig = 'url'";
    mysqli_query($db, $sql);
}

/*
    DietSettings($db, $localSettings) 
    params
        $db --> database handler
        $localSettings --> settings to show
    return strings to print
*/
function DietSettings($db, $localSettings) 
{
    $user = empty($_SESSION['diet_user']) ? 0 : $_SESSION['diet_user'];
    $output = "";
    $sql =  "select ds.IDsetting IDsetting, ds.name name, ds.session session, us.value value ".
            "from diet_user_settings us ".
            "join diet_settings ds ".
            "  on ds.IDsetting = us.IDsetting and ds.IDsetting in ($localSettings)".
            "where us.IDuser = $user";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        $sStatus = $row['value'] == "Y" ? "ON" : "OFF";
        $_SESSION[$row['session']] = $row['value'];

        SaveCallback($db);
        $output .= "<a href='https://diaridigital.net/diet/xDietSettingUpd.php?id=".$row['IDsetting']."'>".
                "  <img style='height:30px; vertical-align:middle' src='../images/switch".$sStatus.".png'>".
                "</a> ".$row['name'];
    }
    //$output .= "<br>";
    return $output;
}

/* 
    GetSettingValue($db, $setting)
    params
        $db      --> database handler
        $setting --> setting to get
    return
        --> setting value
*/
function GetSettingValue($db, $setting)
{
    $user = empty($_SESSION['diet_user']) ? 0 : $_SESSION['diet_user'];
    $value = "";
    $sql =  "select us.value value ".
            "from diet_user_settings us ".
            "where us.IDuser = $user and us.IDsetting = $setting";
    $result = mysqli_query($db, $sql);
    if ($row = mysqli_fetch_array($result))
    {
        $value = $row['value'];
    }
    return $value;
}

/* 
    getImage($prefix, $im)
    params
        $prefix --> image prefix
        $im     --> imagesequence
    return
        --> image with relative path
*/
function getImage($prefix, $im) {

    $imagePath = "./images/"; 
    $fullname  = $imagePath.$prefix."_".$im;
    $image = $fullname.".jpg";
    if (!file_exists($image)) {

        $image = $fullname.".png";
        if (!file_exists($image)) {

            $image = $fullname.".webp";
            if (!file_exists($image)) {

                $image = $imagePath."notfound.png";
            }
        }
    }
    return $image;
}
