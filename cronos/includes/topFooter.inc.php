<?php
    // Header for top level
    // echo "</div>";
    ?>
        <div class='footer'>
            <hr>
            <div style='color:#eeeeee;font-size:small;'>
<?php
            if ($textID > 0 )
            {
                $sql = "select description from crono_texts where IDtext = ".$textID." and lang = '$lang'";
                $result = mysqli_query($db, $sql);
                while ($row = mysqli_fetch_array($result)) 
                {
                    echo $row['description']."<br><br>";
                }
            }
?>                      
            </div>
            <div class='toBottom'>
                <div id='localMenu'></div>
                <a href='main.php'><?php echo locale("strHome")?></a>&nbsp;|&nbsp; 
                <a href='navigation.php'><?php echo locale("strNavigation")?></a>
            </div>
            <div>
                <br>&copy; Xavi Peña, <?php echo date("Y")?>
            </div>
        </div>
    </main>
<script>
    SetLoader('<?php echo $element?>');
</script>
</body>
</html>