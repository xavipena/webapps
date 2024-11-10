<?php
session_start();
$runner_id ="";
if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
$page = "diet";
include '.././includes/dbConnect.inc.php';
include '.././includes/googleSecurity.inc.php';
include '.././includes/googleHeader.inc.php';
?>
    <link rel="stylesheet" type="text/css" href="../css/innerDiet.css" />
</head>
<body>
<?php 

// --------------------------------
// Print table
// --------------------------------

echo "<table>";
$sql =  "select * from diet_products where name like '%".$clean['param']."%'";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result))
{
    echo "<tr><td class='number'>".$row['IDproduct']."</td><td>".$row ['name']."</td></tr>";
}
echo "</table>";
echo "</div></body></html>";