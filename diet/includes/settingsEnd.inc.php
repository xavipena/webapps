<?php
// ---------------------------------------------------------------
// Settings 
// ---------------------------------------------------------------
if (session_status() === PHP_SESSION_ACTIVE) {

    if ($_SESSION['headTitle'] == YES)
    {
        $sql = "select description from link_pages where IDpage = ".$_SESSION['pageID'];
        $res = mysqli_query($db, $sql);
        if ($des = mysqli_fetch_array($res))
        {
            echo "<div class='round'>__".$des['description']."</div>";
            echo "<hr>";
        }
    }
}