<?php
    session_start();
    include ".././includes/dbConnect.inc.php";
    include "./includes/app.security.inc.php";
    include "./includes/app.header.inc.php";

    if (empty($_SESSION['diet_user'])) header("location: user.php");
?>
    <script>
        function goToSelector($page) {

            const meal = document.getElementById("dtMel").value;
            if (meal == "" || meal == "0") {

                alert("Selecciona un àpat");
            }
            else window.location.href = $page + "?dtMel=" + meal;
        }
    </script>
    <style>
        .smallButton { 
            border-radius: 5px; 
        }
    </style>
</head>
<body>
<?php

//--- Settings ------------------------

//--- Settings ------------------------

include "./includes/dietFunctions.inc.php";
$selectPerImage = GetSettingValue($db, "3");

//--- new content ---------------------

//--- functions -----------------------

function BtnRemove($meal, $prd) {

    $output = "<input type='button' class='smallButton' value='&nbsp;Treu&nbsp;' onclick='location.href=\"meals_1.php?mea=$meal&prd=$prd\"'>";
    return $output;
}

function MealSelector($db, $current) {

    $mealSelector = "<select onchange='' id='dtMel' name='dtMel' data-placeholder='Àpat'>";
    $mealSelector .= "<option value='' selected>Selecciona un àpat</option>";
    $sql ="select * from diet_meals order by IDmeal";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    {
        //$selected = $row['IDmeal'] == $current ? "selected" : ""; 
        $mealSelector .= "<option value='".$row['IDmeal']."'>".$row['name']."</option>";
    }
    $mealSelector .= "</select>";
    return $mealSelector;
}

function ProdSelector($db) {

    $prodSelector = "<select onchange='' id='dtPrd' name='dtPrd' data-placeholder='Producte'>";
    $prodSelector .= "<option value='' selected>Selecciona un producte</option>";
    $sql ="select * from diet_products where status = 'A' order by name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    {
        $prodSelector .= "<option value='".$row['IDproduct']."'>".$row['name']."</option>";
    }
    $prodSelector .= "</select>";
    return $prodSelector;
}

function DishSelector($db) {

    $dishSelector = "<select onchange='' id='dtDsh' name='dtDsh' data-placeholder='Plat'>";
    $dishSelector .= "<option value='' selected>Selecciona un plat</option>";
    $sql ="select * from diet_dishes order by name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    {
        $dishSelector .= "<option value='".$row['IDdish']."'>".$row['name']."</option>";
    }
    $dishSelector .= "</select>";
    return $dishSelector;
}

function ListProducts($db) {

    $rowStart = $GLOBALS['rowStart'];
    $rowEnd = $GLOBALS['rowEnd'];
    $newColNum = $GLOBALS['newColNum'];
    $newCol = $GLOBALS['newCol'];

    $user = $_SESSION['diet_user'];
    $meal = "";
    $c = 0;

    $output  = "<table width='100%' style='margin: auto;' cellspacing='5'>";
    $output .= "<thead><tr><th></th><th>Producte</th><th>Qty</th><th></th></tr></thead>";
    $sql =  "select * from diet_user_selection ".
            "inner join diet_products on diet_products.IDproduct = diet_user_selection.IDproduct ".
            "where IDuser = $user order by IDmeal";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {        
        if ($meal != $row['IDmeal']) {

            $sql = "select name from diet_meals where IDmeal = ".$row['IDmeal'];
            $res = mysqli_query($db, $sql);
            $desc = mysqli_fetch_array($res);
            $output .= "<tr><td colspan='4'><hr>".$desc['name'].$rowEnd;
            $meal = $row['IDmeal'];
        }
        $output .= $rowStart.BtnRemove($row['IDmeal'], $row['IDproduct']).$newCol.$row['name'].$newColNum.$row['quantity'].$rowEnd;
        $c += 1;
    }
    if ($c == 0) $output .= "<tr><td colspan='4'>No hi ha productes</td></tr>";
    $output .= "</table><br>";
    return $output;
}

//--- Content -------------------------

?>
    <div class='appPageContainer'>
        <header>
            <div class='cardCircle'>
                <div class='cardTitle'>
                Àpats
                </div>
            </div>
        </header>
        <main>

		<form action="meals_2.php" class="app-form" method="post" id='appForm'>

            <div class="app-content">
                <div class="app-select">
<?php
                    $meal = empty($_SESSION['meal']) ? "0" : $_SESSION['meal'];
                    echo MealSelector($db, $meal);
?>
                </div>
            </div>
            
<?php
            if ($selectPerImage == YES) {
                
                echo "<input type='button' class='app-button' value='Productes' onclick='goToSelector(\"mealSelect.php\")'>";
                echo "<input type='button' class='app-button' value='Plats' onclick='goToSelector(\"mealSelect_2.php\")'>";
            }
            else {
                
                echo "<div class='app-content'>";
                echo "<div class='app-select'>";
                echo ProdSelector($db);
                echo "</div>";
                echo "</div>";

                echo "<div class='app-content'>";
                echo "<div class='app-select'>";
                echo DishSelector($db);
                echo "</div>";
                echo "</div>";

                echo "<button type='submit' class='app-button'>Envia</button>";
            }
            echo DietSettings($db, "3");
?>            
        </form>
        <div class='app-card'>
<?php 
            echo ListProducts($db, $meal)
?>
            <br>
            <button type="button" class="app-button" onclick="location.href='menu.php'">Menú</button>
        </div>
    </main>
        <?php include "./includes/app.footer.inc.php"; ?>
	</div>
<?php

// --- end content -------------------
?>
<script type="text/javascript" src="../js/switch.js"></script>
<script type="text/javascript">

<?php if ($selectPerImage == NO) { 

    // this only if selectors are created
?>

    const prd = document.getElementById('dtPrd');
    const dsh = document.getElementById('dtDsh');

    prd.value = null;
    dsh.value = null;

<?php } ?>
</script>
