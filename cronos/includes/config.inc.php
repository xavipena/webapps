<?php
// --------------------
// Configuration
// --------------------

define ('WEBSITE_NAME',             'Crono');
define ('WEBSITE_URL',              'https://diaridigital.net/crono');
define ('WEBSITE_DOMAIN',			'diaridigital.net');
define ('WEBMASTER_EMAIL',          'xavipena@gmail.com');

// --------------------
// API
// --------------------

define('PIWIGO_GALLERIES',          'https://diaridigital.net/galleries/');
define('PIWIGO_GAL_API',            'https://diaridigital.net/galleries/ws.php');
define('PIWIGO_USER_LOGIN',         'ovelleta');
define('PIWIGO_USER_PWD',           'Fotos personals 2021');

define('PIWIGO_MOBIL',          	'https://diaridigital.net/mobil/');
define('PIWIGO_MOB_API',            'https://diaridigital.net/mobil/ws.php');

// --------------------
// Definitions
// --------------------

define ('SUBJECT',  1);
define ('YEAR',     2);
define ('MONTH',    3);
define ('COUNTRY',  4);
define ('CITY',     5);
define ('EVENT',    6);
define ('PERSON',   7);
define ('GROUP',    8);
define ('PHOTO',    9);
define ('DETAIL',   10);
define ('TYPE',     11);

// --------------------
// Status
// --------------------

define ('LOADED',   "A");
define ('RELOAD',   "B");

// --------------------
// Selection
// --------------------

define ('FILTERING',  0);
define ('TAGGING',    1);

// --------------------
// Steps
// --------------------

define ('NONE',  	  0);
define ('SELECTION',  1);
define ('ASSIGN',     2);
define ('SAVE',       3);

// --------------------
// Popup types
// --------------------

define ('DIMENSION',  1);
define ('FILTERS',    2);

// --------------------
// Paging
// --------------------

define ('PER_PAGE',   20);

// --------------------
// Other
// --------------------

define ('HIGH_VALUE',   99999);
define ('HASH_EMPTY',   "0000000000");
define ('YES',   		'Y');
define ('NO',   		'N');

// -----------------------------
// Loaders
// -----------------------------
define ('BLACK_HOLE',  	1);
define ('DRAGONFLY',  	2);

// --------------------
// Table variables
// --------------------

$rowStart = "<tr><td>";
$rowStartSpan = "<tr><td colspan='2'>";
$rowEnd = "</td></tr>";
$newCol = "</td><td>";

// --------------------
// General variables defaults
// --------------------

$textID = 0;
$page = 0;
$leftMenu = true;
$showFilters = true;
$loaderType = DRAGONFLY;

// --------------------
// Div's definition
// --------------------

include __DIR__."/configDiv.inc.php";

// --------------------
// Sanitize input
// --------------------

$clean = array();
foreach(array_keys($_REQUEST) as $key)
{
	$clean[$key] = mysqli_real_escape_string($db, $_REQUEST[$key]);
}

// --------------------
// Access control
// --------------------

session_start();
if (empty($_SESSION['name'])) 
{
    header("location: https://diaridigital.net/private.php?sr=6");
}

// --------------------
// Set language
// --------------------

$lang = "";
if (isset($clean["lang"])) 
{
	if ($lang !='es' && $lang !='ca') $lang ='es';
	$lang = $clean["lang"];
} 
elseif (isset($_SESSION['language']))
{
	$lang = $_SESSION['language'];
	if ($lang !='es' && $lang !='ca') $lang ='es';
} 
else 
{
	$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	if ($lang !='es' && $lang !='ca') $lang ='es';
	$_SESSION['language'] =$lang;
}

// --------------------
// Locale
// --------------------

$lang_file = __DIR__."/lang/".$lang.".inc.php";
require_once $lang_file;

function locale($key) 
{
	global $_LOCALE;
	$retorn = "#No trobat";
	if (isset($_LOCALE[$key])) $retorn = $_LOCALE[$key];
	return $retorn;
}

?>