<?php
/*
Update user weight
*/
session_start();
include '../includes/dbConnect.inc.php';
include "../includes/app.security.inc.php";

$prd = $clean['prd'];

$sql = "update diet_product_list set done = 1 where IDproduct = $prd";
mysqli_query($db, $sql);
Header("Location: shoppingList.php");
