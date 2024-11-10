<?php
    session_start();
    $runner_id ="";
    $page = "diet";
    if (!empty($_SESSION['runner_id'])) $runner_id =$_SESSION['runner_id'];
    include './includes/dbConnect.inc.php';
    include './includes/googleHeader.inc.php';
    include './includes/googleSecurity.inc.php';
    include "./includes/sideMenuHover_1.inc.php";
?>
</head>
<body>
<?php 
    $_SESSION['pageID'] = PAGE_DIET_MENU;    
    $page ="Diet";
    $sourceTable = "diet_product_brands";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";
    
    //--- new content -------------------- 
    
    //echo "<div class='dietPageContainer'>";
    include "./includes/menu.inc.php"; 
    include "./includes/dietMenu.inc.php";

?>
<form id="" method="post" action=""> 
    <div class="formClass">

        <label for="lname">Nom de marca</label>
        <input type="text" name="lname" id="Brandname" value="">
        <label for="lname">Companyia</label>
        <input type="text" name="lcomp" id="BrandComp" value="">
        <label for="lname">Descripció</label>
        <textarea id="brandText" name="brandText" rows="4" cols="50"></textarea>
        <input type="submit" value="Envia">
    </div>
</form>

<?php
include './includes/googleFooter.inc.php';
?>