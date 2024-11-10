<?php
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $page = "diet";
    include './includes/dbConnect.inc.php';
    include './includes/googleSecurity.inc.php';

    // --------------------------------
    // functions
    // --------------------------------

    include './includes/settingsStart.inc.php';

    // --------------------------------
    // Params
    // --------------------------------

    $prd = empty($clean['prd']) ? 0 : $clean['prd'];
    $mix = empty($clean['mix']) ? 0 : $clean['mix'];

    // --------------------------------
    // Update meal type
    // --------------------------------

    $sql =  "update diet_user_selection set IDmeal = ".$clean['meal'].
            " where IDuser = ".$_SESSION['diet_user'].
            " and IDproduct = ".$prd.
            " and IDmix = ".$mix.
            " and IDmeal = ".$clean['old'];

//ShowSQL($sql);
//exit;

    if (mysqli_query($db, $sql))
    {
        Header("Location: xDietSelection.php");
    }
    else
    {
        echo mysqli_error($db);
    }
