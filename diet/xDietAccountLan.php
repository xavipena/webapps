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

    if ($error == 1) {

        echo "<div class='warning'>La contrasenya actual no és correcta.</div>";
    }
    // New form to change password
?>
        <h3 class="card-title">Canvi de clau</h3>
        <form action="xDietAccountLan_1.php" method="post" onsubmit="return validateForm()">
            <table>
            <tr>
                <td style='vertical-align:middle'><label for="language">Idioma</label></td>
                <td><select id="language" name="language" required>
                        <option value="ca">Català</option>
                        <option value="es">Castellà</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2"><br><input type="submit" value='Canvia el idioma'></td>
            </tr>
            </table>
        </form>
    <script>
        document.getElementById("language").value = <?php echo $lang?>;
    </script>
<?php
    include './includes/googleFooter.inc.php';
?>