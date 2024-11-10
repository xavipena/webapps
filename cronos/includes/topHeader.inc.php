<?php
    // Header for top level
    $dd = "";
    $dd .= "     _   _                  _       _   _           _   _             _ ".PHP_EOL;
    $dd .= "  __| | (_)   __ _   _ __  (_)   __| | (_)   __ _  (_) | |_    __ _  | |".PHP_EOL;
    $dd .= " / _` | | |  / _` | | '__| | |  / _` | | |  / _` | | | | __|  / _` | | |".PHP_EOL;
    $dd .= "| (_| | | | | (_| | | |    | | | (_| | | | | (_| | | | | |_  | (_| | | |".PHP_EOL;
    $dd .= " \__,_| |_|  \__,_| |_|    |_|  \__,_| |_|  \__, | |_|  \__|  \__,_| |_|".PHP_EOL;
    $dd .= "                                            |___/                       ".PHP_EOL;
?>
<!DOCTYPE html>
<html lang="<?php echo $lang?>">
<head>
    <title>Diari Digital * Cronos</title>  

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html"/>
    <meta charset="UTF-8">
    <meta name="description" content="Diari Digital">
    <meta name="abstract" content="Diari digital Album. Fotografia, Viatges, Cami de Sant jaume, Cims i rutes">
    <meta name="keywords" content="Fotografia, Viatges, Cami de Sant jaume, Cims i rutes">
    <meta name="lang" content="<?php echo $lang?>_ES">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./css/cronos.css">
    <link rel="stylesheet" href="./css/popup.css">
    <link rel="stylesheet" href="./css/others.css">
    <link rel="stylesheet" href="./css/card.css">
    <link rel="stylesheet" href="./css/table.css">
    
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>

    <script type="text/javascript" src="./js/cronos.js"></script>
<!--    
<?php 
    // -----------------------------
    // Load setting
    // -----------------------------

    $sql = "select * from crono_settings where IDsetting < 100";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        if (!empty($row['session']))
        {
            $_SESSION[$row['session']] = $row['value'];
        }
    }

    // -----------------------------
    // Load selection
    // -----------------------------
    
    if (empty($_SESSION['action'])
           || $_SESSION['action'] == RELOAD)
    {
        $sql = "select * from crono_selection where IDline = ".FILTERING;
        $result = mysqli_query($db, $sql);
        if ($row = mysqli_fetch_array($result)) 
        {
            $_SESSION['subject'] = $row['IDsubject'];
            $_SESSION['year']    = $row['year'];
            $_SESSION['month']   = $row['month'];
            $_SESSION['event']   = $row['IDevent'];
            $_SESSION['city']    = $row['IDcity'];
            $_SESSION['country'] = $row['country'];
            $_SESSION['person']  = $row['IDperson'];
            $_SESSION['group']   = $row['IDgroup'];
            $_SESSION['detail']  = $row['IDdetail'];
            $_SESSION['type']    = $row['IDtype'];
            $_SESSION['action']  = LOADED;
        }
    }
    echo $dd;
    $server = "https://diaridigital.net";
    $step   = NONE;
?>
-->
