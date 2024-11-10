<?php
include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";
include "./includes/topHeader.inc.php";
$textID = 6;

$type   = empty($clean['id']) ? SUBJECT : $clean['id'];
include "./includes/tableSelector.inc.php";
?>
<script>
    function CheckOut()
    {
        var txt = document.getElementById("mText").value;
        if (txt != "") {

            var fr = document.getElementById("mfrm");
            fr.submit();
        }
        else alert("<?php echo locale("strNoData")?>");
    }
</script>
</head>
<body>
    <main>
        <div>

        <h2><?php echo $title?></h2>
        <form id="mfrm" method="post" action="./maintenance/saveMetadata.php" onsubmit="CheckOut()">

            <input type="hidden" value="<?php echo $type?>" id="mType" name="mType">
            <input type="text" value="" id="mText" name="mText" size="40">
            <input type="submit" value="<?php echo locale("strAdd")?>">
        </form>

    <script>
        // (table, fieldID, fieldName, link, format, destDiv, selected)
        GetDetails(<?php echo "'$tableName','$fieldID','name', false, 'table', 'filterData', ''"?>);
    </script>

<?php
echo "</div>"; // wrapper close

echo "<br>";
echo "<button type='button' onclick='location.href=\"metadataMenu.php\"'>Torna enrere</button>";
echo "<br><hr>".locale("strCurrValues").":";

echo "<div id='filterData'>";
echo "</div>";

include "./includes/topFooter.inc.php";
?>