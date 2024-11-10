<?
// Connection to piwigo Galleries / Mobil
// ---------------------------------------------------
ini_set('display_errors',1); 
error_reporting(E_ALL);

$pw2 = new mysqli('qaia000.diaridigital.net','qaia000','Piwigo17','qaia000');
if ($pw2->connect_errno) 
{
    printf("Connect failed: %s\n", $pw2->connect_error);
    exit();
}
mysqli_set_charset($pw2, 'utf8');
?>
