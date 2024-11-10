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
    include "./includes/loader.inc.php";
?>    
</head>
<body>
<?php 
    include "./includes/loader.inc.php";

    //--- Params ----------------------- 
    
    $query = empty($clean['srch']) ? "banana" : $clean['srch'];
    
    //--- Settings ------------------------

    $_SESSION['pageID'] = PAGE_DIET;
    $sourceTable = "";
    include "./includes/sideMenuHover_2.inc.php";
    include "./includes/sideMenuHover_3.inc.php";

    //--- functions -------------------- 
    
    include "./includes/dietFunctions.inc.php";
    include "./includes/apiFunctions.inc.php";
    include "./includes/cards.inc.php";

    //--- new content -------------------- 

    include "./includes/settingsEnd.inc.php";
    include "./includes/menu.inc.php"; 

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
    $params  = "format=json&";
    $params .= "method=foods.search&";
    $params .= "oauth_consumer_key=$consumer_key&";
    $params .= "oauth_nonce=".rand()."&";
    $params .= "oauth_signature_method=HMAC-SHA1&";
    $params .= "oauth_timestamp=".time()."&";
    $params .= "oauth_version=1.0&";
    $params .= "search_expression=".urlencode($query);
    
    $base  = rawurlencode("GET")."&";
    $base .= rawurlencode($fsurl)."&";
    $base .= rawurlencode($params);
    
    $sig = base64_encode(hash_hmac('sha1', $base, "$consumer_secret&", true));
    
    $url = $fsurl."?".
           $params."&oauth_signature=".rawurlencode($sig);
    
    list($output, $error, $info) = loadFoods($url);

    echo "Selecciona un aliment:";

    echo "<div class='amanida'><ul class='amanida'>";
    if ($error == 0 || $error == "") {

        if ($info['http_code'] == '200') {

            $jsonDecoded = json_decode($output, true);
            $foods = $jsonDecoded['foods'];
            $food = $foods['food'];
            foreach ($food as $meal) {

                if (strpos($meal['food_description'], "g -")) {
                
                    echo "<li><a href='javascript:CallURL(\"$element\",\"xDietAPI_3_get.php?food=".$meal['food_id']."\")'>".$meal['food_name']."</a></li>";
                }
            }
        }
        else
            echo 'Status INFO : '.$info['http_code'];
    }
    else
        echo 'Status ERROR : '.$error." : ".$output;
    
    echo "</ul></div>";

    include './includes/googleFooter.inc.php';
?>