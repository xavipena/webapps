<?php
    session_start();
    $runner_id = "";
    $page = "diet";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    include './includes/dbConnect.inc.php';
    include './includes/googleHeader.inc.php';
    include "./includes/settingsStart.inc.php";
    include './includes/googleSecurity.inc.php';
    include "./includes/sideMenuHover_1.inc.php";

    $server = "https://diaridigital.net";
?>
    <style>
        main {
            background-repeat: no-repeat;
            background: linear-gradient(to left, transparent , #1f1f1f 40%), url("./images/pexels-pedrofurtadoo-28992225.jpg");
            background-size: cover;
        }
        fieldset {
            border-radius: 5px;
            width: 650px;
            max-width: 650px; /* override diet.css */
            border-color: #666;
        }
    </style>
    <script>
        function validateForm() {

            var err = false;

            if (document.forms["dietDay"]["dtMeal"].value == "") err = true;
            else if (document.forms["dietDay"]["dtProd"].value == "") err = true;
            else if (document.forms["dietDay"]["dtQty"].value == 0) err = true;

            if (err) alert("Cal omplir totes les caselles!");

            return err ? false : true;
        }

        function getProductDetails() 
        {
            const prodID = document.getElementById("dtProd").value;
            if (prodID == "") return;

            let url = "<?php echo $server?>/diet/xDiet.ws.php?action=getProd&prod=" + prodID;

            fetch (url) 
                .then ((response) => {
                    if (response.ok) 
                    {
                        return response.json();
                    } 
                    else 
                    {
                        throw new Error("NETWORK RESPONSE ERROR");
                    }
                })
                .then (data => 
                {
                    const getValues = Object.values(data.product[1]);
                    for (let key in Object.keys(data.product[1])) 
                    {
                        let value = getValues[key];
                        const innerDiv = document.getElementById("prInfo");
                        innerDiv.innerHTML = "Ració o unitat és de " + value +"g";
                        break; // first one only
                    }
                })
                .catch ((error) => console.error("FETCH ERROR getProductDetails:", error));
        }

        function GetDetails(i, cat) 
        {
            isSolid = cat == "1";
            const existsDiv = document.getElementById("pData");
           
            let url = "<?php echo $server?>/diet/xDiet.ws.php?action=getProd&prod=" + i;
            fetch (url) 
                .then ((response) => {
                    if (response.ok) 
                    {
                        return response.json();
                    } 
                    else 
                    {
                        throw new Error("NETWORK RESPONSE ERROR");
                    }
                })
                .then (data => {
                    displayDetails(data, i, isSolid)
                })
                .catch ((error) => console.error("FETCH ERROR getProd:", error));
        }

        function displayDetails(jsdata, i, isSolid) 
        {
            const headStyling = " class='dietcard' width=100%";
            const colStyling = " class='number' width='15%'"

            const columnSTD = jsdata.product[0];
            const column100 = jsdata.product[1];

            const cocktailDiv = document.getElementById("pData");
            cocktailDiv.innerHTML = "";
            cocktailDiv.innerHTML = "<table" + headStyling + "><thead><tr><th>key</th><th>per 100g</th><th>per ració</th></tr></thead><tbody id='table_" + i + "'></tbody></table>";

            const tbody = document.getElementById("table_" + i);
            tbody.innerHTML = "";
            var p = "";

            const getKIngredientsSTD = Object.keys(columnSTD);
            const getVIngredientsSTD = Object.values(columnSTD);

            const getVIngredients100 = Object.values(column100);

            var c = 1;
            for (let key in getKIngredientsSTD) 
            {
                unit = "g";
                switch (c)
                {
                    case 1:
                        unit = isSolid ? "g" : "ml";
                        break;
                    case 2:
                        unit = "Kcal";
                }
                let value = getKIngredientsSTD[key];
                p += "<tr><td width='15%'>" + value + "</td>";
                value = getVIngredientsSTD[key];
                p += "<td" + colStyling + ">" + value + " " + unit + "</td>";
                value = getVIngredients100[key];
                p += "<td" + colStyling + ">" + value + " " + unit + "</td>";
                p += "</tr>";

                c += 1;
            }
            tbody.insertAdjacentHTML("beforeend", p);
        }
    </script>
</head>
<body>
<?php 
    $_SESSION['pageID'] = PAGE_DIET_MENU;    
    $sourceTable = "diet_dish_products";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

//--- new content -------------------- 
    
//echo "<div class='dietPageContainer'>";
include "./includes/menu.inc.php"; 
$menuType = MENU_PRODUCT;
include "./includes/dietMenu.inc.php";

$dish  = 0;
$dID   = "0";
$dname = "";
if (!empty($clean['dish'])) 
{
    $dish = $clean['dish'];
    $sql = "select IDdish, IDmeal, name from diet_dishes where IDdish = ".$dish;
    $result = mysqli_query($db, $sql);
    if ($row = mysqli_fetch_array($result)) {
    
        $dID   = $row['IDdish'];
        $dmeal = $row['IDmeal'];
        $dname = $row['name'];
    }
}
else
{
    $sql = "select IDdish from diet_dishes order by IDdish desc limit 1";
    $result = mysqli_query($db, $sql);
    $dID = mysqli_fetch_array($result)[0];
    $dID += 1;
}

if (!empty($_SESSION['meal']))
{
    $dmeal = $_SESSION['meal'];
}
?>

<form id="dietDay" method="post" action="xDietDishes_2.php" onsubmit="return validateForm()">

    <h2>Plats</h2>
    <fieldset>
        <legend>Plat</legend>
        <table>
            <tr><td>ID</td>
            <td><input type="number" value="<?php echo $dID?>" size=6 id="dtID" name="dtID" readonly></td></tr>
            <tr><td><label for="dtDish">Nom del plat</label></td>
            <td><input type="text" value="<?php echo $dname?>" id="dtDish" name="dtDish" size="50"></td></tr>
            <tr><td><label for="dtDesc">Descripció</label></td>
            <td><input type="text" value="<?php echo $dname?>" id="dtDesc" name="dtDesc" size="50"></td></tr>
            <tr><td><label for="dtMeal">Àpat</label></td>
            <td><select id="dtMeal" name="dtMeal">
                <option value="">Selecciona àpat</option>
                <?php 
        $sql = "select * from diet_meals";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result)) {
            
            $selected = $row['IDmeal'] == $dmeal ? " selected" : "";
            echo "<option value='".$row['IDmeal']."'".$selected.">".$row['name']."</option>";
        }
        ?>
            </select></td></tr>
        </table>
    </fieldset>
    <fieldset>
        <legend>Ingredients</legend>
        <table>
        <tr><td><label for="dtProd">Producte</label></td>
            <td><select id="dtProd" name="dtProd" onchange="javascript:getProductDetails()">
                <option value="">Selecciona producte</option>
