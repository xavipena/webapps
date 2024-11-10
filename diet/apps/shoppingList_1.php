<?php
/*
Update user weight
*/
session_start();
include '../includes/dbConnect.inc.php';
include "../includes/app.security.inc.php";

$prd = $clean['prd'];

$sql = "delete from diet_product_list where IDproduct = $prd";
mysqli_query($db, $sql);
Header("Location: shoppingList.php");
