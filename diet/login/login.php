<?php 
	include "./includes/config.inc.php";
	include "./includes/server.php"; 
	$class = "cont";
	if (isset($_POST['reg_user'])) {

		$class = "cont s--signup";
	}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Registre</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="./css/login.css">
	<link href="https://fonts.googleapis.com/css?family=Niramit" rel="stylesheet">

	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.3/animate.min.css">
</head>
<body>
	<div class="<? echo $class?>">
		
		<form method="post" action="login.php">
			<?php 
			if (isset($_POST['login_user'])) {
			
				$error_id = "err_in";
				include "./includes/errors.php";
			} 
			?>

			<div class="form sign-in">
				<div class="row">
					<h2><?php echo locale("strLogInTitle")?></h2>
					<div class="col-md-12">
						<label>
							<span><?php echo locale("strUser")?></span>
							<input type="text" name="username" />
						</label>
					</div>
					<div class="col-md-12">

						<label>
							<span><?php echo locale("strPassword")?></span>
							<input type="password" name="password" />
						</label>
					</div>
					<p class="forgot-pass"><?php echo locale("strForgotPass")?></p>
					<button type="submit" name="login_user" class="submit"> <?php echo locale("strStartSession")?> </button>
				</div>
			</div>
		</form>
		<div class="sub-cont">
			<div class="img">
				<div class="img__text m--up">
					<h2><?php echo locale("strNoUser")?></h2>
					<p><?php echo locale("strRegister")?></p>
				</div>
				<div class="img__text m--in">
					<h2><?php echo locale("strHasUser")?></h2>
					<p><?php echo locale("strHasAccount")?></p>
				</div>
				<div class="img__btn">
					<span class="m--up"><?php echo locale("strSignUp")?></span>
					<span class="m--in"><?php echo locale("strStartSession")?></span>
				</div>
			</div>
			<form method="post" action="login.php">
			<?php 
			if (isset($_POST['reg_user'])) {
			
				$error_id = "err_up";
				include "./includes/errors.php";
			} 
			?>
				<div class="form sign-up">
					<h2><?php echo locale("strNewUser")?></h2>
					<label>
						<span><?php echo locale("strUser")?></span>
						<input type="text" name="username" value="<?php echo isset($username) ? $username : ''; ?>" />
					</label>
					<label>
						<span><?php echo locale("strEmail")?></span>
						<input type="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" />
					</label>
					<label>
						<span><?php echo locale("strPassword")?></span>
						<input type="password" name="password" />
					</label>
					<button type="submit" class="submit" name="reg_user"><?php echo locale("strSignUp")?></button>
				</div>
			</form>
		</div>
	</div>
	</a>
	<script>
		document.querySelector('.img__btn').addEventListener('click', function () {
			document.querySelector('.cont').classList.toggle('s--signup');
		});
	</script>

</body>
</html>