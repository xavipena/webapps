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
    function GetSearchByQuery(query) {

        let url = "https://api.nal.usda.gov/fdc/v1/foods/search?api_key=<?php echo API_KEY_2?>&query=" + query;
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
                displayResults(data)
            })
            .catch ((error) => console.error("FETCH ERROR GetSearchByQuery:", error));
    }

    function addCard(catName, catDesc, fdcID) {

        let url = "xDietAPI_2_fdcID.php?fdcid=" + fdcID;

        let card = `
            <div class='card'>
            <div class='round_transparent'>
                <div style='height:110px; padding:5px;'>
                    <table cellpadding='5px'><tr><td>
                        <a href='${url}'>
                            <img width='80px' src='./images/notfound.png'>
                        </a>
                    </td><td>
                        <a href='${url}'>${catName}</a><br><span class='subtitleText'>${catDesc}</span>
                    </td></tr></table>
                </div>
            </div>
            </div>`;
        return card;
    }

    function displayResults(jsdata) {

        document.getElementById("fetched").innerHTML = " ";
        
        const maxLength = 130;
        const allFoods = jsdata.foods;
        for (let i = 0; i < allFoods.length; i++) {

            let foodCat = allFoods[i].foodCategory;
            let foodDesc = allFoods[i].description;
            let fdcID = allFoods[i].fdcId;
            let foodID = allFoods[i].foodCode;

            let card = addCard(foodCat, foodDesc, fdcID);
            document.getElementById("fetched").innerHTML += card;
        }
    }
</script>
</head>
<body>
<?php 
    //--- Params ----------------------- 
    
    $data = empty($clean['srch']) ? 0 : $clean['srch'];
    
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
    GetSearchByQuery(<?php echo $data?>);
</script>
<?php
    include './includes/googleFooter.inc.php';
?>