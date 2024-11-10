function FS_searchProduct(element) {

    var frm = document.forms["searchf"].getElementsByTagName("input");
    var srch = frm[1].value;
    if (srch == "") return;
    if (element != "") {
    
        console.log("element: " + element);
        document.getElementById(element).style.display = "flex";
    }
    location.href = 'xDietAPI_3.php?srch=' + srch;
}