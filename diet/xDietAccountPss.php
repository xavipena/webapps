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
<script>
    function validateForm() {

        var currentPassword = document.getElementById("currentPassword").value;
        var newPassword = document.getElementById("newPassword").value;
        var confirmPassword = document.getElementById("confirmPassword").value;

        if (currentPassword == "" || newPassword == "" || confirmPassword == "") {

            alert("Si us plau, ompla totes les caselles.");
            return false;
        }

        if (newPassword != confirmPassword) {

            alert("La contrasenya nova i la contrasenya de confirmació no coincideixen.");
            return false;
        }

        return true;
    }
</script>
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
        <form action="xDietAccountPss_1.php" method="post" onsubmit="return validateForm()">
            <table>
            <tr>
                <td style='vertical-align:middle'><label for="currentPassword">Clau actual</label></td>
                <td><input type="password" id="currentPassword" name="currentPassword" required></td>
            </tr>
            <tr>
                <td style='vertical-align:middle'><label for="newPassword">Nova clau</label></td>
                <td><input type="password" id="newPassword" name="newPassword" required></td>
            </tr>
            <tr>
                <td style='vertical-align:middle'><label for="confirmPassword">Confirma la clau</label></td>
                <td><input type="password" id="confirmPassword" name="confirmPassword" required></td>
            </tr>
            <tr>
                <td colspan="2"><br><input type="submit" value='Canvia la clau'>&nbsp;<input type="button" value="Cancel·la" onclick="history.back()"></td>
            </tr>
            </table>
        </form>

<?php
    include './includes/googleFooter.inc.php';
?>