<?php 
	session_start();
	// variable declaration
	$errors = array(); 
	$_SESSION['success'] = "";

	// connect to database
	include ".././includes/dbConnect.inc.php";

	// -------------------------
	// REGISTER USER
	// -------------------------

	if (isset($_POST['reg_user'])) {

		$today = date("Y-m-d");

		// receive all input values from the form
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$password = mysqli_real_escape_string($db, $_POST['password']);

		// form validation: ensure that the form is correctly filled
		if (empty($username)) { array_push($errors, locale("strErrUser")); }
		if (empty($email)) { array_push($errors, locale("strErrEmail")); }
		if (empty($password)) { array_push($errors, locale("strErrPassword")); }

		// register user if there are no errors in the form
		if (count($errors) == 0) {

			$query = "select IDuser from diet_users order by IDuser desc limit 1";
			$results = mysqli_query($db, $query);
			$idUsr = mysqli_fetch_array($results)[0];
			$idUsr += 1;

			$password = md5($password); 
			$query = "insert into diet_users set ".
					 " IDuser 		= ".$idUsr.
					 ",name   		='$username'".
					 ",target   	=''".
					 ",formula  	=''".
					 ",gender   	=''".
					 ",weight		= 0".
					 ",height		= 0".
					 ",age			= 0".
					 ",activity		=''".
					 ",dietType 	= 0".
					 ",basal    	= 0".
					 ",thermogenesis= 0".
					 ",exercise		= 0".
					 ",recommended 	= 0".
					 ",limited 		= 0".
					 ",sysdate		='$today'".
					 ",lossKg		= 0".
					 ",lossWeeks	= 0".
					 ",lossDiet		= 0".
					 ",lossperDay	= 0".
					 ",email  		='$email'".
					 ",password 	='$password'";
			if (mysqli_query($db, $query)) {

				$query = "insert into diet_user_periods set ".
						" IDuser 		= ".$idUsr.
						",IDperiod		= 1".
						",target   		=''".
						",formula  		=''".
						",weight		= 0".
						",height		= 0".
						",age			= 0".
						",activity		=''".
						",dietType 		= 0".
						",basal    		= 0".
						",thermogenesis	= 0".
						",exercise		= 0".
						",recommended 	= 0".
						",limited 		= 0".
						",sysdate		='$today'".
						",lossKg		= 0".
						",lossWeeks		= 0".
						",lossDiet		= 0".
						",lossperDay	= 0";
				if (mysqli_query($db, $query)) {

					$query = "insert into diet_periods set ".
							" IDuser 		= ".$idUsr.
							",IDperiod		= 1".
							",description	='".locale("strInsert_1")."'".
							",beginDate		='$today'".
							",endDAte		='".date('Y-m-d', strtotime("+3 months", strtotime($today)))."'";
					if (mysqli_query($db, $query)) {

						$sql = "select * from diet_user_settings where IDuser = 1";
						$result = mysqli_query($db, $sql);
						while ($row = mysqli_fetch_array($result)) 
						{
							$query = "insert into diet_user_settings set ".
									" IDuser 		= ".$idUsr.
									",IDsetting		= ".$row['IDsetting'].
									",value			='".$row['value']."'";
							if (mysqli_query($db, $query)) {
								
								$_SESSION['user_id'] = mysqli_insert_id($db);
								$_SESSION['diet_user'] = $_SESSION['user_id'];
								$_SESSION['username'] = $username;
								$_SESSION['diet_admin'] = 0;
								$_SESSION['success'] = "Estàs registrat";
								header('location: ../xDietCover.php');
							}
							else array_push($errors, locale("strErrAccount")." : settings");
						}
					}
					else array_push($errors, locale("strErrAccount")." : periods");
				}
				else array_push($errors, locale("strErrAccount")." : user periods");
			}
			else array_push($errors, locale("strErrAccount")." : users");
		}
	}

	// -------------------------
	// LOGIN USER
	// -------------------------

	if (isset($_POST['login_user'])) {

		$username = mysqli_real_escape_string($db, $_POST['username']);
		$password = mysqli_real_escape_string($db, $_POST['password']);

		if (empty($username)) {

			array_push($errors, locale("strErrUser"));
		}
		if (empty($password)) {

			array_push($errors, locale("strErrPassword"));
		}
		if (count($errors) == 0) {

			$password = md5($password);
			$query = "select * from diet_users where name = '$username' and password = '$password' limit 1";
			$results = mysqli_query($db, $query);
			if ($row = mysqli_fetch_array($results)) {
				
				$_SESSION['diet_user'] = $row['IDuser'];
				$_SESSION['user_id'] = $row['IDuser'];
				$_SESSION['diet_period'] = 0;
				$_SESSION['diet_admin'] = $row['isAdmin'];

				$query = "select IDperiod from diet_user_periods where IDuser = ".$row['IDuser']." order by IDperiod desc limit 1";
				$results = mysqli_query($db, $query);
				if ($row = mysqli_fetch_array($results)) {

					$_SESSION['diet_period'] = $row['IDperiod'];
					$_SESSION['diet_recom'] = $row['recommended'];
					$_SESSION['diet_limit'] = $row['limited'];
				}

				$_SESSION['username'] = $username;
				$_SESSION['success'] = locale("strSessionStarted");

				header('location: ../xDietUser_1.php');
			} 
			else {

				array_push($errors, locale("strErrAccess"));
			}
		}
	}
?>