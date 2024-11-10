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
    function GetRecipes(keyword) {

        let url = "https://www.themealdb.com/api/json/v1/1/search.php?s=" + keyword;
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
                displayRecipes(data)
            })
            .catch ((error) => console.error("FETCH ERROR GetRecipes:", error));
    }

    function addCard(recipeName, recipeImg, recipeDesc, recipeID) {

        let card = `
            <div class='coverCard'>
                <a href=''>
                    <div class='cardTitle'>
                        <img class='recipe' src='${recipeImg}'>
                    </div>
                </a><br>
                <a href=''><h2>${recipeName}</h2></a><br>
               <span class='coverCardText'>${recipeDesc}</span>
            </div>`;
        return card;
    }

    function displayRecipes(jsdata) {

        const maxLength = 200;
        const allRecipes = jsdata.meals;
        for (let i = 0; i < allRecipes.length; i++) {

            let recipeName = allRecipes[i].strMeal;
            let recipeImg = allRecipes[i].strMealThumb;
            let recipeDesc = allRecipes[i].strInstructions;
            if (recipeDesc.length > maxLength) {

                recipeDesc = recipeDesc.substring(0, maxLength) + "...";
            }
            let recipeID = allRecipes[i].idMeal;

            let card = addCard(recipeName, recipeImg, recipeDesc, recipeID);
            document.getElementById("fetched").innerHTML += card;
        }
    }
</script>
</head>
<body>
<?php 
    //--- Params ----------------------- 
    
    $key = empty($clean['keyword']) ? 0 : $clean['keyword'];
    
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
<script>GetRecipes('<?php echo $key?>');</script>
<?php
    include './includes/googleFooter.inc.php';
?>