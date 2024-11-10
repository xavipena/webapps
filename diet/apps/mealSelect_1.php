<?php
    session_start();
    include "../includes/dbConnect.inc.php";
    include "../includes/app.security.inc.php";
    include "../includes/app.header.inc.php";
?>
<script>
    function goSelection()
    {
        let form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", "mealSave_1.php");
        
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

//--- Parameters ----------------------

$type = empty($clean['type']) ? 0 : $clean['type'];

//--- Settings ------------------------

//--- functions -----------------------

include "../includes/cards.inc.php";

function GetTitle($type) {

    $ret =  "Producte";

    switch($type) {

        case 1:
            // Fresh
            $ret = "Fresc";
            break;
        case 2:
            // Drinks
            $ret = "Begudes";
            break;
        case 3:
            // Salses
            $ret = "Salses";
            break;
        case 4:
            // Plats preparats
            $ret = "Plats";
            break;
    }
    return $ret;
}

function PrepareSQL($type) {

    $sql =  "select p.IDproduct pID, p.name pname, p.short pdesc, b.name bname from diet_products p ";

    switch($type) {

        case 1:
            // Fresh
            $sql .= "join diet_product_brands b on b.IDbrand = p.brand and b.IDbrand = 14 ".
                    "where p.status = 'A' ".
                    "order by p.name";
            break;
        case 2:
            // Drinks
            $sql .= "join diet_product_brands b on b.IDbrand = p.brand ".
                    "where p.status = 'A' and food = 'Líquid'".
                    "order by p.name";
            break;
        case 3:
            // Salses
            $sql .= "join diet_product_brands b on b.IDbrand = p.brand ".
                    "where p.status = 'A' and food = 'Salsa'".
                    "order by p.name";
            break;
        case 4:
            // Plats preparats
            $sql .= "join diet_product_brands b on b.IDbrand = p.brand ".
                    "where p.status = 'A' and food = 'Preparat'".
                    "order by p.name";
            break;
    }
    return $sql;
}

//--- Content -------------------------

?>
    <div class='appPageContainer'>
        <header>
            <div class='cardCircle'>
                <div class='cardTitle'>
                <?php echo GetTitle($type)?>
                </div>
            </div>
        </header>
        <main>
<?php
            $sql = PrepareSQL($type);
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($result))
            {
                AddAppSelectCard("diet", $row['pID'], $row['pname'], $row['pdesc']);
            }
?>
            <div class="sendButton">
                <input type="button" value="Desa-ho" class="app-button" onclick="goSelection()">
            </div>
        </main>
<?php 
    include "../includes/app.footer.inc.php"; 
?>
