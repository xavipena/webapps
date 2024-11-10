<?php
$sql =  "update crono_selection set ".
        " IDsubject = ".$_SESSION['subject'].
        ",year =      ".$_SESSION['year'].
        ",month =     ".$_SESSION['month'].
        ",country =  '".$_SESSION['country']."'".
        ",IDcity =    ".$_SESSION['city'].
        ",IDevent =   ".$_SESSION['event'].
        ",IDperson =  ".$_SESSION['person'].
        ",IDgroup =   ".$_SESSION['group'].
        ",IDdetail =  ".$_SESSION['detail'];
        ",IDtype =    ".$_SESSION['type'].
        " where IDline = ".$typeOfLine;

mysqli_query($db, $sql); 
?>