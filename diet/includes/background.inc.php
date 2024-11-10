<?php
$thisPage = basename($_SERVER['PHP_SELF']);
$sql ="select * from diet_backgrounds where pageName = '$thisPage'";
$result = mysqli_query($db, $sql);
if ($row = mysqli_fetch_array($result))
{
    $dir = $row['direction'];
    $per = $row['size'];
    $img = $row['image'];

    echo "    <style>";
    echo "        main {";
    echo "            background-repeat: no-repeat;";
    echo "            background: linear-gradient(to $dir, transparent , #1f1f1f $per%), url('./images/$img');";
    echo "            background-size: cover;";
    echo "        }";
    echo "    </style>";
}
?>