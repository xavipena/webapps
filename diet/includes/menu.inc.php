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