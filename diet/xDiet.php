<?php
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $page = "diet";
    include './includes/dbConnect.inc.php';
    include './includes/googleSecurity.inc.php';
    include './includes/googleHeader.inc.php';
    include "./includes/settingsStart.inc.php";
    include "./includes/sideMenuHover_1.inc.php"; 
    include "./includes/background.inc.php";
?>
</head>
<body>
<?php 

//--- Settings ------------------------

$_SESSION['pageID'] = PAGE_DIET;
$sourceTable = "";
include "./includes/sideMenuHover_2.inc.php";
include "./includes/sideMenuHover_3.inc.php";
// local files
include "./includes/cards.inc.php";

//--- functions -------------------- 


//--- new content -------------------- 

include "./includes/settingsEnd.inc.php";
include "./includes/menu.inc.php"; 

$sql ="select count(*) as cnt from diet_products";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result))
{
    $num1 = $row['cnt'];
}
$sql ="select count(*) as cnt from diet_dishes";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result))
{
    $num2 = $row['cnt'];
}
$num3 = 0;

$options = $_SESSION['diet_admin'] == 1 ? "" : "and isAdmin = 0 ";
$userOptions = array(2, 48, 50);
$section = 0;
$sql = "select diet_menu.*, sName from diet_menu ".
       "join diet_menu_sections ".
       "  on diet_menu.IDsection = diet_menu_sections.IDsection and diet_menu.lang = diet_menu_sections.lang ".
       "where diet_menu_sections.IDsection in (1, 2, 3, 5) and diet_menu.lang = '$lang' $options ".
       "order by diet_menu_sections.IDsection, sequence";
$result = mysqli_query($db, $sql);
while ($row = mysqli_fetch_array($result))
{
    if ($section != $row['IDsection'])
    {
        if ($section > 0)
        {
            echo "</div>";
        }
        echo "<br><div class='round'>".$row['sName']."</div>";        
        echo "<div class='container'>";
        $section = $row['IDsection'];
    }

    // exclude options
    if ($row['IDmenu'] == 48 && !empty($_SESSION['diet_user'])) continue;
    if ($row['IDmenu'] == 50 && empty($_SESSION['diet_user'])) continue;

    $prefix = in_array($row['IDmenu'], $userOptions) ? "user" : "diet";
    AddCard($row['page'], $prefix, $row['name'], $row['description'], false, false, $row['isMenu'] == 1);
}
echo "</div>";

// ----------------------------
// Go shopping
// restricted to admin user
// ----------------------------
if ($_SESSION['diet_admin']== 1) {

    echo "<br><div class='round'>Compra</div>";        
    echo "<div class='container'>";
        AddCard("xDietShopping.php","diet","Actualitzar","Actualitza la compra i prepara la llista del que cal comprar");
        AddCard("xDietShoppingLst.php","diet","Llista de la compra","Preparar la llista de la compra");
    echo "</div>";
}

// Other card
echo "<br><div class='round'>Documentació i informació</div>";        
echo "<div class='container'>";
    AddCard("xDiet_2.php","diet","Informació","Dades mestres i informació sobre les dietes", false, false, true);
echo "</div>";

// ----------------------------
// Testing options
// restricted to admin user
// ----------------------------
if ($_SESSION['diet_admin']== 1) {

    echo "<br><div class='round'>Més coses</div>";        
    echo "<div class='container'>";
        AddCard("apps/menu.php","diet","Mòbil","Menú per mòbil simplificat de fàcil accés per entrar l'àpat diàri", false, false, true);
        AddCard("xDietTesting.php","diet","Proves","Coses noves que estic provant", false, false, true);
    echo "</div>";
}
include './includes/googleFooter.inc.php';
?>