<?php
    session_start();
    include "../includes/dbConnect.inc.php";
    include "../includes/app.security.inc.php";
    include "../includes/app.header.inc.php";
?>
    <script>
        function openList()
        {
            var mall = document.getElementById("dtMal").value;
            window.location.href = "shoppingList.php?mall=" + mall;
        }
    </script>
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

function MallSelector($db)
{
    $mallSelector  = "<select onchange='' id='dtMal' name='dtMal' data-placeholder='Centre'>";
    $sql ="select * from diet_malls order by name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    {
        $mallSelector .= "<option value='".$row['IDmall']."'>".$row['name']."</option>";
    }
    $mallSelector .= "</select>";
    return $mallSelector;
}

function CountProds($db)
{
    $countProds = "";
    $sql =  "select diet_product_list.IDmall, name, count(*) as cnt ".
            "from diet_product_list ".
            "join diet_malls on diet_malls.IDmall = diet_product_list.IDmall ".
            "where done = 0 ".
            "group by IDmall, name";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        $countProds .= $row['cnt']." productes per ".$row['name']."<br>";
    }
    $countProds .= "<br>";
    return $countProds;
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
                        echo MallSelector($db);
                    ?>
                    </div>
                    <div class="app-select">
                    <?php
                        echo ProdSelector($db);
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
                <button type="button" class="app-button" onclick="javascript:openList()">llista</button>
            </div>
        </main>

<?php 
    include "../includes/app.footer.inc.php"; 

// --- end content -------------------
?>

