<?php
    session_start();
    include "../includes/dbConnect.inc.php";
    include "../includes/app.security.inc.php";
    include "../includes/app.header.inc.php";

    $err = empty($clean['err']) ? 0 : $clean['err'];
?>
</head>
<body>
<?php 

//--- Settings ------------------------

//--- new content ---------------------

//--- functions -----------------------

//--- Content -------------------------

?>
    <div class='appPageContainer'>
        <header>
<?php
        if ($err > 0) {

            echo "<div class='cardTitle'>";
            switch ($err) {
                case 1:
                    echo "Es requereix un usuari";
                    break;
                case 2:
                    echo "Es requereix una contrasenya";
                    break;
                default:
                    echo "Combinació d'usuari i clau errònia";
            }
            echo "</div>";
        }
        else {
?>
            <div class='cardCircle'>
                <div class='cardTitle'>
                LogIn
                </div>
            </div>
<?php 
        }
?>
        </header>
        <main>
            <form action="./login_1.php" class="app-form" method="post">
                <input type='hidden' name='fromApp' value='1'>

                <div class="app-content">

                    <div class="app-box">
                        <i class="ri-calendar-line app-icon"></i>

                        <div class="app-box-input">
                            <input type="text" class="app-input" id="dtUser" name="dtUser" placeholder="" value="" required>
                            <label for="" class="app-label">Usuari</label>
                        </div>
                    </div>

                    <div class="app-box">
                        <i class="ri-price-tag-3-line app-icon"></i>

                        <div class="app-box-input">
                            <input type="password" class="app-input" id="dtPass" name="dtPass" value="" placeholder="" required>
                            <label for="" class="app-label">Clau</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="app-button">Envia</button>
                <button type="button" class="app-button" onclick="location.href='menu.php'">Menú</button>
                
            </form>
        </main>
<?php 
    include "../includes/app.footer.inc.php";

// --- end content -------------------
?>

