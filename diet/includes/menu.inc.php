<?php
/**
 *
 * @author xavipena
 * Menú anterior a menu.inc.back.php
 * - es treu tot el menú i es passa al sideMenuHover
 */
echo "<header>";
$copyright ="© Xavi Peña, Diari Digital, ".date("Y");
if (isset($page))
{
    if ($page == "x") {
        
        echo "<br>".$copyright;
    } 
    else {

        $lang = empty($_SESSION['lang']) ? "es" : $_SESSION['lang'];
        $sql = "select * from apps where page = '$page' and lang = '$lang'";
        $result = mysqli_query($db, $sql);
        if ($row = mysqli_fetch_array($result)) {
        
            echo "<h3>".$row['page']." - ".$row['title']." - $lang</h3>";
        }
        else {

            echo "<h3>$copyright -  No hi és definida la pàgina '$page'</h3>";
        }
    }
    // --------------------------------------------
    // Blog admin header
    // --------------------------------------------
    if (session_status() === PHP_SESSION_ACTIVE) {

        if ($_SESSION['pageID'] == PAGE_BLOGS || $_SESSION['pageID'] == PAGE_BLOCS)  {

            switch ($_SESSION['blogSet'])
            {
                case "A":
                    $set = "A";
                    $lng = 'es';
                    $typ = "G";
                    break;
            
                case "B":
                    $set = "B";
                    $lng = 'ca';
                    $typ = "P";
                    break;
            }
            echo "<div class='container'>";
            $sql = "select * from project_blogs where status ='A' and type = '$typ' and software = 'P' and ga4 = '$set' and lang = '$lng'";
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($result))
            {   
                $status = $_SESSION['blBLC'] == $row['blog'] ? "on" : "off";
                $baseImage = "../assets/images/logo_".$row['IDname']."_".$status;
                $image = $baseImage.".jpg";
                if (!file_exists($image)) $image = $baseImage.".png";
                echo " <img class='cardImageRound' src='$image' title='".$row['name']."' alt='".$row['name']."'>";
            }
            // Home icon
            if ($_SERVER['REQUEST_URI'] != "/blogs/googleBlogStart.php") {

                $baseImage = "../assets/images/home_logo_on";
                $image = $baseImage.".jpg";
                if (!file_exists($image)) $image = $baseImage.".png";
                echo " <a href='../blogs/googleBlogStart.php'><img class='cardImageRound' src='$image' title='Inici' alt='Inici'></a>";
            }
            echo "</div>";
        }
    }
    // --------------------------------------------
}
else echo $copyright;

if ($page != "wapps") {

    $iconBack = "../$page/images/arrowleft.png";
    if (!file_exists($iconBack)) {

        $iconBack = "../diet/images/arrowleft.png";
    }
    echo "  <div class='headerBackButton'><a href='javascript:history.back()'>".
        "      <img  src='$iconBack' ".
                "title='Torna enrera' ".
                "alt='Torna enrera'></a>";
    echo "      <hr>"; 
    echo "  </div>";

    $sql = "select useCookies from link_pages where IDpage = ".$_SESSION['pageID'];
    $res = mysqli_query($db, $sql);
    if ($des = mysqli_fetch_array($res))
    {
        $cookiesInUse = $des['useCookies'] == 1 ? YES : NO;
    }

}
echo "</header>";
echo "<main>";
?>