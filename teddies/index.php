<?php
    session_start();
    if (empty($_SESSION['runner_id'])) header("location: login.php");
    else header("location: cover.php");
?>