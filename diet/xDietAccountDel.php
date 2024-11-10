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
    
    $error = empty($clean['err']) ? 0 : $clean['err'];
    
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

    switch ($error) {
        case 1:
            echo "<div class='warning'>No se ha borrado completamente la cuenta.</div>";
            break;
        case 2:
            echo "<div class='warning'>La contrasenya no és correcta</div>";
            break;
    }

    // Form to delete account
    ?>
    <h3 class="card-title">Elimimar el compte</h3>
    <form action="xDietAccountDel_1.php" method="post">
        <table>
        <tr>
            <td style='vertical-align:middle'><label for="currentPassword">Clau actual</label></td>
            <td><input type="password" id="currentPassword" name="currentPassword" required></td>
        </tr>
        <tr>
            <td colspan="2"><br><input type="submit" value='Continua'>&nbsp;<input type="button" value="Cancel·la" onclick="history.back()"></td>
        </tr>
        </table>
    </form>

<?php
    include './includes/googleFooter.inc.php';
?>