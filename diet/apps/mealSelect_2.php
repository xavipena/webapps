<?php
    session_start();
    include "../includes/dbConnect.inc.php";
    include "../includes/app.security.inc.php";
    include "../includes/app.header.inc.php";
?>
<script>
    function goSelection()
    {
        let form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", "mealSave_2.php");
        
        let j = 1;
        const check_names = document.getElementsByName("checks");
        for (let i = 0; i < check_names.length; i++) {
            
            if (check_names[i].checked) {
                
                let inputElement = document.createElement("input");
                inputElement.value = check_names[i].value;
                inputElement.name = "prod_" + j; j++;
                form.appendChild(inputElement);
                inputElement.type = "hidden";
            }
        }
        document.getElementsByTagName("body")[0].appendChild(form);
        form.submit();
    }
</script>
</head>
<body>
<?php 

//--- Settings ------------------------

if (empty($_SESSION["meal"]))
{
    $_SESSION["meal"] = $clean['dtMel'];
}
//--- new content ---------------------

//--- functions -----------------------

include "../includes/cards.inc.php";

//--- Content -------------------------

?>
    <div class='appPageContainer'>
        <header>
            <div class='cardCircle'>
                <div class='cardTitle'>
                Plats
                </div>
            </div>
        </header>
        <main>
            <input type="hidden" name="dtSource" value="2">
<?php
            $sql ="select * from diet_dishes order by name";
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($result))
            {
                AddAppSelectCard("meal", $row['IDdish'], $row['name'], "");
            }
?>
            <div class="sendButton">
                <input type="button" value="Desa-ho" class="app-button" onclick="goSelection()">
            </div>
        </main>
<?php 
    include "../includes/app.footer.inc.php"; 
?>
