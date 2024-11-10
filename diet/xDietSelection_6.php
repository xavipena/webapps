<?php
session_start();

$_SESSION['meal'] = $_GET['meal'];

Header("location: xDietSelection.php");
