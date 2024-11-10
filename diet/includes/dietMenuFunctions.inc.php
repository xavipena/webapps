<?php
/* 
    Constants

    
    Functions

    MenuItem($href, $front)
    params
        $href   --> menu url
        $front  --> front text
    return
        --> Menu option
*/
function MenuItem($href, $front)
{
    $output = "<li><a href='$href'>".
                        $front.
                        "</a></li>";
    return $output;
}

/* 
    MenuItem3D($href, $front, $back, $isSelected)
    params
        $href       --> menu url
        $front      --> front text
        $back       --> hidden text
        $isSelected --> Boolean, option is selected
    return
        --> Menu option
*/
function MenuItem3D($href, $front, $back, $isSelected)
{
    // When option is selected, no front shown 
    $output = "<li><a href='$href' class='threed-item'>".
                $front.
                "<span aria-hidden='true' class='threed-item-box'>";
    if (!$isSelected) {
    $output .="   <span class='threed-front'>$front</span>";
    }
    $output .="   <span class='threed-back'>$back</span>".
                "</span>".
                "</a></li>";
    return $output;
}

/* 
    MenuList($db, $menuList, $selectedOption)
    params
        $db             --> database handler
        $menuList       --> list of menu options
        $selectedOption --> current menu option
    return
        --> Prints menu
*/
function MenuList($db, $menuList, $selectedOption)
{
    $sql = "select * from diet_menu where IDmenu in ($menuList) and status = 'A' and lang = '".$GLOBALS['lang']."'";
    $result = mysqli_query($db, $sql);
    while ($row = mysqli_fetch_array($result))
    {
        $front = $row['front'];
        $back = $row['back'];

        if ($row['isSelector'] == 1) $back = "Selector";
        if ($row['isMenu'] == 1) $back = "Menú";

        if ($front == "*")
        {
            if (isset($_SESSION['diet_user'])) {
                
                if ($_SESSION['diet_user'] != "") {
                    
                    $sql = "select name from diet_users where IDuser = ".$_SESSION['diet_user'];
                    $res = mysqli_query($db, $sql);
                    $front = mysqli_fetch_array($res)[0];
                }
            }
            else 
            {
                $front = "Cap usuari";
                $back = "Identifica't";
            }
        }
        if (($GLOBALS['isIPhone'] || $GLOBALS['isAndroid']) && !$GLOBALS['isTab']) {

            echo MenuItem($row['page'], $front);
        }
        else {
            $isSelected = $row['IDmenu'] == $selectedOption;
            echo MenuItem3D($row['page'], $front, $back, $isSelected);
        }
    }
}
