<?php
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $page = "diet";
    include './includes/dbConnect.inc.php';
    include './includes/googleSecurity.inc.php';

    // check password befor continue
    $password = md5($clean['currentPassword']);
    $query = "select * from diet_users where IDuser = ".$_SESSION['diet_user']." and password = '$password' limit 1";
    $results = mysqli_query($db, $query);
    if (!mysqli_fetch_array($results)) {

        header("location: xDietAccountDel.php?err=2");
        exit();
    }

    include './includes/googleHeader.inc.php';
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
    <form action="xDietAccountDel_2.php" method="post">
        <table>
        <tr>
            <td style='vertical-align:middle'><label for="currentUsername">Escriu el nom d'usuari</label></td>
            <td><input type="text" id="currentUsername" name="currentUsername" required></td>
        </tr>
        <tr>
            <td colspan="2"><br><input type="submit" value='Continua'>&nbsp;<input type="button" value="No, cancel·la-ho" onclick="location.href='xDietuser_1.php'"></td>
        </tr>
        </table>
    </form>

<?php
    include './includes/googleFooter.inc.php';
?>