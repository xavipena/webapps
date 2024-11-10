<?php
// $textID
?>
<script>
    function AddDescription()
    {
        var elemDiv = document.createElement('div');
        elemDiv.style.cssText = '<?php echo $divDescription?>';
<?php
        $sql = "select description from crono_texts where IDtext = ".$textID." and lang = '$lang'";
        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_array($result)) 
        {
            echo "elemDiv.innerHTML = '".$row['description']."'";
        }
?>        
        document.body.appendChild(elemDiv);
    }
</script>
