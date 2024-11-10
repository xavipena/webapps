<?
	ini_set('display_errors',1); 
	error_reporting(E_ALL);
 
    $db = new mysqli('qahz993.diaridigital.net','qahz993','photoBlog10','qahz993');
	if ($db->connect_errno) 
    {
		 printf("Connect failed: %s\n", $db->connect_error);
		 exit();
	 }
	 mysqli_set_charset($db, 'utf8');
 ?>
