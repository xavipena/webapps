<?php
if (empty($lang)) {
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        if ($lang !='es' && $lang !='en' && $lang !='ca') $lang ='es';
	$_SESSION['idioma'] =$lang;
}
$lang_file = "../includes/lang/".$lang.".inc.php";

require_once $lang_file;

function locale($key) 
{
	global $_LOCALE;
	$retorn = "#No trobat";
	if (isset($_LOCALE[$key])) $retorn = $_LOCALE[$key];
	return $retorn;
}
?>