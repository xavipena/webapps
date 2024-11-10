<?php
    session_start();
    include "../includes/dbConnect.inc.php";
    include "../includes/app.security.inc.php";
    include "../includes/app.header.inc.php";
?>
</head>
<body>
<?php 

//--- Settings ------------------------

//--- new content ---------------------

//--- functions -----------------------

function ProdSelector($db)
{
    $prodSelector = "<select onchange='' id='dtPrd' name='dtPrd' data-placeholder='Producte'>";
    $sql ="select * from diet_products where status = 'A' order by name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    {
        $prodSelector .= "<option value='".$row['IDproduct']."'>".$row['name']."</option>";
    }
    $prodSelector .= "</select>";
    return $prodSelector;
}

function CountProds($db)
{
    $sql ="select count(*) as cnt from diet_product_list where done = 0";
    $result = mysqli_query($db, $sql);
    return mysqli_fetch_array($result)[0]." productes per comprar<br><br>";
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
            <form action="shopping_1.php" class="app-form" method="post">

                <div class="app-content">

                    <div class="app-select">
                    <?php
                        $meal = empty($_SESSION['meal']) ? "x" : $_SESSION['meal'];
                        echo ProdSelector($db, $meal);
                    ?>
                    </div>

                </div>
                <div class="app-content">

                    <div class="app-box">
                        <i class="ri-price-tag-3-line app-icon"></i>

                        <div class="app-box-input">
                            <input type="number" step="any" required class="app-input" value="1" placeholder="" id="dtQty" name="dtQty">
                            <label for="" class="app-label">Quantitat</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="app-button">Desa</button>
                <button type="button" class="app-button" onclick="location.href='menu.php'">Menú</button>
            </form>
            <div class='app-card'>
                <?php echo CountProds($db)?>
                <button type="button" class="app-button" onclick="location.href='shoppingList.php'">llista</button>
            </div>
        </main>

<?php 
    include "../includes/app.footer.inc.php"; 

// --- end content -------------------
?>

