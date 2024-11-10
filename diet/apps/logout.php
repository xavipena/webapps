<?php

/* The below code is perfect for a logout script to totally delete everything and start new.  
It even works in Chrome which seems to not work as other browsers when trying do logout and 
start a new session.
*/
    session_start();
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(),'',0,'/');
    session_regenerate_id(true);
    
    header("location: ./menu.php");
?>