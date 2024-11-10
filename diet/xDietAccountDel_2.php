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
</head>
<body>
<?php 
    //--- Params ----------------------- 
    
    $data = empty($clean['data']) ? 0 : $clean['data'];
    
    //--- Settings ------------------------

    $_SESSION['pageID'] = PAGE_DIET;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";
    
    //--- new content -------------------- 

    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

    // Form to delete account
    ?>
    <h3 class="card-title">Eliminar el compte</h3>
    <form action="xDietAccountDel_3.php" method="post">
        <table width="50%">
        <tr>
            <td>Si continues s'esborrarà el compte, tota la informació dels àpats entrats i les dades biomètriques de l'usuari. No es podrà recuperar la informació.</td>
        </tr>
        <tr>
            <td><br><input type="submit" value="Sí, esborra el compte"></td>
            <td><br><input type="button" value="No, cancel·la-ho" onclick="location.href='xDietUser_1.php'"></td>
        </tr>
        </table>
    </form>

<?php
    include './includes/googleFooter.inc.php';
?>