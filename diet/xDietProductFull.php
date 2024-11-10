<?php
    session_start();
    $page = "diet";
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $useCharts = "Y";
    include './includes/dbConnect.inc.php';
    include './includes/googleSecurity.inc.php';
    include './includes/googleHeader.inc.php';
    include "./includes/settingsStart.inc.php";
    include "./includes/sideMenuHover_1.inc.php";
?>
</head>
<body>
<?php 
    //--- Settings --------------------

    $_SESSION['pageID'] = PAGE_DIET_MENU;
    $sourceTable = "diet_products";
    $warning = 0.0;
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";

    function CheckToxics($content)
    {
        $toxic = array("BHA","BHT","Nitritos de sodio","Nitratos de sodio","rBGh","rBs","Azodicarbonamida","Aceite vegetal bromado","Olean","Glutamato monosódico","aspartame",
                        "Aceite de palma","aceites hidrogenados");

        $found = searchWords($content, $toxic);
        return $found ? "Té ingredients perillosos!" : "No s'han trobat";
    }

    function searchWords($string,$words)
    {
        foreach ($words as $word)
        {
            if (stristr($string, $word)) 
            {
                return true;
            }
        }
        return false;
    }

    // Using regex
    function contains($string, Array $search, $caseInsensitive = false) {
        $exp = '/'
            . implode('|', array_map('preg_quote', $search))
            . ($caseInsensitive ? '/i' : '/');
        return preg_match($exp, $string) ? true : false;
    }

