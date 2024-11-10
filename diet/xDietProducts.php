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
    include "./includes/dietFunctions.inc.php";
    include "./includes/background.inc.php";
    include "./includes/loader.inc.php";

    $type = empty($clean['type']) ? "" : $clean['type'];

    $server = "https://diaridigital.net";
    $infoText = "Informació nutricional";
?>
    <script>
        function FS_searchProduct(element) {

            var frm = document.forms["searchf"].getElementsByTagName("input");
            var srch = frm[1].value;
            if (srch == "") return;
            if (element != "") {
            
                console.log("element: " + element);
                document.getElementById(element).style.display = "flex";
            }
            location.href = 'xDietAPI_3.php?srch=' + srch;
        }

        function GetDetails(i, cat) 
        {
            isSolid = cat == "1";
            const existsDiv = document.getElementById("prod_" + i);
            if (existsDiv.innerHTML != "<?php echo $infoText?>") 
            {
                existsDiv.innerHTML = "<?php echo $infoText?>";
                return;
            };

            /* sample:
            fetch('http://example.com/movies.json')
                .then(response => response.json())
                .then(data => console.log(data));
            */
           
            let url = "<?php echo $server?>/diet/xDiet.ws.php?action=getProd&prod=" + i;

            //document.getElementById("deb").innerHTML = url;

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

            const cocktailDiv = document.getElementById("prod_" + i);
            cocktailDiv.innerHTML = "";
            cocktailDiv.innerHTML = "<table" + headStyling + "><thead><tr><th>key</th><th>per 100g</th><th>per ració</th></tr></thead><tbody id='table_" + i + "'></tbody></table>";

            const tbody = document.getElementById("table_" + i);
            tbody.innerHTML = "";
            var p = "";

            const getKIngredientsSTD = Object.keys(columnSTD);
            const getVIngredientsSTD = Object.values(columnSTD);

            const getVIngredients100 = Object.values(column100);

            let c = 1;
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
                p += "<td" + colStyling + ">" + value + " " + unit + "</td>";
                if (c == 1) 
                {
                    // room for ingredients and image
                    p += "<td rowspan='9' valign='top'><div id='col_" + i + "'></div></td>";
                }
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
    $sourceTable = "diet_products";
    $searchSource = "prd";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- new content -------------------- 
    
    include "./includes/menu.inc.php"; 
    $submenuType = SUBMENU_PRODUCTS;
    include "./includes/productsMenu.inc.php";
    include "./includes/settingsEnd.inc.php";
            
    if ($type == "") {

        include './includes/googleFooter.inc.php';
        exit;
    }

    $col = 1;
    $cols = 3;
    $colper = round(100 / $cols);
    $openTable = "<table width='100%' cellpadding='5'>";
    $closeTable = "</table>";

    echo "<table width='100%'><tr><td width='$colper%' class='col$col'>"; // for 3 columns
    echo $openTable;
    $c = 1;
    $aux = "";
    $aux2 = "";

    // -------------------------------
    // actions
    // -------------------------------
    switch ($type) {
        
        case "9":
            // no action
            $cnt = 0;   
            break;

        case "8":
            $sql = "select IDproduct from diet_products where status = 'A' order by IDproduct desc limit 1";
            $res = mysqli_query($db, $sql);
            $cnt = mysqli_fetch_array($res)[0] - 9; // last 10 products by ID
            break;
    }

    // -------------------------------
    // listing
    // -------------------------------
    $sql = "select * from diet_products where status = 'A' order by type, name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        if ($row['IDproduct'] < $cnt) continue;

        $i = $row['IDproduct'];
        if ($aux != $row['type']) 
        {
            if ($aux != "") 
            {
                $col += 1;
                echo $closeTable;
                echo "</td><td width='$colper%' class='col$col'>";
                echo $openTable;
            }
            $aux = $row['type'];
        }
        if ($aux2 != $row['type']) {

            $innerLink = "xDietGlossary_1.php";
            switch($col)
            {
                case 1:
                    $innerLink .= "?term=13";
                    break;
                case 2:
                    $innerLink .= "?term=14";
                    break;
                case 3:
                    $innerLink .= "?term=15";
                    break;
            }
            echo "<tr><td colspan='2'><a href='$innerLink'><h2>".$row['type']."</h2></a></td></tr>";
            $aux2 = $row['type'];
        }

        $isSolid = $row['food'] == "Sòlid" ? "1" : "0";
        echo "<tr>";
        echo "<td width='20%'><img class='product' src='".GetImage("diet", $row['IDproduct'])."'></td>"; 
        echo "<td><a href='javascript:GetDetails(".$row['IDproduct'].", $isSolid)'>".$row['name']."</a><br>"; 
        echo $row['short']."<br>"; 
        echo "<a href='xDietProductFull.php?prd=".$row['IDproduct']."'>Fitxa completa</a>";
        echo "<div id='prod_$i' class='details-inline'>Informació nutricional</div></td>";
        echo "</tr>";
        $c += 1;
    }
    echo $closeTable;

    echo "</td><tr><td colspan='3'>".$c." productes</td></tr></table>";

    include './includes/googleFooter.inc.php';
?>