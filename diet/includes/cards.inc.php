<?php
/* 
    Card functions
    CoverCard($url, $logo, $name, $description)
    $url            -> target
    $logo           -> image sufix
    $name           -> option name
    $description    -> option description
*/
function CoverCard($url, $logo, $name, $description, $class = "") {

    if ($class != "") {
        
        $class = " ".$class;
    }
    echo "<div class='coverCard$class'>";
    echo "   <a href='".$url."'>";

    echo "   <div class='cardCircle'>";
    echo "       <div class='cardTitle'>";
    echo "          ".$logo;
    echo "       </div>";
    echo "   </div>";
    
    echo "   </a><br>";
    echo "   <a href='".$url."'><h2>".$name."</h2></a><br>";
    echo "   <span class='coverCardText'>".$description."</span>";
    echo "</div>".PHP_EOL;
}

/* 
    TextCard($author, $quote, $text)
    $author         -> author
    $quote          -> quote text
    $text           -> description
*/
function TextCard($author, $quote, $text) {

    echo "<div class='coverCard'>";

    echo "   <div class='cardCircle'>";
    echo "       <div class='cardTitle'>";
    echo "          Cita";
    echo "       </div>";
    echo "   </div>";
    
    echo "   <br>";
    echo "   <h3>$author</h3>";
    echo "   <span class='coverCardText'>$quote</span>"; 
    echo "   <br><br>"; 
    echo "   <span class='subtitleText'>$text</span>";
    echo "</div>".PHP_EOL;
}

/* 
    PlainTextCard()
    $text          -> text
    $url           -> external link
*/
function PlainTextCard($c, $text, $url) {

    echo "<div class='textCard'>";

    echo "   <h3>$c</h3>";
    echo "   <span class='coverCardText'>$text</span>"; 
    echo "   <br>"; 
    echo "   <span class='subtitleText'><a href='$url' target='_blank' rel='nofollow'>Font</a></span>";
    echo "</div>".PHP_EOL;
}

/* 
    ExplainCard()
    $row            -> buffer
*/
function ExplainCard($text1, $text2) {

    echo "<div class='textCard mite'>";
    echo "   <h3>Mite</h3>";
    echo "   <span class='coverCardText'>$text1</span>"; 
    echo "</div>".PHP_EOL;

    echo "<div class='textCard expl'>";
    echo "   <h3>Explicació</h3>";
    echo "   <span class='coverCardText'>$text2</span>"; 
    echo "</div>".PHP_EOL;
}

/* 
    Card functions
    AddCard($url, $prefix, $name, $description)
    $url            -> target
    $prefix         -> image prefix
    $name           -> option name
    $description    -> option description
    $hasArrow       -> show arrow to next card
    $loader         -> show loader when calling url
    $isMenu         -> menus are drawn with different color
    $disabled       -> show card as disabled
*/
function AddCard($url, $prefix, $name, $description, $hasArrow = false, $loader = false, $isMenu = false, $disabled = false) {

    $image = "./images/".$prefix."_logo.png";
    
    if ($disabled) {
        
        $class = "round_disabled";
    } else {
        
        $class = $isMenu ? "round_menu" : "round_transparent";
    }

    if ($loader) {

        // set loader element visible dependin on selected type
        $element = "loading";
        switch ($GLOBALS['loaderType']) {

            case BLACK_HOLE:

                $element = "loading";
                break;
    
            case DRAGONFLY:
    
                $element = "loading-wave";
                break;
        }
        $url = "javascript:CallURL(\"$element\", \"".trim($url)."\")";
    }

    echo "<div class='card'>";
    echo "   <div class='$class'>";
    echo "      <div style='height:110px; padding:5px;'>";
    echo "          <table cellpadding='5px'><tr><td>";
    if (!$disabled) echo "<a href='$url'>";
    echo "                  <img width='70px' src='$image'>";
    if (!$disabled) echo "</a>";
    echo "          </td><td>";
    if (!$disabled) echo "<a href='$url'>".$name."</a>"; 
    else echo             $name;
    echo "              <br><span class='subtitleText'>$description</span>";
    echo "          </td></tr></table>";
    echo "      </div>";
    echo "   </div>";
    if ($hasArrow) 
    {
        echo "<div class='next'><img title='Següent' src='./images/arrow.png'></div>";
    }
    echo "</div>".PHP_EOL;
}