/* 
Componentes 	Cantidad recomendada 	Otros nombres de este tipo de ingredientes
Total de grasas 	El producto es bajo en grasas cuando contiene menos de 3 g por cada 100 g (sólidos) y 1,5 g por cada 100 ml (líquidos) 	Grasa/aceite animal, grasa de res, mantequilla, chocolate, sólidos de la leche, coco, aceite de coco, leche, crema de leche, ghee, aceite de palma, manteca, margarina, sebo, crema agria.
Grasas saturadas 	

El producto es bajo en grasas saturadas cuando aporta 1,5 g por cada 100 g (sólidos) o 0,75 g por cada 100 ml (líquidos) y 10% de energía.
Grasas trans 	Evitar alimentos que las contengan. 	Si la etiqueta nutricional menciona que contiene "grasas parcialmente hidrogenadas" significa que posee grasas trans pero en muy poca cantidad, menos de 0,5 g por cada porción del producto.
Sodio 	Escoger preferiblemente productos que contengan menos de 400 mg de sodio. 	Glutamato monosódico, MSG, sal marina, ascorbato de sodio, bicarbonato de sodio, nitrato o nitrito de sodio, sal vegetal, extracto de levadura.
Azúcares 	Evitar productos con más de 15 g de azúcar por cada 100 g. Lo ideal son aquellos que aportan menos de 5 g por cada 100 g de producto. Son considerados como "libre de azúcar" aquellos productos que aportan menos de 0,5 g por cada 100 g o ml. 	

Dextrosa, fructosa, glucosa, syrup o jarabe de maple, miel, sacarosa, maltosa, malta, lactosa, azúcar morena, azúcar mascabada, jarabe de maíz, jarabe de maíz alto en fructosa, jugo de fruta concentrado.
Fibras 	Escoger productos con 3 g o más por porción.
Calorías 	El producto es bajo en calorías cuando tiene menos de 40 calorías por cada 100 g (sólidos) o 20 calorías por cada 100 ml (líquidos).
Colesterol 	El producto es bajo en colesterol cuando contiene 0.02 g por 100 g (sólidos) o 0.01 g por 100 ml (líquidos)
*/

    function GetExplanation($id)
    {
        $return = "";
        switch($id) {
            case 2:
                break;
            case 3:
                $return = "Un alto consumo de grasas, especialmente grasas saturadas, puede elevar el colesterol, lo que aumenta el riesgo de enfermedades del corazón.";
                break;
            case 6:
                $return = "Un alto consumo de azúcar puede causar aumento de peso y caries. También aumenta el riesgo de diabetes tipo 2 y enfermedades cardiovasculares.";
                break;
            case 7:
                $return = "Debemos consumir entre 25 y 30g de fibra al día, esta nos ayuda a regular el tránsito intestinal, modula la velocidad de absorción de nutrientes y aumenta la sensación de saciedad.";
                break;
            case 8:
                break;
            case 9:
                $return = "Un alto consumo de sal (o sodio) puede provocar un aumento de la presión arterial, lo que puede aumentar el riesgo de enfermedades cardíacas y accidentes cerebrovasculares.";
                break;
        }
        return $return;
    }

    function GetHealthy($id, $val) {

        $double = false;
        $string = "";
        $return = "";
        if ($val == 0) 
        {
            $return = "<td><img src='../images/light_off.png' width='20px'></td>";
        }
        else 
        {
            $img = "green";

            switch($id) {
                case 2:
                    if ($val < 120) $string .= "Baix en calories";
                    else if ($val <= 350) $string .= "Acceptable";
                    else if ($val > 350) {
                        $string .= "Ric en calories";
                        $img = "red";
                        $GLOBALS['warning'] += 1;
                    }
                    break;
                case 3:
                    if ($val < 5) $string .= "Baix en greixos";
                    else if ($val < 20) {
                        $string .= "Moderat";
                        $img = "yellow";
                        $GLOBALS['warning'] += 0.5;
                    }
                    else if ($val >= 20) {
                        $string .= "Alt contingut en greixos";
                        $img = "red";
                        $GLOBALS['warning'] += 1;
                    }
                    break;
                case 4:
                    if ($val <= 1.5) $string = "baix en greixos saturats";
                    break;
                case 5:
                    break;
                case 6:
                    if ($val < 2) $string .= "Baix contingut en sucre";
                    else if ($val < 10) {
                        $string .= "Moderat contingut de sucre";
                        $img = "yellow";
                        $GLOBALS['warning'] += 0.5;
                    }
                    else if ($val >= 10) {
                        $string .= "Alt contingut de sucre (producte ensucrat!)";
                        $img = "red";
                        $GLOBALS['warning'] += 1;
                    }
                    else if ($val > 20) {
                        $string .= "No deuries de menjar-ho!!";
                        $img = "red";
                        $double = true;
                        $GLOBALS['warning'] += 2;
                    }
                    break;
                case 7:
                    if ($val <= 10) {
                        $string .= "Baix en fibra";
                        $img = "yellow";
                        $GLOBALS['warning'] += 0.5;
                    }
                    else if ($val > 10) {
                        $string .= "Bé de fibra";
                    }
                    break;
                case 8:
                    if ($val < 5) $string .= "Baix en proteïnes";
                    else if ($val <= 15) $string .= "Moderat";
                    else if ($val > 15) {
                        $string .= "Alt contingut";
                    }
                    break;
                case 9:
                    if ($val <= 0.25) $string .= "Baix contingut en sal";
                    else if ($val < 1) {
                        $string .= "Moderat en sal";
                        $img = "yellow";
                        $GLOBALS['warning'] += 0.5;
                    }
                    else if ($val >= 1) {
                            $string .= "Alt contingut de sal";
                            $img = "red";
                            $GLOBALS['warning'] += 1;
                        }
                    break;
            }
            $return = "<td><img src='../images/light_".$img.".png' width='20px'>";
            if ($double) 
            {
                $return .= "<img src='../images/light_".$img.".png' width='20px'>";
            }
            $return .= $string."</td>";
        }
        return $return;
    }

    //--- new content -------------------- 

    //echo "<div class='dietPageContainer'>";
    include "./includes/menu.inc.php"; 
    $menuType = MENU_PRODUCT;
    include "./includes/dietMenu.inc.php";
    include "./includes/settingsEnd.inc.php";

    // --------------------------------
    // Product
    // --------------------------------

    $sql ="select * from diet_products where IDproduct = ".$clean['prd'];
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);

    // Later use
    $brand = $row['brand'];
    $product = $row['IDproduct'];
    $cat = $row['IDcat'];
    $IG = $row['IG'];
    $ingredients = $row['ingredients'];

    echo "<h1>".$row['name']."</h1>";
    echo "<table width='80%' cellpadding='5'>";
    echo "<tr><td rowspan='5' width='160px'><img class='productFull' src='".GetImage("diet", $clean['prd'])."' width='150px'>".$newCol.$row['description'].$rowEnd;
    echo $rowStart."ID: ".$row['IDproduct'].$rowEnd;
    echo $rowStart.$row['type'].$rowEnd; 
    echo $rowStart."Aliment ".$row['food'].$rowEnd;
    echo $rowStart.$ingredients.$rowEnd; 
    echo "</table>";
