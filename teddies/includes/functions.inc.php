<?php
/* 
    ------------------------------------------------------------------------
        Ted functions
    ------------------------------------------------------------------------
        GetTedType($id)
        $id             -> type index
*/
function GetTedType($id) {

    global $database;
    $sql = "select * from ted_types where IDtype = $id";
    $result = mysqli_query($database, $sql);
    if ($row = mysqli_fetch_array($result)) {
    
        return $id > 0 ? $row['tname'] : "<span style='color: red;'>".$row['tname']."</span>";
    } 
    else {
        
        return "<span style='color: red;'>Desconegut</span>";
    }
}

/* 
    ------------------------------------------------------------------------
        Card functions
    ------------------------------------------------------------------------
        CoverCard($url, $logo, $name, $description)
        $url            -> target
        $logo           -> image sufix
        $name           -> option name
        $description    -> option description
*/
function CoverCard($url, $logo, $name, $description) {

    echo "<div class='coverCard'>";
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
        LinkCard($id, $url, $name, $description)
        $id             -> image index
        $url            -> target
        $name           -> title
        $description    -> option desciption
*/
function LinkCard($id, $url, $name, $description) {

    $img = "./images/link_ted_$id.png";
    $image = "<img class='linkImage' src='$img'>";

    echo "<div class='coverCard'>";
    echo "   <a href='".$url."'>";
    echo "          ".$image;
    echo "   </a>";
    echo "   <div class='cardTitle'>$name</div>";    
    echo "   <span class='coverCardText'>".$description."</span>";
    echo "</div>".PHP_EOL;
}

/* 
        showActionCard($name, $page, $text)
        $name           -> title
        $page           -> target URL
        $text           -> option desciption
*/
function showActionCard($name, $page, $text) {

    $image = "./images/edit_logo.jpg";

    echo "<div class='card'>";
    echo "   <div class='round_transparent'>";
    echo "      <div style='height:110px; padding:5px;'>";
    echo "          <table cellpadding='5px'><tr><td>";
    echo "              <a href='$page'>";
    echo "                  <img class='cardImageRound' src='$image'>";
    echo "              </a>";
    echo "          </td><td>";
    echo "              $name<br><br><span class='smallText'>$text</span>";
    echo "          </td></tr></table>";
    echo "      </div>";
    echo "   </div>";
    echo "</div>";
}

/* 
        teddyID($row)
        $row            -> teddie record
*/
function teddyID($row) {

    $rowStart = "<tr><td>";
    $rowEnd = "</td></tr>";
    $newCol = "</td><td>";

    $path = "./images/";
    $image = $path."ted_".$row['IDted'].".jpg";
    if (!file_exists($image)) $image = $path."no_image.png";
    
    echo "<div class='DIPcard'>";
    echo "   <div class='DIPbackground'>";
    echo "      <div class='DIPtitle'>Document Identificació Peluix</div>";
    echo "          <table cellpadding='5px'>$rowStart";
    echo "              <img class='DIPimage' src='$image'>";
    echo "          $newCol";
    
    echo "          <table cellpadding='5px'>";
    echo "              ".$rowStart."Nom.......".$newCol.$row['name'].$rowEnd;
    echo "              ".$rowStart."Neixament.".$newCol.$row['adquiredDate'].$rowEnd;
    echo "              ".$rowStart."Origen....".$newCol.$row['origin'].$rowEnd;
    echo "              ".$rowStart."Tipus.....".$newCol.GetTedType($row['IDtype']).$rowEnd;
    echo "          </table>";
    
    echo "          $rowEnd";
    echo "          <tr><td class='DIPnotes' colspan='2'>**Si em perdo trucar al 667 123 678**".$rowEnd;
    echo "          </table>";
    echo "   </div>";
    echo "   <div class='DIPstamp'><img class='DIPstampImage' src='./images/stamp.png'></div>";
    echo "</div>";
}

/* 
    ------------------------------------------------------------------------
    Misc functions
    ------------------------------------------------------------------------
    countOrfan()
    return
        - number of orfan images, not assigned to teddie record
*/
function countOrfan() {

    $c = 0;
    $path = "./images/";
    if (file_exists($path)) {

        $files = array_slice(scandir($path), 2);

        // display on page
        foreach ($files as $img) 
        {
            if (substr($img, 0, 4) == "IMG_") {

                $c += 1;
            }
        }  
    }
    return $c;
}
?>