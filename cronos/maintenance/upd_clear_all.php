<?php
include "../includes/dbConnect.inc.php";
include "../includes/config.inc.php";

// Photos
$upd =  "delete from crono_photos";
mysqli_query($db, $upd);

// people related to photos
$upd =  "delete from crono_photo_ppl";
mysqli_query($db, $upd);

// albums loaded
$upd =  "delete from crono_pw_albums";
mysqli_query($db, $upd);

Header("Location: ../main.php");
?>