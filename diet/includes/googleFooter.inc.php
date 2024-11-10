<?php
/**
 *
 * @author xavipena
 */
?>
<br>
<?php
    if ($page == "") $page = "apps"; // --> generic page to default to
    $iconBack = "../$page/images/arrowleft.png";
    echo "</main>";
    echo "<footer>";
    echo "<div class='footerBar'>";
    if ($page == "diet") { 
        
        echo "<div class='footerText'><a href='xDietReferences.php'>".locale("strReference")."</a>";
        echo "</div>";
    }
    if ($page != "wapps") {
        
        echo "  <div class='footerBackButton'><a href='javascript:history.back()'>".
                "<img src='$iconBack' ".
                "title='Torna enrera' ".
                "alt='Torna enrera'></a></div>";
    }
    echo "  <br><br><hr>";
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