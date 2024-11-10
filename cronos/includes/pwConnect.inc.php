<?
// Connection to piwigo Events / Running
// ---------------------------------------------------
ini_set('display_errors',1); 
error_reporting(E_ALL);

$pw = new mysqli('qahz999.diaridigital.net','qahz999','Piwigo18','qahz999');
if ($pw->connect_errno) 
{
    printf("Connect failed: %s\n", $pw->connect_error);
    exit();
}
mysqli_set_charset($pw, 'utf8');
?>