<?php 
            $sql = "select * from diet_products where status = 'A' order by name";
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($result)) {

                echo "<option value='".$row['IDproduct']."'>".$row['name']."</option>";
            }
?>
            </select></td></tr>
        <tr><td><label for="dtQty">Quantitat</label></td>
                <td><input type="number" value="0" size=6 id="dtQty" name="dtQty"> (en racions o unitats)</td></tr>
        </table>
    </fieldset>
    <br>
    <input type="submit" value="Afegeix al plat">
    <input type="button" value="Crea un producte" onclick="location.href='xDietProduct_1.php'">
    <input type="button" value="Ja està" onclick="location.href='xDietDishes.php'">
</form>

<br>
<div id="prInfo">...</div>
<br>

<?php
if ($dish > 0) 
{
    echo "<table class='dietcard'>";
    echo "<caption>Composició del plat</caption>";
    echo "<thead><tr><th>Treu</th>".
                "<th>Producte</th>".
                "<th>Calories</th>".
                "<th>Greixos</th>".
                "<th>Saturats</th>".
                "<th>Sucre</th>".
                "<th>Sal</th>".
                "</tr></thead>";

/*  test:
    select dd.quantity qty, 
            dp.name pname, 
            dpd.energy energy, dpd.fat fat, dpd.saturates saturates, dpd.sugar sugar, dpd.salt salt 
    from diet_dish_products dd 
        join diet_products dp on dp.IDproduct = dd.IDproduct
        join diet_product_data dpd on dpd.IDproduct = dd.IDproduct and unit = 'Ration'
        where dd.IDdish = 4
*/
$pvalue = array(0.0,0.0,0.0,0.0,0.0);
$ptotal = array(0.0,0.0,0.0,0.0,0.0);

    $sql =  "select dd.Idproduct prod, dd.quantity qty, dp.food food, dp.name pname ".
            "from diet_dish_products dd ".
            "join diet_products dp on dp.IDproduct = dd.IDproduct ".
            "where dd.IDdish = ".$dish;
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {

        $insql =  "select dpd.energy energy, dpd.fat fat, dpd.saturates saturates, dpd.sugar sugar, dpd.salt salt ". 
                  "from diet_product_data dpd ".
                  "where dpd.IDproduct = ".$row['prod']." and dpd.unit = 'Ration'";
        $inresult = mysqli_query($db, $insql);
        while ($inrow = mysqli_fetch_array($inresult)) {
            
            $pvalue[0] = round($row['qty'] * $inrow['energy'], 2);
            $pvalue[1] = round($row['qty'] * $inrow['fat'], 2);
            $pvalue[2] = round($row['qty'] * $inrow['saturates'], 2);
            $pvalue[3] = round($row['qty'] * $inrow['sugar'], 2);
            $pvalue[4] = round($row['qty'] * $inrow['salt'], 2);
            
            $isSolid = $row['food'] == "Sòlid" ? "1" : "0";
            echo $rowStart."<a href='xDietDishes_3.php?dsh=$dID&prd=".$row['prod']."'>Treu</a>".$newCol."<a href='javascript:GetDetails(".$row['prod'].", $isSolid)'>".$row['pname']."</a></td>";
            for ($c = 0; $c < 5; $c++) {

                printf("<td class='number'>%.2f</td>",$pvalue[$c]);
                $ptotal[$c] += $pvalue[$c];
            }
        }
    }
       
    echo "<tfoot>";
    echo "<tr>";
    echo "    <td></td>";
    echo "    <td></td>";
    for ($c = 0; $c < 5; $c++) {

        echo "<td class='number'>".$ptotal[$c]."</td>";
    }
    echo "</tr>";
    echo "</tfoot>";
    
    echo "</table>";
    echo "<div id='pData' class='details'>Informació nutricional</div>";
}
if (!empty($_SESSION['diet_user']) && !(empty($_SESSION['meal'])))
{
?>
<br>
<input type="button" value="Afegeix a la meva selecció" onclick="location.href='xDietSelection_3.php?dish=<?php echo $dID?>'">
<input type="button" value="Selecciona un altre" onclick="location.href='xDietDishes.php'">
<?php
}
include './includes/googleFooter.inc.php';
?>