<?php
/* 
  Side menu with hover
  Include 3 / 3
  menu container and shape
  Closing div at footer
*/
//echo ($isMob."|".$isTab."|".$isIOS."|".$isAndroid."|".$isIPhone);
if ($isMob || $isTab || $isIOS || $isAndroid || $isIPhone) 
{
    echo "<div>";
}
else
{
?>
    <!-- ++ SideMenu -->
    <div id="l-menu">
        <div class="hamburger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
        <div class="l-menu-inner">        
            <ul>
                <li><div class="topLogo">
                    <a href='https://diaridigital.net'>
                        <img class="imgLogo" src='../assets/images/ddg_on.png'>
                    </a>
                    </div>
                </li>
                <?php 
                if (session_status() === PHP_SESSION_ACTIVE) {

                    if (empty($_SESSION['pageID'])) {
                        
                        echo LeftMenu($db, PAGE_HOME);
                    }
                    else 
                    { 
                        echo LeftMenu($db, $_SESSION['pageID']);
                    }
                }
                else echo LeftMenuPublic($pageType);
                ?>
            </ul>
        </div>
        <svg version="1.1" 
            id="blob"
            xmlns="http://www.w3.org/2000/svg" 
            xmlns:xlink="http://www.w3.org/1999/xlink">
            <path id="blob-path" d="M60,500H0V0h60c0,0,20,172,20,250S60,900,60,500z"/>
        </svg>
    </div>
    <!-- -- SideMenu -->
    <div class="RightContainer">
<?php
}
?>