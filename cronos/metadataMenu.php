<?php
include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";
include "./includes/topHeader.inc.php";
?>
</head>
<body>
<?php 
// -----------------------------
// popup for tag selection 
// -----------------------------
include "./includes/popup.inc.php";
// -----------------------------
// loader to wait while next page loads 
// -----------------------------
include "./includes/loader.inc.php";

?>

    <main>
        <div class="wrapper">
<?php 
function AddCard($id, $name, $description)
{
    echo "<div class='card'>";
    echo "   <div class='round_transparent'>";
    echo "      <table cellpadding='5px'><tr><td>";
    echo "          <a href='metadata.php?id=$id'>";
    echo "              <img width='70px' src='./images/tag.png'>";
    echo "          </a>";
    echo "      </td><td>";
    echo "          <a href='metadata.php?id=$id'>$name</a><br><span class='subtitleText'>$description</span>";
    echo "      </td><tr></table>";
    echo "   </div>";
    echo "</div>";
}

AddCard(SUBJECT, locale("strSubject"), locale("strSubjectDs"));
AddCard(CITY, locale("strCity"), locale("strCityDs"));
AddCard(EVENT, locale("strEvent"), locale("strEventDs"));
AddCard(PERSON, locale("strPerson"), locale("strPersonDs"));
AddCard(GROUP, locale("strGroup"), locale("strGroupDs"));
AddCard(DETAIL, locale("strDetail"), locale("strDetailDs"));
AddCard(TYPE, locale("strType"), locale("strTypeDs"));

// -----------------------------
// wrapper close
// -----------------------------
echo "</div>"; 

// -----------------------------
// get callback
// -----------------------------
$url = "metadataMenu.php";
include "./includes/readCallback.inc.php";

echo "<button type='button' onclick='location.href=\"$url\"'>".locale("strGoBack")."</button>";

include "./includes/topFooter.inc.php";
?>
