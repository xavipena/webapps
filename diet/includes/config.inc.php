<?php
// -----------------------------
// Definició de constants de app
// -----------------------------

define ('LANG_PATH',                'includes/lang/');
define ('LANG_DEFAULT',             'es');
define ('YES',                      'Y');
define ('NO',                       'N');

// --------------------
// Configuration
// --------------------

define ('WEBCODE',                  'PHA');
define ('WEBSITE_NAME',             'diaridigital');
define ('WEBMASTER_EMAIL',          'xavipena@gmail.com');
define ('WEB_URL',                  'https://diaridigital.net');

// -----------------------------
// Definició de pàgines (left menu)
// -----------------------------

define ('PAGE_DIAGRAMS',    1 );
define ('PAGE_QUOTES',      2);
define ('PAGE_STORIES',     4 );
define ('PAGE_WLIST',       5 );
define ('PAGE_IMAGE',       6 );
define ('PAGE_SOFTWARE',    10);
define ('PAGE_SAGAS',       11);
define ('PAGE_PIANO',       16);
define ('PAGE_SMEDIA',      17); 
define ('PAGE_DIET',        19);
define ('PAGE_CSJ',         20);
define ('PAGE_CHKLISTS',    21);
define ('PAGE_PROJECTS',    22);
define ('PAGE_ELECTRIC',    23); 
define ('PAGE_BLOGS',       24); 
define ('PAGE_COLLECTIONS', 26);
define ('PAGE_SETTINGS',    27);
define ('PAGE_PLANS',       28);
define ('PAGE_SPACEX',      29); 
define ('PAGE_HOME',        30);
define ('PAGE_CRONOS',      33);
define ('PAGE_TEDDIES',     34);
define ('PAGE_DATA',        36);
define ('PAGE_SCIFI',       37);
define ('PAGE_MAGAZINE',    38);
define ('PAGE_DEV',         39);
define ('PAGE_BLOCS',       40);
define ('PAGE_CHANNELS',    50);
define ('PAGE_APPS',        99);

define ('HOME_PAGE',        101);
define ('INNER_PAGE',       102);

// -----------------------------
// Definició de constants de conversió
// -----------------------------
define ('SAL_SODI',   2.54);
define ('MILIGRAMS',  1000);

// -----------------------------
// Pàgines
// -----------------------------
define ('AUDIT_1',  	1);
define ('AUDIT_2',  	2);
define ('AUDIT_3',  	3);
define ('AUDIT_4',  	4);

// -----------------------------
// Settings
// -----------------------------
define ('SET_DETAILS',  	1);
define ('SET_IN_PERIOD',  	4);

// -----------------------------
// Loaders
// -----------------------------
define ('BLACK_HOLE',  	1);
define ('DRAGONFLY',  	2);

// -----------------------------
// Form actions
// -----------------------------
define ('FORM_EDIT',  	"edit");
define ('FORM_ADD',  	"add");

// -----------------------------
// API provider
// -----------------------------
define ('API_FS',  		1); // FatSecret
define ('API_FDC',  	2); // Food Dtaa Central
define ('API_MDB',  	3); // The Meal DB

// -----------------------------
// API KEY
// -----------------------------
define ('API_KEY_2',  	"cta9ppbMcjfHFoDLNoZOfwiZeDVIwYPupbJoCvzr");

// -----------------------------
// localització
// -----------------------------
$lang = "";
if (isset($_SESSION["lang"])) {

	// si ja el tinc a la sessió, l'assigna
	$lang = $_SESSION["lang"];
}
if (isset($clean["lang"])) 
{
	// Si es forçá per parámetre get
	$lang = $clean["lang"];
	if ($lang !='es' && $lang !='ca') $lang ='es';
} 
if ($lang == "") {

	// Si no hi ha cap, agafa el del navegador
	$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	if ($lang !='es' && $lang !='ca') $lang ='es';
}
// Des el idioma
$_SESSION['lang'] = $lang;

// Locale

//$lang_file = "./includes/lang/".$lang.".inc.php";
$lang_file = "/lang/".$lang.".inc.php";

require_once __DIR__.$lang_file;

function locale($key) 
{
	global $_LOCALE;
	$retorn = "#No trobat";
	if (isset($_LOCALE[$key])) $retorn = $_LOCALE[$key];
	return $retorn;
}

// --------------------
// Config variables
// --------------------

$cookiesInUse = 'N';
$loaderType = DRAGONFLY;

// --------------------
// Useful variables
// --------------------

$rowStart       = "<tr><td>";
$rowStartSpan   = "<tr><td colspan='2'>";
$rowEnd         = "</td></tr>";
$newCol         = "</td><td>";
$newColNum      = "</td><td class='number'>";

$portrait  = 1;
$landscape = 0;
$ALIGN = $landscape;