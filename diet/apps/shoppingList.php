<?php
    session_start();
    include ".././includes/dbConnect.inc.php";
    include "./includes/app.security.inc.php";
    include "./includes/app.header.inc.php";
?>
</head>
<body>
<?php 

//--- Settings ------------------------

//--- new content ---------------------

//--- functions -----------------------

function BtnRemove($id) {

    $output = "<input type='button' value='&nbsp;Treu&nbsp;' onclick='location.href=\"shoppingList_1.php?prd=$id\"'>";
    return $output;
}

function BtnDone($id) {

    $output = "<input type='button' value='&nbsp;Tinc&nbsp;' onclick='location.href=\"shoppingList_2.php?prd=$id\"'>";
    return $output;
}

function ListProds($db)
{
    $rowStart = $GLOBALS['rowStart'];
    $rowEnd = $GLOBALS['rowEnd'];
    $newColNum = $GLOBALS['newColNum'];
    $newCol = $GLOBALS['newCol'];

    $output  = "<table width='100%' style='margin: auto;' cellspacing='8'>";
    $output .= "<thead><tr><th></th><th>Producte</th><th>Qty</th><th></th></tr></thead>";
    $output .= "<tr><td colspan='4'><hr>".$rowEnd;
    $sql =  "select * from diet_product_list ".
            "inner join diet_products on diet_product_list.IDproduct = diet_products.IDproduct ".
            "where done = 0";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {        
        $output .= $rowStart.BtnRemove($row['IDproduct']).$newCol.$row['name'].$newColNum.$row['quantity'].$newCol.BtnDone($row['IDproduct']).$rowEnd;
    }
    $output .= "</table><br>";
    return $output;
}

//--- Content -------------------------

?>
    <div class='appPageContainer'>
        <header>
            <div class='cardCircle'>
                <div class='cardTitle'>
                Compra
                </div>
            </div>
        </header>
        <main>
            <div class='app-card'>
                <?php echo ListProds($db)?>
                <button type="button" class="app-button" onclick="location.href='shopping.php'">Compra</button>
                <button type="button" class="app-button" onclick="location.href='menu.php'">Menú</button>
            </div>
        </main>
<?php 
    include "./includes/app.footer.inc.php"; 

// --- end content -------------------
?>

