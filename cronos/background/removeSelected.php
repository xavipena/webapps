<?php
include "../includes/dbConnect.inc.php";
include "../includes/config.inc.php";

$sql =  "delete from crono_sel_work";
mysqli_query($db, $sql);
Header("Location: ../selectSelected.php");
?>