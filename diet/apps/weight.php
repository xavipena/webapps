<?php
    session_start();
    include ".././includes/dbConnect.inc.php";
    include "./includes/app.security.inc.php";
    include "./includes/app.header.inc.php";

    if (empty($_SESSION['diet_user'])) header("location: user.php");
?>
</head>
<body>
<?php 

//--- Settings ------------------------

//--- new content ---------------------

//--- functions -----------------------

function ListWeight($db)
{
    $rowStart = $GLOBALS['rowStart'];
    $rowEnd = $GLOBALS['rowEnd'];
    $newColNum = $GLOBALS['newColNum'];

    $output  = "<table width='200px' style='margin: auto;'>";
    $output .= "<thead><tr><th>Data</th><th>Pes</th></tr></thead>";
    $output .= "<tr><td colspan='2'><hr>".$rowEnd;
    $sql ="select * from diet_user_data where IDuser = ".$_SESSION['diet_user']." and IDperiod = ".$_SESSION['diet_period']." order by date desc limit 3";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        $output .= $rowStart.$row['date'].$newColNum.$row['weight'].$rowEnd;
    }    
    $output .= "</table>";
    return $output;
}

//--- Content -------------------------

?>
    <div class='appPageContainer'>
        <header>
            <div class='cardCircle'>
                <div class='cardTitle'>
                <?php echo $_SESSION['diet_user'] == "1" ? "Xavi" : "Cris"; ?>
                </div>
            </div>
        </header>
        <main>

            <form action="../xDietUser_5.php" class="app-form" method="post">
                <input type='hidden' name='fromApp' value='1'>

                <div class="app-content">

                    <div class="app-box">
                        <i class="ri-calendar-line app-icon"></i>

                        <div class="app-box-input">
                            <input type="date" class="app-input" id="dtDate" name="dtDate" placeholder="" value="<? echo date("Y-m-d")?>">
                            <label for="" class="app-label">Data</label>
                        </div>
                    </div>

                    <div class="app-box">
                        <i class="ri-price-tag-3-line app-icon"></i>

                        <div class="app-box-input">
                            <input type="number" step="any" required class="app-input" value="" placeholder=" " id="dtWeight" name="dtWeight">
                            <label for="" class="app-label">Pes</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="app-button">Envia</button>
                <button type="button" class="app-button" onclick="location.href='menu.php'">Menú</button>
                
                <div class="app-content">
                    <?php echo ListWeight($db); ?>
                </div>

            </form>
        </main>
<?php 
    include "./includes/app.footer.inc.php";

// --- end content -------------------
?>

