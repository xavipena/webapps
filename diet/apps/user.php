<?php
    session_start();
    include ".././includes/dbConnect.inc.php";
    include "./includes/app.security.inc.php";
    include "./includes/app.header.inc.php";

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

$isWebApp = true;

//--- new content ---------------------

//--- functions -----------------------

function UserSelector($db, $current)
{
    $UserSelector = "<select onchange='' id='dtUser' name='dtUser' data-placeholder='Usuari'>";
    $sql ="select * from diet_users";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result)) 
    {
        $selected = $row['IDuser'] == $current ? "selected" : ""; 
        $UserSelector .= "<option $selected value='".$row['IDuser']."'>".$row['name']."</option>";
    }
    $UserSelector .= "</select>";
    return $UserSelector;
}

//--- Content -------------------------

?>
    <div class='appPageContainer'>
        <header>
            <div class='cardCircle'>
                <div class='cardTitle'>
                    Usuari
                </div>
            </div>
        </header>
        <main>
            <form action="" class="app-form" method="post" id='appForm'>
                <button type="submit" class="app-button" onclick="location.href='weight.php'">Pes</button>
                <button type="button" class="app-button" onclick="location.href='logout.php'">Desconnecta</button>
                <button type="button" class="app-button" onclick="location.href='menu.php'">Menú</button>
            </form>
        </main>
<?php 
    include "./includes/app.footer.inc.php"; 

// --- end content -------------------
?>