?>
    <h2>Vitamines i minerals</h2>
<?php
    echo "<table width='300%' cellpadding='5'>";
    $c = 0;
    $sql =  "select p.quantity qty, p.unit un, c.name cname from diet_product_composition p ".
            "join diet_components c on c.IDcomponent = p.IDcomponent ".
            "where p.IDproduct = ".$clean['prd'];
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        echo $rowStart.$row['cname'].$newCol.$row['qty'].$newCol.$row['un'].$rowEnd;
        $c += 1;
    }
    echo "</table>";
    if ($c == 0) echo "No s'han indicat";
?>
    <h2>Additius alimentaris</h2>
<?php

    echo "<table cellpadding='5'>";
    $c = 0;
    $tokens = explode(",", $ingredients);
    foreach ($tokens as $addtv) {
        
        $addtv = trim($addtv);
        if (substr($addtv, 0, 2) == "E-") {

            $sql =  "select * from diet_additives where IDadditive = '$addtv'";
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($result))
            {
                echo $rowStart.$row['IDadditive'].$newCol.$row['name'].$newCol.$row['description'].$newCol."<a target='_blank' href='".$row['url']."'>Detalls</a>".$rowEnd;
                $c += 1;
            }
        }
    }
    echo "</table>";
    if ($c == 0) echo "No hi ha additius";

// --------------------------------
// Info
// --------------------------------

?>
</table>

<h2>Grup d'aliments</h2>

<?php
    $sql = "select * from diet_product_categories where IDcat = $cat";
    $res = mysqli_query($db, $sql);
    if ($grp = mysqli_fetch_array($res))
    {
        echo "Grup: ".$grp['name']."<br>";
        if ($grp['recommended'] > 0)
        {
            $periode = $grp['periode'] == "s" ? " a la setmana" : " al dia";
            echo "Recomanat: ".$grp['recommended']." ".$grp['units']."$periode<br>";
        }
        echo "Detalls: ".$grp['details']."<br>";
    }
    else 
    {
        echo "No hi ha cap categoria";
    }
?>

<h2>Informació nutricional</h2>

<!--div id="main"-->
    <table class="dietcard">
        <thead>
            <tr>
                <th id="tdCol1">Element</th>
                <th id="tdCol2">per 100g/ml</th>
                <th id="tdCol3">per ració</th>
                <th id="tdCol4">Valoració</th>
            </tr>
        </thead>
