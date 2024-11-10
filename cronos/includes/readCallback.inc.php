<?php
$sql = "select url from crono_url";
$result = mysqli_query($db, $sql);
if ($row = mysqli_fetch_array($result)) {

    if ($row['url'] != "") {

        $url = $row["url"];
    }
}
?>