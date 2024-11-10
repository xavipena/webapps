<?php
    session_start();
    include ".././includes/dbConnect.inc.php";
    
    $username = mysqli_real_escape_string($db, $_POST['dtUser']);
    $password = mysqli_real_escape_string($db, $_POST['dtPass']);

    if (empty($username)) {

        //array_push($errors, "Es requereix un usuari");
        header ("location: ./login.php?err=1");
    }
    if (empty($password)) {

        //array_push($errors, "Es requereix una contrasenya");
        header ("location: ./login.php?err=2");
    }
    $password = md5($password);
    $query = "select * from diet_users where name = '$username' and password = '$password' limit 1";
    $results = mysqli_query($db, $query);
    if ($row = mysqli_fetch_array($results)) {
        
        $_SESSION['diet_user'] = $row['IDuser'];
        $_SESSION['user_id'] = $row['IDuser'];
        $_SESSION['diet_period'] = 0;

        $query = "select IDperiod from diet_user_periods where IDuser = ".$row['IDuser']." order by IDperiod desc limit 1";
        $results = mysqli_query($db, $query);
        if ($row = mysqli_fetch_array($results)) {

            $_SESSION['diet_period'] = $row['IDperiod'];
        }

        $_SESSION['username'] = $username;
        $_SESSION['success'] = "Sessió iniciada";

        header('location: ./menu.php');
    } 
    else {

        //array_push($errors, "Combinació d'usuari i contrasenya errònia");
        header ("location: ./login.php?err=3");
    }
    echo "Ups!";
?>