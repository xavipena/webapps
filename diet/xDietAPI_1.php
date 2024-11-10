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
    function GetCats() {

        let url = "https://www.themealdb.com/api/json/v1/1/categories.php";
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
                displayCategories(data)
            })
            .catch ((error) => console.error("FETCH ERROR GetCats:", error));
    }

    function addCard(catName, catImg, catDesc, catID) {

        let url = `xDietAPI_1_recipes.php?keyword=${catName}`;
        let card = `
        <div class='card'>
           <div class='round_transparent'>
              <div style='height:110px; padding:5px;'>
                  <table cellpadding='5px'><tr><td>
                      <a href='${url}'>
                          <img width='80px' src='${catImg}'>
                      </a>
                  </td><td>
                      <a href='${url}'>${catName}</a><br><span class='subtitleText'>${catDesc}</span>
                  </td></tr></table>
              </div>
           </div>
        </div>`;
        return card;
    }

    function displayCategories(jsdata) {

        const maxLength = 130;
        const allCats = jsdata.categories;
        for (let i = 0; i < allCats.length; i++) {

            let cat = allCats[i];
            let catName = cat.strCategory;
            let catImg = cat.strCategoryThumb;
            let catDesc = cat.strCategoryDescription;
            if (catDesc.length > maxLength) {

                catDesc = cat.strCategoryDescription.substring(0, maxLength) + "...";
            }
            let catID = cat.idCategory;
            let card = addCard(catName, catImg, catDesc, catID);
            document.getElementById("fetched").innerHTML += card;
        }
    }
</script>
</head>
<body>
<?php 
    include "./includes/loader.inc.php";

    //--- Params ----------------------- 
    
    $data = empty($clean['data']) ? 0 : $clean['data'];
    
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
<script>GetCats();</script>
<?php
    include './includes/googleFooter.inc.php';
?>