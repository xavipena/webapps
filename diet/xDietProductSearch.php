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
    include "./includes/background.inc.php";

    $server = "https://diaridigital.net";
    $search = $clean['searchBox'];
    $searchSource = $clean['searchID'];
?>
    <script>
        function GetDetails(i, cat) 
        {
            isSolid = cat == "1";
            const existsDiv = document.getElementById("prod_" + i);
            if (existsDiv.innerHTML != "") 
            {
                existsDiv.innerHTML = "";
                return;
            };
           
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
            const colStyling = " class='number' width='15%'"/*" width='15%' style='align:right'";*/

            const columnSTD = jsdata.product[0];
            const column100 = jsdata.product[1];

            const cocktailDiv = document.getElementById("prodIN");
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
                let unit = "g";
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
                p += "<td" + colStyling + ">" + value + " " + unit + "</td></tr>";

                c += 1;
            }
            tbody.insertAdjacentHTML("beforeend", p);

        }

    </script>
</head>
<body>
<?php 
    $_SESSION['pageID'] = PAGE_DIET_MENU;
    $sourceTable = "diet_products";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";
    
    //--- new content -------------------- 
    
    //echo "<div class='dietPageContainer'>";
    include "./includes/menu.inc.php"; 
    include "./includes/productsMenu.inc.php";
    include "./includes/settingsEnd.inc.php";
        
    if ($searchSource == "txt")
    {
        echo "<table class='dietcard'>";
        echo "<thead>".
             "<th>ID</th>".
             "<th>Nom</th>".
             "<th></th>".
             "<th>Descripció</th>".
             "</thead>";
    }
    else
    {
        echo "<table>";
    }
    $prefix = "diet";
    $c = 0;
    $sql ="select * from diet_products where status = 'A' and (name like '%$search%' or description like '%$search%') order by type, name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        $params = "?prd=".$row['IDproduct'];

        $isSolid = $row['food'] == "Sòlid" ? "1" : "0";
        switch ($searchSource)
        {
            case "prd":
            case "lst":
                echo "<tr>";
                echo "<td width='20%'><img class='product' src='".GetImage($prefix, $row['IDproduct'])."'></td>"; 
                echo "<td><br>ID: ".$row['IDproduct']."<br>".
                         "<a href='javascript:GetDetails(".$row['IDproduct'].", $isSolid)'>".$row['name']."</a><br>".$row['short'];
                if (isset($_SESSION['diet_user'])) 
                {
                    if ($_SESSION['diet_user'] != "") 
                    {
                        echo "<br><br><a href='xDietSelectionAdd.php$params'>Selecciona</a>";
                    }
                }
                echo "<br><a href='xDietProductFull.php?prd=".$row['IDproduct']."'>Fitxa completa</a>";
                echo "<div id='prod_".$row['IDproduct']."'></div>";
                echo $rowEnd;
                break;

            case "box":

                if (!empty($_SESSION['menu']) && $_SESSION['menu'] == MENU_COMPARE) {

                    echo PrintBoxForSelection($row);
                }
                else {
        
                    echo PrintBoxProduct($row);
                }
                break;

            case "txt":
                echo "<tr><td class='number'>";
                echo $row['IDproduct']."&nbsp;$newCol<a href='xDietProductFull.php$params'>".$row['name']."</a>";
                if (isset($_SESSION['diet_user'])) 
                {
                    if ($_SESSION['diet_user'] != "") 
                    {
                        echo "$newCol<a href='xDietSelectionAdd.php$params'>Selecciona</a>";
                    }
                }
                echo $newCol.$row['short'];
                echo $rowEnd;
                break;
        }
        $c += 1;
    }
    echo "</table>";
    if ($c > 0) echo $c." productes";
    else echo "No es troba cap producte per '$search'";

    echo "<div id='prodIN' class='details'>Clica el nom per a veure la informació nutricional</div></td>";

    include './includes/googleFooter.inc.php';
?>