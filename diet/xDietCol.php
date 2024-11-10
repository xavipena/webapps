<?php
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    unset($_SESSION['IDsaga']);
    unset($_SESSION['IDauthor']);
    include './includes/dbConnect.inc.php';
    include './includes/googleHeader.inc.php';
    include './includes/googleSecurity.inc.php';
    include "./includes/settingsStart.inc.php";
?>
</head>
<body>
<?php 
    $_SESSION['pageID'] = PAGE_DIET_MENU;    
    $page ="diet";
    $sourceTable ="";
    
    //--- new content -------------------- 
    
    //echo "<div class='dietPageContainer'>";
    include "./includes/menu.inc.php"; 
    include "./includes/dietMenu.inc.php";

    echo "<table width='100%' cellpadding='5'>";
    echo "<tr><td colspan='4'><h1>Colesterol</h1></td></tr>";
    echo "<tr><td colspan='2'><h2>Bo</h2></td><td colspan='2'><h2>Dolent</h2></td></tr>";
    $c =0;
    $sql ="select * from diet where facts = '' ";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        echo "<tr>";
        if (!empty($row['Good'])) echo "<td width='20%'>".$row['Good']."</td><td width='30%'>".$row['TextGood']."</td><td></td><td></td>";
        if (!empty($row['Bad'])) echo "<td></td><td></td><td width='20%'>".$row['Bad']."</td><td width='30%'>".$row['textBad']."</td>";
        echo "</tr>";
        $c += 1;
    }
    echo "</td></tr></table>";

    echo "<table width='100%' cellpadding='5'>";
    echo "<tr><td><h1>Fets</h1></td></tr>";
    $c =0;
    $sql ="select * from diet where facts <> '' ";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        echo "<tr>";
        echo "<td>".$row['facts']."</td>";
        if (!empty($row['reference'])) echo "<td><a href='".$row['reference']."'>font</a></td>";
        echo "</tr>";
        $c += 1;
    }
    echo "</td></tr></table>";

    include './includes/googleFooter.inc.php';
?>