function gotoln(ilnk,lnk,pos)
{
        var parameters = "?ilnk="+ilnk+"&lnk="+lnk+"&pos="+pos;
        var sDialog ="x/setCounter.php";
        sDialog += parameters;
        var MessageWindow = window.frames["counter"];
        MessageWindow.location.href = sDialog;

}
function googleRemove()
{
    if (confirm("Esborra. Segur?")) {

        location.href = "googleRemove.php?pos="+arguments[0]+"&ln="+arguments[2];
    }
} 
function googleBad()
{
    location.href = "googleBad.php?pos="+arguments[1]+"&ln="+arguments[0];
} 
function googleTaskDone()
{
    if (confirm("Fet. Segur?")) {

        location.href="googleTaskDone.php?ln="+arguments[0];
    }
} 
function googleTaskPlus30()
{
    location.href="googleTaskDelay.php?ln="+arguments[0];
}
function googleMove(src,lnk,pos) { 
    
    $("#lgroups").toggle(); 
    
    document.getElementById("linkID").value = lnk;
    document.getElementById("source").value = src;
    document.getElementById("posID").value = pos;
    //return false; 
} 
function go() {

    document.getElementById(arguments[0]).submit();
}
// search and login forms made visibles
function search() {

    document.getElementById("sea").style.display="inline";
}
function showLogin() 
{
    document.getElementById("log").style.display="inline";
}
