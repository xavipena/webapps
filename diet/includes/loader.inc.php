<?php 
    switch ($loaderType) {

        case DRAGONFLY:

            echo "<div id='loading-wave'>";
            echo "  <div class='loading-bar'></div>";
            echo "  <div class='loading-bar'></div>";
            echo "  <div class='loading-bar'></div>";
            echo "  <div class='loading-bar'></div>";
            echo "</div>";
            $element = "loading-wave";
            break;

        case BLACK_HOLE:

            echo "<div id='loading'>";
            echo "    <div class='spinner'>";
            echo "        <div class='spinner1'></div>";
            echo "    </div>";
            echo "</div>";
            $element = "loading";
            break;
    }
?>
