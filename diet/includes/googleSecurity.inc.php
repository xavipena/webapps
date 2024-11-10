<?php
    $canProceed = FALSE;
    
    // Check for web apps, allow IP
    //-----------------------------------------------------------------------------
    $isWebApp = empty($isWebApp) ? false : true;

    // Apps with own login procedure
    // diet has it's own login
    //-----------------------------------------------------------------------------
    $canProceed = TRUE;

    // Sanitize input
    //-----------------------------------------------------------------------------
    $clean = array();
    foreach(array_keys($_REQUEST) as $key)
    {
        $clean[$key] = mysqli_real_escape_string($db, $_REQUEST[$key]);
    }

    // Check if IP control is deactivated; Y = no control
    //-----------------------------------------------------------------------------
    if (!$canProceed) {

        if (!empty($_SESSION['loggedKey'])) {

            if ($_SESSION['loggedKey'] == "Y") {

                $canProceed = TRUE;
            }
        }
    }

    if (!$canProceed) {

        // Already logged
        //-----------------------------------------------------------------------------
        $allowed = !empty($_SESSION['allowed']) ? $allowed = $_SESSION['allowed'] : "";
        $canProceed = $allowed == "yes";

        require '../login/PasswordHash.php';

        //  trace the IP address
        //-----------------------------------------------------------------------------

        if (!$canProceed) {

            if ($isWebApp) $canProceed = true;
            else {
                
                $ip = "";
                if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                }
                else 
                {
                    $ip = $_SERVER['REMOTE_ADDR'];
                }
                $sql = "select ip from users_ip where ip = '$ip' and status = 'A'";
                $result = mysqli_query($db, $sql);
                $canProceed = $row = mysqli_fetch_array($result);
            }
        }

        // allow access by key. Check if key logged ?key=
        //-----------------------------------------------------------------------------
        if (!$canProceed) {

            $key = empty($clean['key']) ? "" : $clean['key'];
            if ($key != "") 
            {
                $username = "xavipena";
                $sql = "select password,salt from users where md5(username) = '".md5($username)."' and status ='A' and salted = 'Y'";
                $result = mysqli_query($db, $sql);
                if ($row = mysqli_fetch_array($result))
                {
                    $salted = $row['salt'] . $key;
                    $check = md5($salted) == $row['password'];
                    if ($check) {

                        $canProceed = TRUE;
                        $_SESSION['loggedKey'] = "Y";
                    }
                }
        
            }
        }

        // Not visible
        //-----------------------------------------------------------------------------
        if (!$canProceed) {

            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            $acceptLang = ['ca', 'es', 'en']; 
            $lang = in_array($lang, $acceptLang) ? $lang : 'en';

            echo "</head><body>";
            echo "<div style='width:90%; position:absolute; top:125px; left:50px; font-size:15pt;'>";
            echo "IP ".$ip."<br>";
            switch ($lang) {
                case "ca":
                    echo "Aquesta pàgina no és visible";
                    break;
                case "es":
                    echo "esta página no está visible"; 
                    break;
                case "en":
                    echo "This page is not visible"; 
                    break;
                }
            echo "</div></body></html>";
            exit;
        }
    }
?>