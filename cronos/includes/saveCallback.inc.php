<?php
$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$sql = "update crono_url set url = '$actual_link'";
mysqli_query($db, $sql);
?>