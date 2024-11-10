<?php
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $page = "diet";
    include './includes/dbConnect.inc.php';
    include './includes/googleHeader.inc.php';
    include './includes/googleSecurity.inc.php';
    include "./includes/settingsStart.inc.php";
    include "./includes/sideMenuHover_1.inc.php";
?>
    <style>
        #dtList {
            position:relative; 
            top:-200px;
            left:400px;
            width:300px
        }
    </style>
    <script>
        function validateForm() {

            var err = false;

            if (document.forms["dietDay"]["dtMeal"].value == "") err = true;
            else if (document.forms["dietDay"]["dtProd"].value == "" && document.forms["dietDay"]["dtDish"].value == "") err = true;
            else if (document.forms["dietDay"]["dtQty"].value == 0) err = true;

            if (err) alert("Cal omplir totes les caselles!");

            return err ? false : true;
        }

        function getProductDetails() 
        {
            const prodID = document.getElementById("dtProd").value;
            if (prodID == "") return;

            url = "https://diaridigital.net/diet/xDiet.ws.php?action=getProd&prod=" + prodID;

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
                        innerDiv.innerHTML = "Ració o unitat és de " + value +" Kcal";
                        document.forms["dietDay"]["dtCals"].value = value;
                        break; // first one only
                    }
                })
                .catch ((error) => console.error("FETCH ERROR getIngredients:", error));
        }
    </script>
</head>
<body>
<?php 
    $user = "0";
    $period = 0;
    if (isset($_SESSION['diet_user'])) {

        if ($_SESSION['diet_user'] != "") {
            
            $user = $_SESSION['diet_user'];
            $period = $_SESSION['diet_period'];
        }
    }
    $_SESSION['pageID'] = PAGE_DIET_MENU;    
    $page ="Diet";
    $sourceTable = "diet_user_meals";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    
    //--- new content -------------------- 
    
    $date = empty($clean['dt']) ? "" : $clean['dt'];
    $meal = empty($clean['ml']) ? "" : $clean['ml'];
    //echo "<div class='dietPageContainer'>";
    include "./includes/menu.inc.php"; 
    $menuType = MENU_SELECTION;
    include "./includes/dietMenu.inc.php";

if (empty($_SESSION['diet_user'])) {

    echo "No s'ha assignat cap usuari";
    include './includes/googleFooter.inc.php';
    exit;
}
?>

<form id="dietDay" method="post" action="xDietDay_2.php" onsubmit="return validateForm()">

    <input type="hidden" id="dtUser" name="dtUser" value="1">
    <input type="hidden" id="dtCals" name="dtCals" value="0">

    <h2>Dieta diària</h2>
    <table>
    <tr><td>Dia</td>
            <td><input type="date" value="<?php echo $date ?>" id="dtDate" name="dtDate"></td></tr>
    <tr><td>Àpat</td>
        <td><select id="dtMeal" name="dtMeal" onchange="javascript:getProductDetails()">
            <option value="">Selecciona àpat</option>
<?php 
        $sql = "select * from diet_meals";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result)) {

            $selected = $meal == $row['IDmeal'] ? " selected" : "";
            echo "<option value='".$row['IDmeal']."'".$selected.">".$row['name']."</option>";
        }
?>
        </select></td></tr>
    <tr><td>Producte</td>
        <td><select id="dtProd" name="dtProd" onchange="javascript:getProductDetails()">
            <option value="">Selecciona producte</option>
<?php 
        $sql = "select * from diet_products order by name";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result)) {

            echo "<option value='".$row['IDproduct']."'>".$row['name']."</option>";
        }
?>
        </select></td></tr>
    <tr><td>Quantitat</td>
                <td><input type="number" value="0" size=6 id="dtQty" name="dtQty"> (en racions o unitats)</td></tr>
    <tr><td>o plat</td>
        <td><select id="dtDish" name="dtDish">
            <option value="">Selecciona plat</option>
<?php 
        $sql = "select * from diet_dishes order by name";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result)) {

            echo "<option value='".$row['IDdish']."'>".$row['name']."</option>";
        }
?>
        </select></td></tr>
    </table>
    <br>
    <input type="submit" value="Afegeix">
    <input type="button" value="Ja està" onclick="javascript:history.back();">
</form>

<div id="prInfo"></div>

<div id="dtList">
<?php 
    function printHeader() {
        
        echo "<table width='300px'><thead><tr><th>Producte</th><th>Qtat</th><th>Kcal</th></tr></thead>";
    }
    
    $aux = "";
    $tot = 0;
    $sql =  "select um.IDmeal id, m.name mname, p.name pname, um.quantity qty, um.calories cal from diet_user_meals um ".
            "join diet_meals m on m.IDmeal = um.IDmeal ".
            "join diet_products p on p.IDproduct = um.IDproduct ";
    if ($date != "")
    {
        $sql .= "where um.IDuser = $user and IDperiod = $period and date = '".$date."' ";
    }
    $sql .= "order by um.IDmeal ";
    
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {

        if ($aux != $row['id']) {

            echo "</table><h3>".$row['mname']."</h3>";
            printHeader();
            $aux = $row['id'];
        }
        echo "<tr><td width='70%'>".$row['pname']."</td><td>".$row['qty']."</td><td>".$row['cal']."</td></tr>";
        $tot += $row['qty'] * $row['cal'];
    }
    echo "</table><br>";
    echo "Total de l'àpat ".$tot." Kcal";
?>
</div>

<?php
include './includes/googleFooter.inc.php';
