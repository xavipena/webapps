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
    include "./includes/loader.inc.php";

    //--- Params ----------------------- 
    
    $query = empty($clean['food']) ? "0" : $clean['food'];
    
    //--- Settings ------------------------

    $_SESSION['pageID'] = PAGE_DIET;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";
    include "./includes/cards.inc.php";

    function loadFoods($url) {

        //ShowSQL($url);

        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);
        $error = curl_error($ch);
        $info = curl_getinfo($ch);

        // close curl resource to free up system resources
        curl_close($ch);
        return array($output,$error,$info);
    }

    function ShowDetails($serving) {

        $rowStart   = $GLOBALS['rowStart'];
        $rowEnd     = $GLOBALS['rowEnd'];
        $newCol     = $GLOBALS['newCol'];
        $newColNum  = $GLOBALS['newColNum'];
        
        $calories   = $serving['calories'];
        $fat        = $serving['fat'];
        $satura     = $serving['saturated_fat'];
        $carbs      = $serving['carbohydrate'];
        $sugar      = $serving['sugar'];
        $fiber      = $serving['fiber'];
        $protein    = $serving['protein'];
        $sodium     = $serving['sodium'];

        $chole      = $serving['cholesterol'];
        $vitA       = $serving['vitamin_a'];
        $vitC       = $serving['vitamin_c'];
        $calcium    = $serving['calcium'];
        $iron       = $serving['iron'];
        $potassium  = $serving['potassium'];

        $unit1      = $serving['metric_serving_unit'];
        $unit2      = $serving['metric_serving_unit'] == "g" ? "mg" : "µg";

        echo "<table class='dietcard'>";
        echo "<caption>Valor nutricional</caption>";
        echo "<thead><tr>".
                    "<th>Item</th>".
                    "<th>Per 100g</th>".
            "</tr></thead>";
        
        echo $rowStart."Calories"    .$newColNum.$calories .$unit1.$rowEnd;
        echo $rowStart."Greix"       .$newColNum.$fat      .$unit1.$rowEnd;
        echo $rowStart."...saturats" .$newColNum.$satura   .$unit1.$rowEnd;
        echo $rowStart."Carbohidrats".$newColNum.$carbs    .$unit1.$rowEnd;
        echo $rowStart."...sucres"   .$newColNum.$sugar    .$unit1.$rowEnd;
        echo $rowStart."Fibra"       .$newColNum.$fiber    .$unit1.$rowEnd;
        echo $rowStart."Proteïna"    .$newColNum.$protein  .$unit1.$rowEnd;
        echo $rowStart."Sal"         .$newColNum.$sodium   .$unit1.$rowEnd;
        echo $rowStart.$newCol.$rowEnd;
        echo $rowStart."Colesterol"  .$newColNum.$chole    .$unit2.$rowEnd;
        echo $rowStart."Vitamina A"  .$newColNum.$vitA     .$unit2.$rowEnd;
        echo $rowStart."Vitamina C"  .$newColNum.$vitC     .$unit2.$rowEnd;
        echo $rowStart."Calci"       .$newColNum.$calcium  .$unit2.$rowEnd;
        echo $rowStart."Ferro"       .$newColNum.$iron     .$unit2.$rowEnd;
        echo $rowStart."Potassi"     .$newColNum.$potassium.$unit2.$rowEnd;

        echo "</table>";
    }

    //--- new content -------------------- 

    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

    // set loader element visible depending on selected type
    
    switch ($loaderType) {

        case BLACK_HOLE:

            $element = "loading";
            break;

        case DRAGONFLY:

            $element = "loading-wave";
            break;
    }

/*
    OAuth 1.0
    ------------------------------------------------------
    Consumer Key: 2c3596e3348347938602967a6157f877
    Consumer secret: 2d54ccab22af4b8a88320198e0ad8624
*/
    $consumer_key = "2c3596e3348347938602967a6157f877";
    $secret_key = "f36377c8366f464ab4f25d423fe9d2bc";
    $consumer_secret = "2d54ccab22af4b8a88320198e0ad8624";

    $fsurl = "https://platform.fatsecret.com/rest/server.api";

    //sort params by abc....necessary to build a correct unique signature
    $params  = "food_id=$query&";
    $params .= "format=json&";
    $params .= "method=food.get.v4&";
    $params .= "oauth_consumer_key=$consumer_key&";
    $params .= "oauth_nonce=".rand()."&";
    $params .= "oauth_signature_method=HMAC-SHA1&";
    $params .= "oauth_timestamp=".time()."&";
    $params .= "oauth_version=1.0";
    
    $base  = rawurlencode("GET")."&";
    $base .= rawurlencode($fsurl)."&";
    $base .= rawurlencode($params);
    
    $sig = base64_encode(hash_hmac('sha1', $base, "$consumer_secret&", true));
    
    $url = $fsurl."?".
           $params."&oauth_signature=".rawurlencode($sig);
    
    list($output, $error, $info) = loadFoods($url);
    if ($error == 0 || $error == "") {

        if ($info['http_code'] == '200') {

            $jsonDecoded = json_decode($output, true);
            $food  = $jsonDecoded['food'];
            $servs = $food['servings'];
            $serv  = $servs['serving'];

            $found = false;
            foreach ($serv as $serving) {

                foreach ($serving as $key => $value) {

                    if ($key == "serving_description") {
                        
                        if ($value == "100 g") {

                            $found = true;
                            break;
                        }
                    }
                }
                if ($found) {

                    ShowDetails($serving);
                    break;
                }
            }
        }
        else
            echo 'Status INFO : '.$info['http_code'];
    }
    else
        echo 'Status ERROR : '.$error." : ".$output;

    include './includes/googleFooter.inc.php';
?>