/* 
    Card functions
    AddMealCard($url, $id, $name, $description, $cnt)
    $url            -> target
    $id             -> image id in meal_$id.png
    $name           -> option name
    $description    -> option description
    $cnt            -> red if zero
*/
function AddMealCard($url, $prefix, $id, $name, $description, $cnt) {

    $image = GetImage($prefix, $id);
    $inRed = $cnt == 0 ? "redSubtitle" : ""; 

    $unit = $prefix == "mix" ? "peça" : "productes";

    echo "<div class='card'>";
    echo "   <div class='round_transparent'>";
    echo "      <div style='height:110px; padding:5px;'>";
    echo "          <table cellpadding='5px'><tr><td>";
    echo "              <a href='".$url."'>";
    echo "                  <img class='ilogo' src='$image'>";
    echo "              </a>";
    echo "          </td><td>";
    echo "              <a href='".$url."'>".$name."</a><br><span class='subtitleText $inRed'>".$description."<br>$cnt $unit</span>";
    echo "          </td></tr></table>";
    echo "      </div>";
    echo "   </div>";
    echo "</div>".PHP_EOL;
}

/* 
    Card functions for app
    AddAppSelectCard($url, $id, $name, $description, $cnt)
    $id             -> image id in meal_$id.png
    $name           -> option name
    $description    -> option description
*/
function AddAppSelectCard($prefix, $id, $name, $description) {

    $imagePath = "../images/"; 
    $fullname  = $imagePath.$prefix."_".$id;
    $image = $fullname.".jpg";
    if (!file_exists($image)) {

        $image = $fullname.".png";
        if (!file_exists($image)) {

            $image = $fullname.".webp";
            if (!file_exists($image)) {

                $image = $imagePath."notfound.png";
            }
        }
    }

    echo "<div class='cardShort'>";
    echo "   <div class='round_transparent'>";
    echo "       <table><tr><td>";
    echo "           <img class='ilogo' src='$image'>";
    echo "       </td><td>";
    echo "           $name<br><span class='subtitleText'>".$description."<br><input type='checkbox' name='checks' value='$id'> Selecciona</span>";
    echo "       </td></tr></table>";
    echo "   </div>";
    echo "</div>".PHP_EOL;
}

/* 
    Card functions
    AddSelectCard($url, $id, $name, $description, $cnt)
    $id             -> image id in meal_$id.png
    $name           -> option name
    $description    -> option description
*/
function AddSelectCard($prefix, $id, $name, $description) {

    $image = GetImage($prefix, $id);

    echo "<div class='cardShort'>";
    echo "   <div class='round_transparent'>";
    echo "      <div style='height:90px; padding:5px;'>";
    echo "          <table cellpadding='5px'><tr><td>";
    echo "                  <img class='ilogo' src='$image'>";
    echo "          </td><td>";
    echo "              $name<br><span class='subtitleText'>".$description."<br><input type='checkbox' name='checks' value='$id'></span>";
    echo "          </td></tr></table>";
    echo "      </div>";
    echo "   </div>";
    echo "</div>".PHP_EOL;
}

/* 
    Card functions ** testing version
    AddCard($url, $prefix, $name, $description)
    $id             -> file id in diagram_$id.txt
    $url            -> target
    $prefix         -> image prefix
    $name           -> option name
    $description    -> option description
    $hasArrow       -> show arrow to next card
*/
function DiagramCard($id, $prefix, $name, $description, $hasArrow = false) {

    $image = GetImage($prefix, "logo");
    
    echo "<div class='card'>";
    echo "   <div class='round_transparent'>";
    echo "      <div style='height:110px; padding:5px;'>";
    echo "          <table cellpadding='5px'><tr><td>";
    echo "              <img width='70px' src='$image'>";
    echo "          </td><td><a href='xDietDiagrams_1.php?file=$id'>$name</a><br><span class='subtitleText'>".$description."</span>";
    echo "          </td></tr></table>";
    echo "      </div>";
    echo "   </div>";
    if ($hasArrow) 
    {
        echo "<div class='next'><img title='Següent' src='./images/arrow.png'></div>";
    }
    echo "</div>".PHP_EOL;
}

