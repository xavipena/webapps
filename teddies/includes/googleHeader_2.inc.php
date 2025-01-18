<?php
// ------------------------------------------
// Part from googleHeader.inc.php
// To allow css hierarchy override side menu styles
// ------------------------------------------

    echo "<link rel='stylesheet' type='text/css' href='./css/google.css' />";    
    echo "<link rel='stylesheet' type='text/css' href='./css/teddies.css' />";
    echo "<link rel='stylesheet' type='text/css' href='./css/style.css' />";
    echo "<link rel='stylesheet' type='text/css' href='./css/card.css' />";
    if ($cookiesInUse == YES) { 
?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@3.0.1/dist/cookieconsent.css">
<?php
    }
    if ($page == GALLERY_PAGE) { // galleries
?>
        <script
            src="https://code.jquery.com/jquery-3.2.1.js"
            integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-beta.2/lazyload.js"></script>
        <script src="js/transition.js"></script>
        <script src="js/zoom.min.js"></script>
        <script src="js/gallery.js"></script>
<?php  
    }
    else {

        echo "<script src='../js/jquery-3.3.1.min.js'></script>";
        echo "<script src='../js/google.js'></script>";
        if (file_exists("./js/$page.js")) {
            
            echo "<script src='./js/$page.js'></script>";
        }
    }    
?>