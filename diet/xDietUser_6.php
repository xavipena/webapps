<?php
/*
Change period
*/
session_start();
$period = $_POST['dtPeriod'];

//--- Settings ------------------------

//--- functions -----------------------

//--- Content ---------------------

$_SESSION['diet_period'] = $period;
Header("Location: xDietUser_1.php");

