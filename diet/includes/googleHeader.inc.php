<?php
/**
 *
 * @author xavipena
 * 
 */
    include __DIR__."/config.inc.php";

    //----------------------------
    // check for session settings
    // if session is created
    //----------------------------
    if (session_status() === PHP_SESSION_ACTIVE) {

        // Apps with own login procedure
        //-----------------------------------------------------------------------------
        $goPrivateLogin = TRUE;
        $goPrivateLogin = FALSE;
        if (empty($_SESSION['settings'])) 
        {
            if ($goPrivateLogin) {

                header("location: https://diaridigital.net/private.php?sr=3");
            }
        }
    }
    if (empty($_SESSION['settings'])) 
    {
        $_SESSION['settings'] = "Y";
        //----------------------------
        // load session settings
        //----------------------------
        $sql = "select * from project_settings";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result))
        {
            $_SESSION[$row['session']] = $row['value'];
        }
    }
    $_SESSION['pageID'] = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>XaviPena - <?php echo $page?></title> 
    <meta http-equiv="Content-Type" content="text/html"/>
    <meta charset="UTF-8">
<?php 
    $h_description = "Xavi Peña";

    //----------------------------
    // load app description for title
    //----------------------------
    $sql = "select * from apps where page = '$page'";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        $h_description = $row['title'];
    }
?>
    <meta name="description" content="<?php echo $h_description?>">
    <meta name="abstract" content="">
    <meta name="keywords" content="">
    <meta name="robots" content="noindex,nofollow">
    <meta name="lang" content="<?php echo $lang ?>_ES">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
<?php
// ------------------------------------------
// Part from googleHeader.inc.php moved to sideMenuHover_1.inc.php
// To allow css hierarchy override side menu styles
// ------------------------------------------

if (!empty($useCharts) && $useCharts == "Y") {
?>
    <script type="text/javascript" src="../js/chart.min.js"></script>
    <script type="text/javascript" src="../js/chart-utils.min.js"></script>
<?php
}
?>
    <script type="text/javascript" src="./js/api.js"></script>

