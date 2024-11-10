<?php
include "./includes/dbConnect.inc.php";
include "./includes/config.inc.php";
include "./includes/topHeader.inc.php";
include "./includes/functions.inc.php";

if (!empty($clean['rld']))
{
    // force load session settings
    $sql = "select * from crono_settings";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        $_SESSION[$row['session']] = $row['value'];
    }
}
?>
<body>
<main>
    <h2>Settings</h2>
    <div class="wrapperSingle">
<?php

// -------------------- 
// functions
// -------------------- 
function CheckRows()
{
    $GLOBALS['c'] += 1;
    $cols = intval($_SESSION['numCols']);
    if ($cols == 0) $cols = 3;
    if ($GLOBALS['c'] % $cols == 0)
    {
        echo "</div>".PHP_EOL;
        echo "<div class='Row'>";
        $GLOBALS['c'] = 0;
    }
}

function FillCols($c)
{
    // fill cols
    for ($i = $c; $i < $_SESSION['numCols']; $i++) 
    { 
        echo "<div class='Column'><div class='round_transparent'><img height='30px' style='vertical-align:middle' src='../images/switchOFF.png'></div></div>"; 
    }
}

// -------------------- 
// Content
// -------------------- 

$c = 0;

$sql = "select * from crono_settings where IDsetting < 100";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result))
{
    if ($row['type'] == "bool")
    {
        $currentValue = "";
        $sStatus = $row['value'] == YES ? "ON" : "OFF";
    }
    else
    {
        // if type value, set the values
        $currentValue = "";
        switch ($row['session'])
        {
            case "coverpics":
                $sStatus = $row['value'] == 3 ? "ON" : "OFF";
                break;
            case "numCols":
                $sStatus = $row['value'] == 3 ? "ON" : "OFF";
                break;
            case "language":
                $sStatus = $row['value'] == "ca" ? "ON" : "OFF";
                break;
        }
    }
    $sessionValue = empty($row['session']) ? $row['value'] : $_SESSION[$row['session']];

    echo "<div class='card'>";
    echo "   <div class='round_transparent'>";
    echo "      <table cellpadding='5px'><tr><td>";
    echo "          <a href='./background/updateSettings.php?id=".$row['IDsetting']."'>";
    echo "              <img height='30px' style='vertical-align:middle' src='../images/switch".$sStatus.".png'>";
    echo "          </a>";
    echo "      </td><td>";
    echo "          ".$row['name'].$currentValue;
    echo "          <br>Session value: $sessionValue<br><span class='smallText'>".$row['description']."</span>";
    echo "      </td><tr></table>";
    echo "   </div>";
    echo "</div>";
}

// -------------------- 
// wrapper close
// -------------------- 
echo "</div>"; 
    
// -------------------- 
// Add the local menu when the DOM is fully loaded
// -------------------- 
?>
    <script>
        window.onload = (event) => {
            
            var mdiv = document.getElementById("localMenu");
            mdiv.innerHTML = "<a href='settings.php?rld=1'>Reload</a>&nbsp;|&nbsp;";
        };
    </script>
<?php
// -------------------- 
// end content
// -------------------- 

include "./includes/topFooter.inc.php";
?>