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

    //--- Settings ------------------------

    $_SESSION['pageID'] = PAGE_DIET;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    // local files

    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";
    
    //--- new content -------------------- 

    //echo "<div class='dietPageContainer'>";
    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

    echo "<div class='container'>";
        DiagramCard(10, "diet","Carbohidrats","Después de comer, el cuerpo descompone los nutrientes llamados carbohidratos en un azúcar llamado glucosa.", true);
        DiagramCard(20, "diet","Glucosa","La glucosa es la principal fuente de energía del cuerpo. También se llama glucosa sanguínea. La glucosa sanguínea sube después de comer.", true);
        Diagramcard(30, "diet", "Insulina", "La insulina se produce en el páncreas cuando la glucosa sube y permite que esta entre en las células.", true);
        //Cuando la glucosa entra en el torrente sanguíneo, el páncreas responde produciendo insulina. La insulina permite que la glucosa entre en las células del cuerpo para darles energía.
        DiagramCard(40, "diet", "Glucógeno", "El exceso de glucosa se almacena en el hígado. Esta glucosa almacenada se conoce como glucógeno.", true);
        //Después de comer, los niveles de insulina son altos. El exceso de glucosa se almacena en el hígado. Esta glucosa almacenada se conoce como glucógeno.
        DiagramCard(50, "diet", "Hígado", "Entre comidas, los niveles de insulina son bajos. Durante ese tiempo, el hígado libera glucógeno al torrente sanguíneo en forma de glucosa.", true);
        // Entre comidas, los niveles de insulina son bajos. Durante ese tiempo, el hígado libera glucógeno al torrente sanguíneo en forma de glucosa.
        DiagramCard(60, "diet","Almacenamiento","Los dos lugares principales de almacenamiento del glucógeno son el hígado y el músculo esquelético", true);
        // Los dos lugares principales de almacenamiento del glucógeno son el hígado (10% en peso) y el músculo esquelético (2% en peso), aunque hay más masa musculasr que hígado
        DiagramCard(70, "diet", "Glucógeno Muscular", "El exceso de glucosa se almacena en el músculo esquelético. Esta glucosa almacenada se conoce como glucógeno muscular.", true);
        // en el músculo el glucógeno juega un papel de almacén de glucosa para sus propias necesidades, como el ejercicio físico
        DiagramCard(80, "diet","Energía","Cuado se acaba el glucógeno muscular, se busca energía en las grasas");
        // Cuado se acaba el glucógeno muscular, se busca energía en las grasas
        echo "</div>";

    include './includes/googleFooter.inc.php';
?>