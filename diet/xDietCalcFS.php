<?php
    session_start();
    $runner_id ="";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    $page = "diet";
    include './includes/dbConnect.inc.php';
    include './includes/googleHeader.inc.php';
    include './includes/googleSecurity.inc.php';
?>
</head>
<body>
<?php 
    $_SESSION['pageID'] = PAGE_DIET_MENU;    
    $sourceTable ="";
    
    //--- new content -------------------- 
    
    //echo "<div class='dietPageContainer'>";
    include "./includes/menu.inc.php"; 
    include "./includes/dietMenu.inc.php";

if (isset($_SESSION['diet_user'])) {

    if ($_SESSION['diet_user'] != "") {

        $user = $_SESSION['diet_user'] == "1" ? "Xavi" : "Cris";
        echo "<br><br>";
        echo "<div class='round'>Usuari seleccionat</div><br>";
        echo "<ul><li>".$user."</li></ul>";
    }
}
?>
<br><br>
<div class='round'>FatSecret</div><br>

<div style="width:350px;">
<div id="diet_container" class="fatsecret_container"></div>
</div>
<div style="width:350px; position:relative;">
<span id="food_title"></span>
<div id="nutrition_panel"></div>
</div>

<script>
fatsecret.setContainer("diet_container");
fatsecret.setCanvas("home");
</script>



<script>
fatsecret.addRef("foodtitle", "food_title");
fatsecret.addRef("nutritionpanel", "nutrition_panel");
</script>



<a href="https://platform.fatsecret.com">
<img src="https://platform.fatsecret.com/api/static/images/powered_by_fatsecret.png" srcset="https://platform.fatsecret.com/api/static/images/powered_by_fatsecret_2x.png 2x, https://platform.fatsecret.com/api/static/images/powered_by_fatsecret_3x.png 3x" border="0"/>
</a>	

<?php
include './includes/googleFooter.inc.php';
?>