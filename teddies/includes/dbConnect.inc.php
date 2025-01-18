<?php

try {
    
    //---- Database local-------------
    ini_set('display_errors',1); 
    error_reporting(E_ALL);
    $db4 = new mysqli('qahz993.diaridigital.net','qahz993','photoBlog10','qahz993');
    if ($db4->connect_errno) 
    {
        printf("Connect failed: %s\n", $db4->connect_error);
        exit();
    }
    mysqli_set_charset($db4, 'utf8');
    
    //---- Database global------------
    ini_set('display_errors',1); 
    error_reporting(E_ALL);
    
    // base de dades de producció
    // $db = new mysqli("qagg302.photoadict.com","qagg302","CrisRami2023","qagg302");
    $db = new mysqli('qahz995.diaridigital.net','qahz995','CrisRami2023','qahz995');
    /* check connection */
    if ($db->connect_errno) {
        printf("Connect failed: %s\n", $db->connect_error);
    }
    mysqli_set_charset($db, 'utf8');
} 
catch (Exception $e) 
{
    //echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    header("location: notavailable.php");
}
?>