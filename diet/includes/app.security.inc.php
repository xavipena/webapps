<?php
    $canProceed = false;
    
    // Sanitize input
    //-----------------------------------------------------------------------------
    $clean = array();
    foreach(array_keys($_REQUEST) as $key)
    {
        $clean[$key] = mysqli_real_escape_string($db, $_REQUEST[$key]);
    }

    $canProceed = true;
?>
