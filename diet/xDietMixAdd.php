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
<script>
    function goSelectionBack()
    {
        let form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", "xDietMix_4.php");
        
        let j = 1;
        const check_names = document.getElementsByName("checks");
        for (let i = 0; i < check_names.length; i++) {
            
            if (check_names[i].checked) {
                
                let inputElement = document.createElement("input");
                inputElement.value = check_names[i].value;
                inputElement.name = "prod_" + j; j++;
                form.appendChild(inputElement);
                inputElement.type = "hidden";
            }
        }
        document.getElementsByTagName("body")[0].appendChild(form);
        form.submit();
    }
</script>
</head>
<body>
<?php 
    //--- Params ----------------------- 
    
    $cat = empty($clean['cat']) ? 0 : $clean['cat'];
    // Set cat for use in dietSelecion
    $_SESSION['cat'] = $cat;
    
    //--- settings --------------------- 

    $_SESSION['pageID'] = PAGE_DIET_MENU;
    $sourceTable = "diet_product_mix";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 

    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";

    //--- new content -------------------- 

    //echo "<div class='dietPageContainer'>";
    include "./includes/menu.inc.php"; 

    $sql = "select name from diet_mixes where IDmixcat = $cat";
    $result = mysqli_query($db, $sql);
    $catname = mysqli_fetch_array($result)[0]; 
    echo "<h2>$catname</h2>";

    if (!empty($_SESSION['meal'])) {

        $sql = "select name from diet_meals where IDmeal = ".$_SESSION['meal'];
        $result = mysqli_query($db, $sql);
        $mealname = mysqli_fetch_array($result)[0]; 
        echo "<h3>Per $mealname</h3>";
    }
    else echo "<h3>---</h3>";

    echo "<div class='container'>";
    $c = 0;

    switch ($cat) {

        case 1;
            $sql = "select * from diet_product_mix where IDmixcat = $cat";
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($result)) {
        
                AddMealCard("xDietMix_1.php?cat=$cat&mix=".$row['IDmix'], "mix", $row['IDmix'], $row['name'], $row['description'], 1);
                $c += 1;
            }
            break;
        
        case 2:

            $sql = "select * from diet_products where status ='A' and pick = 1";
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($result)) {
        
                AddSelectCard("diet", $row['IDproduct'], $row['name'], $row['description']);
                $c += 1;
            }
            break;
    }
    //AddCard("diet", 0, "Torna", "Res més");
    echo "</div>";
    
    switch ($cat) {

        case 1;
            echo "<br>".$c." components";
           break;

        case 2:
            echo "<br>".$c." productes<br>";
            echo "<input type='button' value='Actualitza' onclick='goSelectionBack()'>";
            break;
    }

    $page = "";
    include './includes/googleFooter.inc.php';
?>