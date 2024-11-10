<?php
    session_start();
    include "../includes/dbConnect.inc.php";
    include "../includes/app.security.inc.php";
    include "../includes/app.header.inc.php";

    $user = empty($_SESSION['diet_user']) ? "" : $_SESSION['diet_user'];
    if ($user == "")
    {
        header("Location: ./login.php");
        exit();
    }
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
            <div class='cardCircle'>
                <div class='cardTitle'>
                Menú
                </div>
            </div>
        </header>
        <main>
            <form action="" class="app-form" method="post" id='appForm'>
                <button type="button" class="app-button" onclick="location.href='user.php'">Usuari</button>
                <button type="button" class="app-button" onclick="location.href='weight.php'">Pes</button>
                <button type="button" class="app-button" onclick="location.href='meals.php'">Àpat</button>
                <button type="button" class="app-button" onclick="location.href='shopping.php'">Compra</button>
            </form>
        </main>
<?php 
    include "../includes/app.footer.inc.php"; 
?>
