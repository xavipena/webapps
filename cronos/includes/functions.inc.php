<?php
/* ------------------------------------------------------------------------
API page
https://diaridigital.net/galleries/tools/ws.htm

Índex de funcions
-----------------
Piwigo
-----------------
function CallUrl($url, $context, $header, $post)
function GetSessionID()
function LogIn()
function LogOut()
function GetPicture($IDimage, $size = "2small")
function GetAlbum($IDalbum)

Categories
-----------------
function GetDescription($db, $value, $id, $mode)
--------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------ 
Call url via cURL
Parameters
    -> The url
    -> The context parameters
    -> header: bool
    -> post: bool
*/
function CallUrl($url, $context, $header, $post) {

    //echo $url."<br><br>";
    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_HEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, $post);
    if (gettype($context) == "array") {

        curl_setopt_array($curl, $context);        
    }
    else {

        curl_setopt($curl, CURLOPT_POSTFIELDS, $context);        
    }

    $json_response = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    return $json_response;
}

/* ------------------------------------------------------------------------
Returns session ID 
*/
function GetSessionID() {

    $cookies = explode('; ', $_SERVER['HTTP_COOKIE']);
    $allCookies = [];

    foreach($cookies as $cookie) {

        $keyAndValue = explode('=', $cookie);
        $allCookies[$keyAndValue[0]] = $keyAndValue[1];
    }

    if (empty($allCookies["PHPSESSID"])) return "";
    return $allCookies["PHPSESSID"];
}

/* ------------------------------------------------------------------------
Open session
*/
function LogIn() {

    $loginData = http_build_query (

        array (
            'username' => PIWIGO_USER_LOGIN,
            'password' => PIWIGO_USER_PWD
        )
    );
  
    $url  = PIWIGO_GAL_API."?method=pwg.session.login&format=json";
    $json_response = CallUrl($url, $loginData, false, true);
    return (strpos($json_response, "ok") === false) ? "" : "ok";
}

/* ------------------------------------------------------------------------
Close session
*/
function LogOut() {

    $url  = PIWIGO_GAL_API."?method=pwg.session.logout&format=json";
    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, false);

    $json_response = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
}

// ------------------------------------------------------------------------
// Functional
// ------------------------------------------------------------------------

/* ------------------------------------------------------------------------
Parameters
    -> The ID of the image
    -> Size of the image
Info from Config.inc.php
    -> URL of your API (ending with "/ws.php")
    -> Login of the user you created on step 1
    -> Password of the user you created on step 1
*/
function GetPicture($IDimage, $size = "2small") {

    // ------------------
    // Login
    // ------------------

    if (LogIn() == "") return locale("strDenied");

    // ------------------
    // Get session id in cookie
    // ------------------

    $loginID = GetSessionID();

    // ------------------
    // Get picture
    // ------------------

    $responseOpts = array('http' =>
        array (
            'method' => 'GET',
            'header' => 'Cookie: pwg_id='.$loginID
        )
    );

    $url  = PIWIGO_GAL_API."?method=pwg.images.getInfo&format=json&image_id=".$IDimage;    
    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, false);
    //curl_setopt_array($curl, $responseOpts);

    $json_response = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if (strpos($json_response, "Access denied") > 0) {

        LogOut();
        return locale("strDenied");
    }

    $imageData =  $json_response;

    $p = strpos($json_response, "{");
    $clean_json = $imageData = substr($json_response, $p, strlen($json_response) - $p);
    $decoded_json = json_decode($clean_json, true); // Associative array

    $result = $decoded_json['result'];
    $derivatives = $result['derivatives'];
    $xsmall = $derivatives[$size];
    $p1 = $xsmall['url'];
    $p2 = $xsmall['width'];
    $p3 = $xsmall['height'];

    if ($_SESSION['polaroid'] == YES) {

        // only to fit the polaroid frame
        if ($p3 > $p2) {

            if ($_SESSION['imageAspect'] == YES ) {

                $ratio = $p3 / $p2;
                $p3 = 180;
                $p2 = $p3 / $ratio;
            }
            else {

                // swap
                $xx = $p2;
                $p2 = $p3;
                $p3 = $xx;
            }
        }
    }

    $p4 = "";
    $categories = $result['categories'];
    foreach ($categories as $cat) {

        $p4 = $cat['id'];
    }

    // ------------------
    // Logout
    // ------------------

    LogOut();

    return [$p1, $p4, $p2, $p3];
}

