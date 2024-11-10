
function getMessage(imc) 
{
    var result = "";
    if (imc == 0) result = "No es pot calcular";
    else if (imc < 18.5) result = "Pas baix";
    else if (imc < 25) result = "Pes normal";
    else if (imc < 30) result = "Sobrepes";
    else if (imc < 35) result = "Obessitat, tipus I";
    else if (imc < 40) result = "Obessitat, tipus II";
    else if (imc >= 40) result = "Obessitat, tipus III";
    else result = "Error";

    return result;
}

function getMessageIMLG(gen, imlg) {

    if (imlg == 0) return "No es pot calcular";

    var result = "";
    if (gen == "D") {

        if (imlg <= 14) result = "Baix";
        else if (imlg <= 16) result = "Normal";
        else if (imlg <= 18) result = "Bueno";
        else if (imlg <= 20) result = "Muy bueno";
        else if (imlg == 22) result = "Límite máximo";
    }
    else {
        if (imlg <= 18) result = "Baix";
        else if (imlg <= 20) result = "Normal";
        else if (imlg <= 22) result = "Bueno";
        else if (imlg <= 25) result = "Muy bueno";
        else if (imlg == 25) result = "Límite máximo";
    }
    return result;
}

function getMessagePGC(gen, age, pgc) 
{
    var result = "";

    if (pgc == 0 || age == 0) return "No es pot calcular";

    if (gen == "D") {

        if (age <= 39) {

            if (pgc < 16) result = "Baix";
            else if (pgc <= 28) result = "Saludable";
            else if (pgc <= 39) result = "Sobrepes";
            else result = "Obesitat";
        }
        else if (age <= 59) {

            if (pgc < 18) result = "Baix";
            else if (pgc <= 30) result = "Saludable";
            else if (pgc <= 40) result = "Sobrepes";
            else result = "Obesitat";
        }
        else if (age <= 79) {

            if (pgc < 20) result = "Baix";
            else if (pgc <= 32) result = "Saludable";
            else if (pgc <= 42) result = "Sobrepes";
            else result = "Obesitat";
        }
    }
    else {

        if (age <= 39) {

            if (pgc < 8) result = "Baix";
            else if (pgc <= 20) result = "Saludable";
            else if (pgc <= 25) result = "Sobrepes";
            else result = "Obesitat";
        }
        else if (age <= 59) {

            if (pgc < 11) result = "Baix";
            else if (pgc <= 22) result = "Saludable";
            else if (pgc <= 28) result = "Sobrepes";
            else result = "Obesitat";
        }
        else if (age <= 79) {

            if (pgc < 13) result = "Baix";
            else if (pgc <= 25) result = "Saludable";
            else if (pgc <= 30) result = "Sobrepes";
            else result = "Obesitat";
        }
    }

    return result;
}

function CalcBMI(cm, kg) {

    Meters = eval(cm) / 100;
    Kilos = eval(kg);
    Square = Meters * Meters;
    return Math.round(Kilos * 10 / Square) / 10;
}

/* 
    Obté les dades d'informació nutricional de producte
    @params i         -> producte
            type      -> tipus d'aliment
            container -> div on es mostraran les dades
    respon a pData
*/
function GetDetails(i, type) 
{
    GetDetailsEx(i, type, "pData");
}

function GetDetailsEx(i, type, container) 
{
    isSolid = type == "1";
    const existsDiv = document.getElementById(container);
   
    let url = "https://diaridigital.net/diet/xDiet.ws.php?action=getProd&prod=" + i;
    fetch (url) 
        .then ((response) => {
            if (response.ok) 
            {
                return response.json();
            } 
            else 
            {
                throw new Error("NETWORK RESPONSE ERROR");
            }
        })
        .then (data => {
            displayDetails(data, i, isSolid, container)
        })
        .catch ((error) => console.error("FETCH ERROR getProd:", error));
}

function displayDetails(jsdata, i, isSolid, container) 
{
    const headStyling = " class='dietcard' width=100%";
    const colStyling = " class='number'"

    const columnSTD = jsdata.product[0];
    const column100 = jsdata.product[1];

    const cocktailDiv = document.getElementById(container);
    cocktailDiv.innerHTML = "";
    cocktailDiv.innerHTML = "<table" + headStyling + "><thead><tr><th>key</th><th>per 100g</th><th>per ració</th></tr></thead><tbody id='table_" + i + "'></tbody></table>";

    const tbody = document.getElementById("table_" + i);
    tbody.innerHTML = "";
    var p = "";

    const getKIngredientsSTD = Object.keys(columnSTD);
    const getVIngredientsSTD = Object.values(columnSTD);

    const getVIngredients100 = Object.values(column100);

    var c = 1;
    for (let key in getKIngredientsSTD) 
    {
        unit = "g";
        switch (c)
        {
            case 1:
                unit = isSolid ? "g" : "ml";
                break;
            case 2:
                unit = "Kcal";
        }
        let value = getKIngredientsSTD[key];
        p += "<tr><td>" + value + "</td>";
        value = getVIngredientsSTD[key];
        p += "<td" + colStyling + ">" + value + " " + unit + "</td>";
        value = getVIngredients100[key];
        p += "<td" + colStyling + ">" + value + " " + unit + "</td>";
        p += "</tr>";

        c += 1;
    }
    tbody.insertAdjacentHTML("beforeend", p);
}

// Use of loader

function CallURL(element, url) {

    console.log("element: " + element);
    document.getElementById(element).style.display = "flex";
    location.href = url;
}

// help & Explain

function GetExplain(i, container, lang) {

    const existsDiv = document.getElementById(container);
   
    let url = "https://diaridigital.net/diet/xDiet.ws.php?action=getHelp&help=" + i + "&lang=" + lang;
    fetch (url) 
        .then ((response) => {
            if (response.ok) 
            {
                return response.json();
            } 
            else 
            {
                throw new Error("NETWORK RESPONSE ERROR");
            }
        })
        .then (data => {
            explainDetails(data, container)
        })
        .catch ((error) => console.error("FETCH ERROR GetExplain:", error));
}

function explainDetails(jsdata, container) {

    const texts = jsdata.help[0];

    const getVals = Object.values(texts);
    
    const explainDiv = document.getElementById(container);
    explainDiv.innerHTML = "";
    explainDiv.innerHTML = "<p>" + getVals[0] + "<br><br>" + getVals[1] + "</p>";

}

function explain(id) {

    document.getElementById("explainText").innerHTML = "Carregant...";
    var modal = document.getElementById("explainDiv");
    modal.style.display = "block";
    GetExplain(id, "explainText", "ca"); 
}

// Setup listener
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    
    if (document.body.contains(document.getElementById("explainDiv"))) {

        var modal = document.getElementById("explainDiv");
        if (event.target == modal) {

            modal.style.display = "none";
        }
    }
}