<?php
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