<?php
    $c = 0;
    $sql ="select * from diet_product_data where IDproduct = ".$clean['prd'];
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        $c1[$c] = $row['grams'];
        $c2[$c] = $row['energy'];
        $c3[$c] = $row['fat'];
        $c4[$c] = $row['saturates'];
        $c5[$c] = $row['carbohydrate'];
        $c6[$c] = $row['sugar'];
        $c7[$c] = $row['fibre'];
        $c8[$c] = $row['protein'];
        $c9[$c] = $row['salt'];
        $c += 1;
    }
    echo "<tr><th>Grams</th><td class='number'>".$c1[0]."g</td><td class='number'>".$c1[1]."g</td>".GetHealthy(0, 0)."</tr>";
    echo "<tr><th>Energia</th><td class='number'>".$c2[0]."cal</td><td class='number'>".$c2[1]."cal</td>".GetHealthy(2, $c2[0])."</tr>";
    echo "<tr><th>Greixos</th><td class='number'>".$c3[0]."g</td><td class='number'>".$c3[1]."g</td>".GetHealthy(3, $c3[0])."</tr>";
    echo "<tr><th>\__Saturats</th><td class='number'>".$c4[0]."g</td><td class='number'>".$c4[1]."g</td>".GetHealthy(4, 0)."</tr>";
    echo "<tr><th>Carbohidrats</th><td class='number'>".$c5[0]."g</td><td class='number'>".$c5[1]."g</td>".GetHealthy(0, 0)."</tr>";
    echo "<tr><th>\__Sucres</th><td class='number'>".$c6[0]."g</td><td class='number'>".$c6[1]."g</td>".GetHealthy(6, $c6[0])."</tr>";
    echo "<tr><th>Fibra</th><td class='number'>".$c7[0]."g</td><td class='number'>".$c7[1]."g</td>".GetHealthy(7, $c7[0])."</tr>";
    echo "<tr><th>Proteïnes</th><td class='number'>".$c8[0]."g</td><td class='number'>".$c8[1]."g</td>".GetHealthy(8, $c8[0])."</tr>";
    echo "<tr><th>Sal</th><td class='number'>".$c9[0]."g</td><td class='number'>".$c9[1]."g</td>".GetHealthy(9, $c9[0])."</tr>";

    $sodi_1 = round(($c9[0] * MILIGRAMS)/ SAL_SODI);
    $sodi_2 = round(($c9[1] * MILIGRAMS) / SAL_SODI);
    echo "<tr><th>Sodi</th><td class='number'>".$sodi_1."mg</td><td class='number'>".$sodi_2."mg</td>".GetHealthy(0, 0)."</tr>";
    echo "</table>";

    // add as a new ingredient

    echo "<br>";
    echo "<fieldset>";
    echo "<legend>Accions</legend>";
    echo "<input type='button' value=' Afegeix com ingredient ' onclick='location.href=\"xDietProduct_8.php?prd=".$clean['prd']."\"'>&nbsp;";
    echo "<input type='button' value=' Modifica producte ' onclick='location.href=\"xDietProduct_3.php?dtProd=".$clean['prd']."\"'>";
    echo "</fieldset>";
    echo "<br>";

    echo "<h2>Inredientes no saludables</h2>";
    echo CheckToxics($ingredients);

    // --------------------------------
    // Notes
    // --------------------------------

    $notes = false;
    $sectionNotes = "<br>NOTES:";
    if ($warning > 1) {

        echo $sectionNotes;
        echo "<br>Atenció amb aquest producte!!";
        $notes = true;
    }
    if ($c3[0] == 0 && $c5[0] == 0 && $c8[0] == 0) 
    {
        if (!$notes) echo $sectionNotes;
        echo "<br>Aquest producte no aporta res d'aliment";
        $notes = true;
    }

    if (isset($_SESSION['diet_user'])) 
    {
        if ($_SESSION['diet_user'] != "") 
        {
            $idr = 0;
            if ($_SESSION['diet_recom'] > 0)
            {
                $idr = $_SESSION['diet_limit'] * 100 / $_SESSION['diet_recom'];
                $idr = round($idr,2);
            }
            echo "<br>IDR: ".$idr."% de la teva ingesta diària sobre ".$_SESSION['diet_limit']." Kcal";
        }
    }

    // --------------------------------
    // Energy
    // --------------------------------

    echo "<h2>Desglòs calòric</h2>";
