<?php
/**
 *
 * @author xavipena
 */
?>
<br>
<?php
    if ($page == "") $page = "apps"; // --> generic page to default to
    $iconBack = "./images/arrowleft.png";
    echo "</main>";
    echo "<footer>";
    echo "<div class='footerBar'>";
    if ($page == "diet") { 
        
        echo "<div class='footerText'><a href='xDietReferences.php'>".locale("strReference")."</a>";
        echo "</div>";
    }
    echo "<div class='footerBackButton'><a href='javascript:history.back()'>".
            "<img src='$iconBack' ".
            "title='Torna enrera' ".
            "alt='Torna enrera'></a></div>";
    echo "  <br><br><hr>";

    $sql =  "select disclaimer from diet_manual where IDstep = -1 and lang = '".$GLOBALS['lang']."'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result);

    echo "  <div class='footerSmallText'>".$row['disclaimer'];
    echo "      <br><a href='#' data-cc='show-preferencesModal'>Cookies</a> | <a href=''>Política de privacitat</a> | <a href=''>Avís legal</a>";
    echo "  </div>";
    echo "  <hr>";
    echo "  © Xavi Peña, Diari Digital, ".date("Y");
    echo "</div>";
    echo "</footer>";
    if ($cookiesInUse == YES) { 
        
        echo "<script type='module' src='./js/cookieconsent-config.js'></script>";
    }
?>
</div> <!-- RightContainer -->
</body>
</html>