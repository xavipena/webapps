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
<style>
    .recipe {
        width: 150px;
        border-radius: 10px;
        border: 1px solid #eee;
    }
</style>
<script>
    function GetSheet(fdcID) {

        let url = `https://api.nal.usda.gov/fdc/v1/food/` + fdcID + "?api_key=<?php echo API_KEY_2?>";
        fetch (url) 
            .then ((response) => {
                if (response.ok) 
                {
                    return response.json();
                } 
                else 
                {
                    throw new Error("NETWORK RESPONSE ERROR");
                }
            })
            .then (data => {
                displaySheet(data)
            })
            .catch ((error) => console.error("FETCH ERROR GetSheet:", error));
    }

    function displaySheet(jsdata) {

        document.getElementById("fetched").innerHTML = " ";
        let card = "<table class='dietcard'>";

        const maxLength = 200;
        const nutrients = jsdata.foodNutrients;
        for (let i = 0; i < nutrients.length; i++) {

            let name = nutrients[i].nutrient.name;
            if ("SFA MUF PUF".indexOf(name.substring(0, 3)) < 0) {
            
                card += "<tr><td>" + nutrients[i].nutrient.name + "</td><td>" + nutrients[i].amount + " " + nutrients[i].nutrient.unitName + "</td></tr>";
            }
        }
        document.getElementById("fetched").innerHTML += card + "</table>";
    }
</script>
</head>
<body>
<?php 
    //--- Params ----------------------- 
    
    $fdcID = empty($clean['fdcid']) ? 0 : $clean['fdcid'];
    
    //--- Settings ------------------------

    $_SESSION['pageID'] = PAGE_DIET;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 
        
    //--- new content -------------------- 

    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 
?>
<div id="fetched" class="container"></div>
<script>
    document.getElementById("fetched").innerHTML = "Carregant...";
    GetSheet('<?php echo $fdcID?>');
</script>
<?php
    include './includes/googleFooter.inc.php';
?>