/* ------------------------------------------------------------------------
Parameters
    -> The ID of the album
Info from Config.inc.php
    -> URL of your API (ending with "/ws.php")
    -> Login of the user you created on step 1
    -> Password of the user you created on step 1
*/

function GetAlbum($IDalbum) {

    // ------------------
    // Login
    // ------------------

    if (LogIn() == "") return "";

    // ------------------
    // Get session id in cookie
    // ------------------

    $loginID = GetSessionID();

    // ------------------
    // Get pictures in album
    // ------------------

    // ------------------
    // Logout
    // ------------------

    LogOut();
}

/* ------------------------------------------------------------------------
Parameters
    -> database
    -> value to get description
    -> ID de filtro
    -> mode
    Return array [filter, table, field, value]
*/
function GetDescription($db, $value, $id, $mode) {

    $filterName = "";
    $tableName  = "";
    $fieldID    = "";
    $valueName  = "";

    switch ($mode) {

        case FILTERING:
            $valueName  = locale("strAll");
            break;
        case TAGGING:
            $valueName  = locale("strNone");
            break;
    }
    $fieldID    = "";
    
    switch ($id) {

        case SUBJECT:
            $filterName = locale("strSubject");
            $tableName = "crono_subjects";
            $fieldID = "IDsubject";
            $sql = "select name from crono_subjects where IDsubject = $value";
            break;
        case YEAR:
            $filterName = locale("strYear");
            if ($value > 0) $valueName = $value;
            break;
        case MONTH:
            $filterName = locale("strMonth");
            if ($value > 0) $valueName = date('F', mktime(0, 0, 0, $value, 10));
            break;
        case COUNTRY:
            $filterName = locale("strCountry");
            $tableName = "countries";
            $fieldID = "IDcountry";
            $sql = empty($value) ? "" : "select name from countries where IDcountry = '$value'";
            break;
        case CITY:
            $filterName = locale("strCity");
            $tableName = "crono_cities";
            $fieldID = "IDcity";
            $sql = "select name from crono_cities where IDcity = $value";
            break;
        case EVENT:
            $filterName = locale("strEvent");
            $tableName = "crono_events";
            $fieldID = "IDevent";
            $sql = "select name from crono_events where IDevent = $value";
            break;
        case PERSON:
            $filterName =locale("strPerson");
            $tableName = "crono_persons";
            $fieldID = "IDperson";
            $sql = "select name from crono_persons where IDperson = $value";
            break;
        case GROUP:
            $filterName = locale("strGroup");
            $tableName = "crono_groups";
            $fieldID = "IDgroup";
            $sql = "select name from crono_groups where IDgroup = $value";
            break;        
        case DETAIL:
            $filterName = locale("strDetail");
            $tableName = "crono_details";
            $fieldID = "IDdetail";
            $sql = "select name from crono_details where IDdetail = $value";
            break;
        case TYPE:
            $filterName = locale("strType");
            $tableName = "crono_types";
            $fieldID = "IDtype";
            $sql = "select name from crono_types where IDtype = $value";
            break;
    }
    if (!empty($sql)) {

        $result = mysqli_query($db, $sql);
        if ($row = mysqli_fetch_array($result)) {
            
            $valueName = $row['name'];
        }
    }
    return [$filterName, $tableName, $fieldID, $valueName];
}
?>