/* 
    1g de carbohidratos proporciona 4 Kcal
    1g de proteínas proporciona 4 Kcal
    1g de grasas proporciona 9 Kcal
    1g de alcohol proporciona 7 Kcal 
    1g de fibra proporciona 2 Kcal
*/
    $per_1 = 0;
    $per_2 = 0;
    $per_3 = 0;
    $per_4 = 0;

    if ($c2[0] > 0) {

        $per_1 = round((($c3[0] * 9) * 100) / $c2[0], 2);
        $per_2 = round((($c5[0] * 4) * 100) / $c2[0], 2);
        $per_3 = round((($c8[0] * 4) * 100) / $c2[0], 2);
        $per_4 = round((($c7[0] * 2) * 100) / $c2[0], 2);
     
        echo "<table class='dietcard'>";
        echo "<tr><td>greixos</td><td class='number'>".$per_1."%</td><td class='number'>".round($c3[0] * 9, 2)."Kcal</td>".
        "<td rowspan='4'><div style='width: 300px'><canvas id='chart_1'></canvas></div></td>".
        "</tr>";
        echo "<tr><td>carbohidrats</td><td class='number'>".$per_2."%</td><td class='number'>".round($c5[0] * 4, 2)."Kcal</td></tr>";
        echo "<tr><td>proteïnes</td><td class='number'>".$per_3."%</td><td class='number'>".round($c8[0] * 4, 2)."Kcal</td></tr>";
        echo "<tr><td>fibra</td><td class='number'>".$per_4."%</td><td class='number'>".round($c7[0] * 2, 2)."Kcal</td></tr>";
        echo "</table>";
    }
    else echo "No té calories";
        
    // --------------------------------
    // Índex glucèmic
    // --------------------------------

    if ($IG >= 0)
    {
        echo "<h2>Índex glucèmic</h2>";
        echo "IG: $IG";

        if ($IG < 56) {

            $nivelIG = "Baix";
            $img = "green";
        }
        else if ($IG < 70) {

            $nivelIG = "Mitjà";
            $img = "yellow";
        }
        else {

            $nivelIG = "Alt";
            $img = "red";
        }
        echo "<br>Nivell: <img src='../images/light_".$img.".png' width='20px'> $nivelIG";

        // --------------------------------
        // Càrrega glucèmica
        // --------------------------------

        // CG = (IG x cantidad de carbohidratos disponibles por porción) dividido por 100.
        echo "<h2>Càrrega glucèmica</h2>";
        $cg = round(($IG * $c5[1]) /100, 1);
        echo "CG: $cg g";

        if ($cg < 11) {

            $nivelCG = "Baixa";
            $img = "green";
        }
        else if ($IG < 20) {

            $nivelCG = "Mitjana";
            $img = "yellow";
        }
        else {

            $nivelCG = "Alta";
            $img = "red";
        }
        echo "<br>Nivell: <img src='../images/light_".$img.".png' width='20px'> $nivelCG";
    } 

    // --------------------------------
    // Reference
    // --------------------------------

    $sql =  "select dpl.IDreference, dpr.description descr ".
            "from diet_product_links dpl ".
            "join diet_product_reference dpr ".
            "  on dpr.IDreference = dpl.IDreference ".
            "where dpl.IDproduct = ".$product;

    $result = mysqli_query($db, $sql);
    if ($row = mysqli_fetch_array($result))
    {
        echo "<h2>Referencia</h2>";
        echo $row['descr']; 
    }

    // --------------------------------
    // Brand
    // --------------------------------

    $sql = "select * from diet_product_brands where IDbrand = ".$brand;
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);
    echo "<h2>Marca: ".$row['name']."</h2>"; 
    
    if ($row['IDbrand'] == 14) echo $row['company']."<br><br>";
    else if ($row['company'] != "") echo " de la empresa ".$row['company']."<br>"; 
    
    echo $row['description'];
    if ($row['holding'] > 0) 
    {
        $sql ="select * from diet_product_brands where IDbrand = ".$row['holding'];
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($result);
        echo $row['company']."<br>".$row['description'];
    }
?>
<script>
    // helpers

    const Utils = ChartUtils.init();

    // setup
    
    const data_1 = {
    labels: ['Greixos','Carbohidrats','Proteïnes','Fibra'],
    datasets: [
            {
                label: 'Percentatge',
                data: [<?php echo $per_1.",".$per_2.",".$per_3.",",$per_4?>],
                backgroundColor: Object.values(Utils.CHART_COLORS),
            }
        ]
    };

    // config

    const config_1 = {
        type: 'pie',
        data: data_1,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                },
                title: {
                    display: true,
                    text: 'Desglòs calòric'
                }
            }
        }
    };

    // rendering

    const Chart_1 = new Chart(
        document.getElementById('chart_1'),
        config_1
    );
</script>
<?php
    include './includes/googleFooter.inc.php';
?>