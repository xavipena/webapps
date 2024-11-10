<?php  
if (count($errors) > 0) {

	echo "<div class='error' id='$error_id'>";
	foreach ($errors as $error) {

		echo "<div class='alert alert-danger' role='alert'>";
		echo $error;
		echo "</div>";		
	}
	echo "</div>";
}