/* 
    BigNUmberCard($num)
    $num    -> number
    $text   -> text
*/
function BigNUmberCard($num, $text) {

    $rowStart = $GLOBALS['rowStart'];
    $rowEnd   = $GLOBALS['rowEnd'];
    $newCol   = $GLOBALS['newCol'];

    if (empty($text)) {

        echo "<div class='cardSqr'>";
        echo "   <div class='round_transparent tcenter'>";
        echo        "<span style='font-size: 80pt; font-weight: bold;'>$num</span>";
        echo "   </div>";
        echo "</div>".PHP_EOL;
    }
    else {

        echo "<div class='cardNumber'>";
        echo "   <div class='round_transparent'>";
        echo        "<table width='100%''>".$rowStart;
        echo        "<span style='font-size: 80pt; font-weight: bold;'>$num</span>";
        echo        $newCol."<div class='textContainer'><p class='textCentered'>$text</p></div>";
        echo        $rowEnd."</table>";
        echo "   </div>";
        echo "</div>".PHP_EOL;
    }
}

/* 
    Card functions ** testing version
    AddTestCard($url, $prefix, $name, $description)
    $url            -> target
    $prefix         -> image prefix
    $name           -> option name
    $description    -> option description
    $loader         -> show loader when calling url
*/
function AddTestCard($url, $prefix, $name, $description, $items = 0, $loader = false) {

    $image = GetImage($prefix, "logo");
    
    if ($loader) {

        // set loader element visible dependin on selected type
        $element = "loading";
        switch ($GLOBALS['loaderType']) {

            case BLACK_HOLE:

                $element = "loading";
                break;
    
            case DRAGONFLY:
    
                $element = "loading-wave";
                break;
        }
        $url = "javascript:CallURL(\"$element\", \"".trim($url)."\")";
    }

    echo "<div class='card'>";
    echo "   <div class='round_transparent'>";
    echo "      <div style='height:110px; padding:5px;'>";
    echo "          <table cellpadding='5px'><tr><td>";
    echo "              <a href='".$url."'>";
    echo "                  <img width='70px' src='$image'>";
    echo "              </a>";
    echo "          </td><td>";
    echo "              <a href='".$url."'>".$name."</a><br>".
         "              <span class='subtitleText'>".$description;
                        if ($items > 0) echo "<br>".$items. " items".
         "              </span>";
    echo "          </td></tr></table>";
    echo "      </div>";
    echo "   </div>";
    echo "</div>".PHP_EOL;
}

/*
    PrintProduct($prefix, $row, $item, $value)
    params
        $prefix -> image prefix
        $row    -> current db record
        $item   -> 
        $value  -> 
    return
        --> product box
 */
function PrintProduct($prefix, $row, $item = "", $value = "") {
        
    $product = "<div class='productCard'>";
    $product .= "<table><tr>";
    $product .= "<td><a href='xDietProductFull.php?prd=".$row['pID']."'><img class='product' src='".getImage($prefix, $row['pID'])."'></a></td>";
    $product .= "<td>ID: ".$row['pID']."<br><a href='xDietProductFull.php?prd=".$row['pID']."'>".$row['pname']."</a>".
                "<br>".$row['bname'];
    if ($item == "")
    {
        if (isset($_SESSION['diet_user'])) 
        {
            if ($_SESSION['diet_user'] != "") 
            {
                $product .= "<br><br><a href='xDietSelectionAdd.php?prd=".$row['pID']."'>Selecciona</a>";
            }
        }
        //echo "<br><br>".$row['pdesc'];
    }
    else
    {
        $product .= "<br><br>$item: ".$value."g";
    }
    $product .= "</td></tr></table></div>".PHP_EOL;

    return $product;
}

/*
    PrintBoxProduct($row)
    params
        $row -> current db record
    return
        --> product box
 */
function PrintBoxProduct($row) {
        
    $output =   "<div class='tooltip'><a href='xDietProductFull.php?prd=".$row['pID']."'>".
                "<img class='product' src='".getImage("diet", $row['pID'])."'></a>".
                "<span class='tooltiptext'>".$row['pname']."</span></div>".PHP_EOL;
    return $output;
}

/*
    PrintBoxForSelection($row)
    params
        $row -> current db record
    return
        --> product box
 */
function PrintBoxForSelection($row) {
        
    $id = empty($row['pID']) ? $row['IDproduct'] : $row['pID'];
    $nm = empty($row['pname']) ? $row['name'] : $row['pname'];

    $output =   "<div class='tooltip'><a href='xDietCompare_1.php?prd=$id'>".
                "<img class='product' src='".getImage("diet", $id)."'></a>".
                "<span class='tooltiptext'>$nm</span></div>".PHP_EOL;
    return $output;
}