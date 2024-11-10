<?php
/* 
    list functions
    ProductStock($db)
    $db     -->     database handler
*/
function ProductStock($db) {

    $rowEnd     = $GLOBALS['rowEnd'];
    $newCol     = $GLOBALS['newCol'];
    $newColNum  = $GLOBALS['newColNum'];
?>
    <table class="dietcard">
        <thead>
            <caption>Producte stock</caption>
            <tr>
                <th>Codi</th>
                <th>Producte</th>
                <th>Quantitat</th>
            </tr>
        </thead>
<?php
    $sql =  "select dps.IDproduct id, dps.quantity qty, dp.name name ".
            "from diet_product_stock dps ".
            "join diet_products dp on dp.IDproduct = dps.IDproduct ".
            "order by name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    {
        echo "<tr><td class='number'>".$row['id'].$newCol.$row['name'].$newColNum.$row['qty'];
        echo $rowEnd;
    }
    echo "</table>";
}

/* 
    list functions
    ProductStock($db)
    $db     -->     database handler
    $done   -->     pending or bought
*/
function ShoppingList($db, $done) {

    $rowEnd     = $GLOBALS['rowEnd'];
    $newCol     = $GLOBALS['newCol'];
    $newColNum  = $GLOBALS['newColNum'];

    $title = $done == 0 ? "Llista" : "Compra feta";
?>
    <table class="dietcard">
        <thead>
            <caption><?php echo $title?></caption>
            <tr>
                <th>Codi</th>
                <th>Producte</th>
                <th>Quantitat</th>
                <th>+</th>
                <th>-</th>
            </tr>
        </thead>
<?php
    $sql =  "select dpl.IDproduct id, dpl.quantity qty, dp.name name ".
            "from diet_product_list dpl ".
            "join diet_products dp on dp.IDproduct = dpl.IDproduct ".
            "where dpl.done = $done ".
            "order by name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    {
        echo "<tr><td class='number'>".$row['id'].$newCol.$row['name'].$newColNum.$row['qty'];
        echo $newCol."[<a href='xDietShoppingQty.php?prd=".$row['id']."&act=add'>+</a>]".
            $newCol."[<a href='xDietShoppingQty.php?prd=".$row['id']."&act=sub'>-</a>]".
            $rowEnd;
    }
    echo "</table>";
}