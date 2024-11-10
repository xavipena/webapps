<?php
include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";
include "./includes/topHeader.inc.php";
?>
</head>
<body>
    <?php // loader to wait while next page loads 
    include "./includes/loader.inc.php";

$sql = "select url from crono_url";
$result = mysqli_query($db, $sql);
if ($row = mysqli_fetch_array($result)) {

    echo "<script>document.location.href='".$row['url']."'</script>";
}
else echo "Nowhere to go";
?>
</body>
</html>