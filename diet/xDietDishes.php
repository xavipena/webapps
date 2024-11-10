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
        main {
            background-repeat: no-repeat;
            background: linear-gradient(to left, transparent , #1f1f1f 40%), url("./images/pexels-pedrofurtadoo-28992225.jpg");
            background-size: cover;
        }
        .redSubtitle {
            color: red; 
        }
    </style>
    <script>
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
                        innerDiv.innerHTML = "Ració o unitat és de " + value +"g";
                        document.forms["dietDay"]["dtCals"].value += value;
                        break; // first one only
                    }
                })
                .catch ((error) => console.error("FETCH ERROR getIngredients:", error));
        }
    </script>
</head>
<body>
<?php 
    //--- settings --------------------- 

    if (!empty($clean['mealSelect_x'])) $_SESSION['meal'] = $clean['mealSelect_x'];  
    if (!empty($clean['mealSelect_y'])) $_SESSION['meal'] = $clean['mealSelect_y'];  
    if (!empty($clean['mealSelect_z'])) $_SESSION['meal'] = $clean['mealSelect_z'];  
    if (!empty($clean['mealSelect_v'])) $_SESSION['meal'] = $clean['mealSelect_v'];  

    $_SESSION['pageID'] = PAGE_DIET_MENU;    
    $sourceTable = "diet_dishes";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 

    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";

    //--- new content -------------------- 

    $date = empty($clean['dt']) ? "" : $clean['dt'];
    //echo "<div class='dietPageContainer'>";
    include "./includes/menu.inc.php"; 
    $menuType = MENU_DIET;
    $submenuType = SUBMENU_DISHES;
    include "./includes/dietMenu.inc.php";

    $where = "";
    if (empty($clean['type'])) 
    {
        if (!empty($_SESSION['meal']))
        {        
            switch ($_SESSION['meal']) 
            {
                case 1:
                case 2:
                case 4:
                    $clean['type'] = "A";
                    break;
                case 3:
                case 5:
                    $clean['type'] = "B";
                    break;
            }
        }
    }
    if (!empty($clean['type']))
    {
        switch($clean['type'])
        {
            case "A":
                $where = " where IDmeal in (1, 2, 4)";
                break;
            case "B":
                $where = " where IDmeal in (3, 5)";
                break;
            case "C":
                $where = "";
                break;
            case "D":
                $where = " where IDcat = 1";
                break;
            case "E":
                $where = " where IDcat = 2";
                break;
        }
    }
    echo "<div class='container'>";
    $c = 0;
    $sql = "select * from diet_dishes".$where." order by name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) {

        $sql = "select count(*) from diet_dish_products where IDdish = ".$row['IDdish'];
        $res = mysqli_query($db, $sql);
        $cnt = mysqli_fetch_array($res)[0];
        AddMealCard("xDietDishes_1.php?dish=".$row['IDdish'], "meal", $row['IDdish'], $row['name'], $row['description'], $cnt);
        $c += 1;
    }
    echo "</div>";
    echo "<br>".$c." plats";

    include './includes/googleFooter.inc.php';
?>