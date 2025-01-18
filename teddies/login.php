<?php 
session_start();
$_SESSION['runner_id'] = "";
?>
<!DOCTYPE html>
<html lang="ca">
    <head>
        <meta charset="UTF-8">
        <title>Teddies</title>
        <link rel="stylesheet" href="./css/panda.css">
    </head>
    <body>
        <div class="panda">
        <div class="ear"></div>
        <div class="face">
            <div class="eye-shade"></div>
            <div class="eye-white">
            <div class="eye-ball"></div>
            </div>
            <div class="eye-shade rgt"></div>
            <div class="eye-white rgt">
            <div class="eye-ball"></div>
            </div>
            <div class="nose"></div>
            <div class="mouth"></div>
        </div>
        <div class="body"> </div>
        <div class="foot">
            <div class="finger"></div>
        </div>
        <div class="foot rgt">
            <div class="finger"></div>
        </div>
        </div>
        <form id="panda" method="post" action="javascript:doSubmit()">
            <div class="hand"></div>
            <div class="hand rgt"></div>
            <h1>Els meus teddies</h1>
            <div class="form-group">
                <input id="username" required="required" class="form-control"/>
                <label class="form-label">Usuari    </label>
            </div>
            <div class="form-group">
                <input id="password" type="text" required="required" class="form-control"/>
                <label class="form-label">Clau</label>
                <p id="amsg" class="alert">No és correcte..</p>
                <button class="btn">Entra </button>
            </div>
        </form>

        <script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
        <script  src="./js/panda.js"></script>

    </body>
</html>

