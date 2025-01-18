<?php 
/* 
  Side menu with hover
  Include 1 / 3
  css and js
*/
if ($isMob || $isTab) 
{
  // Do nothing
}
else
{
?>
<!-- ++ SideMenu -->
<link href='../css/css.css' rel='stylesheet'>
<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script type="text/javascript" src="../js/js.js"></script>
<!-- -- SideMenu -->
<?php
}

// ------------------------------------------
// Part from googleHeader.inc.php
// To allow css hierarchy override side menu styles
// ------------------------------------------
include __DIR__."/googleHeader_2.inc.php";
?>