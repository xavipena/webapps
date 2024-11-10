<?php
    session_start();
    include "../includes/dbConnect.inc.php";
    include "../includes/app.security.inc.php";
    include "../includes/app.header.inc.php";

    if (empty($_SESSION['diet_user'])) header("location: user.php");
?>
    <script>
        function goToSelector(type) {

            window.location.href =  "mealSelect_1.php?type=" + type;
        }
    </script>
</head>
<body>
<?php

//--- Settings ------------------------

if (empty($_SESSION["meal"]))
{
    $_SESSION["meal"] = $clean['dtMel'];
}

//--- functions -----------------------

include "../includes/dietFunctions.inc.php";
$selectPerImage = GetSettingValue($db, "3");

//--- Content -------------------------

?>
    <div class='appPageContainer'>
        <header>
            <div class='cardCircle'>
                <div class='cardTitle'>
                Àpats
                </div>
            </div>
        </header>
        <main>
            <form action="" class="app-form" method="post" id='appForm'>
<?php
                echo "<input type='button' class='app-button' value='Fresc' onclick='goToSelector(1)'>";
                echo "<input type='button' class='app-button' value='Begudes' onclick='goToSelector(2)'>";
                echo "<input type='button' class='app-button' value='Salses' onclick='goToSelector(3)'>";
                echo "<input type='button' class='app-button' value='Plats' onclick='goToSelector(4)'>";
                echo "<input type='button' class='app-button' value='Tots' onclick='goToSelector(0)'>";
?>            
            </form>
        </main>
<?php 
    include "../includes/app.footer.inc.php"; 
?>
