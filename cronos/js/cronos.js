// ----------------------------
// This constant definition should be the same as config.inc.php
// ----------------------------
const SUBJECT   = 1;
const YEAR      = 2;
const MONTH     = 3;
const COUNTRY   = 4;
const CITY      = 5;
const EVENT     = 6;
const PERSON    = 7;
const GROUP     = 8;
const DETAIL    = 10;
const TYPE      = 11;

const DIMENSION = 1;
const FILTERS   = 2;

const FILTERING = 0;
const TAGGING   = 1;

const DIV_STATUS= 1;
const DIV_TAGS  = 2
const DEBUG     = false;

// ----------------------------
// Config
// ----------------------------
const host = "https://diaridigital.net/";
var loaderEl = "";

function SetLoader(elementID) {

    loaderEl = elementID;
}

// ----------------------------
// List to manage deleted items
// list.push({ photo: 4444 })
// ----------------------------
var list = [];
// ----------------------------

// ----------------------------
// Delete selected image from selection list
// image
// ----------------------------
function DelSelected(image) {

    let url = host + "ws/cronos.ws.php?action=delSelected&photo=" + image;
    if (DEBUG) console.warn(url);

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

            var aDiv = document.getElementById(image);
            // change class
            aDiv.classList.remove("item");
            aDiv.classList.add("itemDeleted");
            // change action
            var aLink = document.getElementById("a" + image);
            aLink.href = "javascript:RecoverSelected('" + image + "')";
            // Refresh after removal
            GetStatus("galleryStats", 1);
        })
        .catch ((error) => console.error("FETCH ERROR DelSelected:", error));
}

// ----------------------------
// Add selected image to selection list
// image
// ----------------------------
function RecoverSelected(image) {

    let url = host + "ws/cronos.ws.php?action=addSelected&photo=" + image;
    if (DEBUG) console.warn(url);

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

            var aDiv = document.getElementById(image);
            aDiv.classList.remove("itemDeleted");
            aDiv.classList.add("item");
            var aLink = document.getElementById("a" + image);
            aLink.href = "javascript:DelSelected('" + image + "')";
            // Refresh after recover
            GetStatus("galleryStats", 1);
        })
        .catch ((error) => console.error("FETCH ERROR AddSelected:", error));
}

// ----------------------------
// Add selected image to selection list
// image
// ----------------------------
function AddSelected(image) {

    let url = host + "ws/cronos.ws.php?action=addSelected&photo=" + image;
    if (DEBUG) console.warn(url);

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
//        .then (data => {
//               Update status
//        })
        .catch ((error) => console.error("FETCH ERROR AddSelected:", error));
}

// ----------------------------
// Get selection status 
// desDiv   - div to display data
// ----------------------------
function GetStatus(destDiv, where) {

    let url = host + "ws/cronos.ws.php?action=getStatus&where=" + where;
    if (DEBUG) console.warn(url);

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
            UpdateOnScreen(data, destDiv, DIV_STATUS);
        })
        .catch ((error) => console.error("FETCH ERROR AddSelected:", error));
}

// ----------------------------
// Get year and month:
// source ID
// format
//  -> table
//  -> list
// destination container
// selected, to highlight
// link, with link or not
//  -> true
//  -> false
// locale for All/None
// type 
//  -> FILTERING
//  -> TAGGING
// ----------------------------
function GetDates(id, format, destDiv, selected, link, locale, type) {
    
    if (format == null) format = "table";

    var value = "";
    var elementOpen  = "";
    var elementClose = "";
    var notSelected  = "";
    switch (format)
    {
        case "table":
            value += "<table><tr><td><a href='./background/setValue.php?id=" + id + "&val=0&type=" + type + "'>" + locale + "</a><br>";
            break;

        case "list":
            value += "<ul class='sliderList' id='uList" + id + "'>";
            elementOpen = "<li";
            elementClose = "</li>";
            break;

        case "block":
            value += "";
            elementOpen = "<div class";
            elementClose = "</div>";
            notSelected = " class='filterBlock'";
            break;
    }
    var a = 0;
    var c = 1;
    var current = "";
    switch (id)
    {
        case YEAR:
            for (a = 2002; a <= 2023; a++)
            {
                c++; 
                current = (a == selected) ? " class='selected'" : notSelected;
                switch (format)
                {
                    case "table":

                        if (link) {

                            value += "<a href='./background/setValue.php?id=" + id + "&val=" + a + "&type=" + type + "'>" + a + "</a><br>";
                        } else {
                            
                            value += a + "<br>";
                        }
                        if (c % 10 == 0) 
                        {
                            value += "</td><td>";
                        }
                        break;

                    default:

                        if (link) {

                            value += elementOpen + current + "><a href='./background/setValue.php?id=" + id + "&val=" + a + "&type=" + type + "'>" + a + "</a>" + elementClose;
                        } else {
                            
                            value += "<li" + current + ">" + a + elementClose;
                        }
                        break;
                }
            }
            break;
            
        case MONTH:
            var val = 0;
            for (a = 0; a < 12; a++)
            {
                const date = new Date(2023, a, 1);
                const month = date.toLocaleString('default', { month: 'short' });
                val = a + 1;
                current = (val == selected) ? " class='selected'" : "";
                c++; 
                switch (format)
                {
                    case "table":
                        
                        if (link) {

                            value += "<a href='./background/setValue.php?id=" + id + "&val=" + val + "&type=" + type + "'>" + month + "</a><br>";
                        } else {

                            value += month + "<br>";
                        }
                        if (c % 10 == 0) 
                        {
                            value += "</td><td>";
                        }
                        break;

                    default:

                        if (link) {

                            value += elementOpen + current + "><a href='./background/setValue.php?id=" + id + "&val=" + val + "&type=" + type + "'>" + month + "</a>" + elementClose;
                        } else {
                            
                            value += elementOpen + current + ">" + month + elementClose;
                        }
                        break;
                }
            }
            break;
    }
    switch (format)
    {
        case "table":
            value += "</td></tr></table>";
            break;
        case "list":
            value += "</ul>";
            break;
    }
    if (destDiv == "")
    {
        // Pop up to select filter
        SelectorPopup(value);
    }
    else
    {
        const cocktailDiv = document.getElementById(destDiv);
        cocktailDiv.innerHTML = value;
    }
}

// ----------------------------
// Get data via web service
// table
// fieldID
// fieldName
// link, with link or not
//  -> true
//  -> false
// format
//  -> table
//  -> list
// destination container
// selected value, to highlight
// locale for All/None
// type
//  -> FILTERING
//  -> TAGGING
// ----------------------------
function GetDetails(table, fieldID, fieldName, link, format, destDiv, selected, locale, type) {
    
    let url = host + "ws/cronos.ws.php?action=read&table=" + table + "&fieldin=" + fieldID + "&fieldout=" + fieldName;
    if (DEBUG) console.warn(url);

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
            if (link)
            {
                DisplayDetailsWithLink(data, table, format, destDiv, selected, locale, type);
            }
            else
            {
                DisplayDetails(data, format, destDiv, selected);
            }
        })
        .catch ((error) => console.error("FETCH ERROR getDetails:", error));
}

// ----------------------------
// Set value for a given filter
// param id
// value to set
// type
//  -> FILTERING
//  -> TAGGING
// ----------------------------
function SetValue(id, value, type) {

    // display loader
    const loader = document.getElementById(loaderEl);
    loader.style.display = "flex";

    
    let url = host + "ws/cronos.ws.php?action=setValue&id=" + id + "&val=" + value + "&type=" + type;
    if (DEBUG) console.warn(url);

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
            // dismiss popup
//            document.querySelector("body").style.visibility = "hidden";
//            document.querySelector("#loader").style.visibility = "visible";
            document.getElementById("popup-1").classList.toggle("active");
            document.location.href = 'GoBackToPage.php';
        })
        .catch ((error) => console.error("FETCH ERROR setValue:", error));
}

// ----------------------------
// Get values
// type
//  -> FILTERING
//  -> TAGGING
// ----------------------------
function GetValues(type) {

    let url = host + "ws/cronos.ws.php?action=getValues&type=" + type;
    if (DEBUG) console.warn(url);

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
            UpdateOnScreen(data, "daTags", DIV_TAGS);
        })
        .catch ((error) => console.error("FETCH ERROR getValues:", error));
}

// ----------------------------
// Functions to process received JSON data
// ----------------------------
// Display values with link to set value
// ----------------------------
function DisplayDetailsWithLink(jsdata, table, format, destDiv, selected, locale, type) {

    const Root = jsdata.result[0];
    const getKeys = Object.keys(Root);
    const getValues = Object.values(Root);

    if (format == null) format = "table";
    var tableID = SetTableID(table);

    var value = "";
    switch (format)
    {
        case "table":

            if (tableID == COUNTRY)
            {
                value += "<table><tr><td><a href='javascript:SetValue(" + tableID + ",\"\"," + type + ")'>" + locale + "</a><br><br>";
            } else {
                value += "<table><tr><td><a href='javascript:SetValue(" + tableID + ",0," + type + ")'>" + locale + "</a><br><br>";
            }
            break;

        case "list":

            //value += "<ul class='sliderList'>";
            value += "<ul class='sliderList' id='uList'>";
            break;
    }
    
    var c = 1;

    for (let key in getKeys) 
    {
        const ResultsFound = getValues[key][0]
        const getK = Object.keys(ResultsFound);
        const getV = Object.values(ResultsFound);
        
        if (c > 1) 
        {
            for (let key2 in getK) 
            {
                //current = (getK[key2] == selected) ? " class='current'" : "";
                current = (getK[key2] == selected) ? " class='selected'" : "";
                switch (format)
                {
                    case "table":

                        if (tableID == COUNTRY)
                        {
                            value += "<a href='javascript:SetValue(" + tableID + ",\"" + getK[key2] + "\"," + type + ")'>" + getV[key2] + "</a><br>";
                        } else {
                            value += "<a href='javascript:SetValue(" + tableID + "," + getK[key2] + "," + type + ")'>" + getV[key2] + "</a><br>";
                        }
                        if (c % 10 == 0) 
                        {
                            value += "</td><td>";
                        }
                        break;
                        
                    case "list":

                        if (tableID == COUNTRY)
                        {
                            value += "<li" + current + "><a href='javascript:SetValue(" + tableID + ",\"" + getK[key2] + "\"," + type + ")'>" + getV[key2] + "</a></li>";
                        } else {
                            value += "<li" + current + "><a href='javascript:SetValue(" + tableID + "," + getK[key2] + "," + type + ")'>" + getV[key2] + "</a></li>";
                        }
                        break;
                }
                c++;
            }
        }
        else c++; // skip this
    }
    switch (format)
    {
        case "table":
            value += "</td></tr></table>";
            break;
        case "list":
            value += "</ul>";
            break;
    }

    if (destDiv == null || destDiv == "")
    {
        // Pop up to select filter
        if (value != null) {
        
            SelectorPopup(value);
        }
    }
    else
    {
        const cocktailDiv = document.getElementById(destDiv);
        cocktailDiv.innerHTML = value;
    }
}

// ----------------------------
// Display values
// ----------------------------
function DisplayDetails(jsdata, format, destDiv, selected) {

    const Root = jsdata.result[0];
    const getKeys = Object.keys(Root);
    const getValues = Object.values(Root);
    
    if (format == null) format = "table";

    var value = "";
    switch (format)
    {
        case "table":
            value += "<table><tr><td>";
            break;
        case "list":
            value += "<ul class='sliderList'>";
            break;
    }

    var c = 1;

    for (let key in getKeys) 
    {
        const ResultsFound = getValues[key][0]
        const getK = Object.keys(ResultsFound);
        const getV = Object.values(ResultsFound);
        
        if (c > 1) 
        {
            for (let key2 in getK) 
            {
                //current = (getK[key2] == selected) ? " class='current'" : "";
                current = (getK[key2] == selected) ? " class='selected'" : "";
                switch (format)
                {
                    case "table":
                        value += getV[key2] + "<br>";
                        if (c % 10 == 0) 
                        {
                            value += "</td><td>";
                        }
                        break;
                        
                    case "list":
                        value += "<li" + current + ">" + getV[key2] + "</li>";
                        break;
                }
                c++;
            }
        }
        else c++; // skip this
    }

    switch (format)
    {
        case "table":
            value += "</td></tr></table>";
            break;
        case "list":
            value += "</ul>";
            break;
    }
    const cocktailDiv = document.getElementById(destDiv);
    cocktailDiv.innerHTML = value;
}

// ----------------------------
// Update status div
// ----------------------------
function UpdateOnScreen(jsdata, destDiv, div) {

    const Root = jsdata.result[0];
    const getKeys = Object.keys(Root);
    const getValues = Object.values(Root);

    var value = "";

    switch (div) {

        case DIV_STATUS:
            value = "<table width='100%'>";    
        
            for (let key in getKeys) 
            {        
                value += "<tr><td align='right' width='75%'>" + getKeys[key] + "</td><td align='right'>" + getValues[key] + "</td></tr>";
            }
            value += "</table>";
            break;

        case DIV_TAGS:

            value = "<table>";
            for (let key in getKeys) 
            {        
                value += "<tr><td><a href='javascript:GetDetails()'>" + getKeys[key] + "</a></td><td><span style='color:darkgray;'>" + getValues[key] + "</span></td></tr>";
            }
/*
            <tr><td><a href='javascript:GetDetails("crono_subjects", "IDsubject", "name",true,"table","","","Ninguno",1)'>Tema</a></td><td><span style='color:darkgray;'>Ninguno</span></td></tr>
            <tr><td><a href='javascript:GetDates(2, "table", "","","Ninguno",1)'>Año</a></td><td><span style='color:darkgray;'>Ninguno</span></td></tr>
            <tr><td><a href='javascript:GetDates(3, "table", "","","Ninguno",1)'>Mes</a></td><td><span style='color:darkgray;'>Ninguno</span></td></tr>
            <tr><td><a href='javascript:GetDetails("countries", "IDcountry", "name",true,"table","","","Ninguno",1)'>País</a></td><td><span style='color:darkgray;'>Ninguno</span></td></tr>
            <tr><td><a href='javascript:GetDetails("crono_cities", "IDcity", "name",true,"table","","","Ninguno",1)'>Ciudad</a></td><td><span style='color:darkgray;'>Ninguno</span></td></tr>
            <tr><td><a href='javascript:GetDetails("crono_events", "IDevent", "name",true,"table","","","Ninguno",1)'>Evento</a></td><td><span style='color:darkgray;'>Ninguno</span></td></tr>
            <tr><td><a href='javascript:GetDetails("crono_persons", "IDperson", "name",true,"table","","","Ninguno",1)'>Persona</a></td><td>Cris</td></tr>
            <tr><td><a href='javascript:GetDetails("crono_groups", "IDgroup", "name",true,"table","","","Ninguno",1)'>Grupo</a></td><td>Nosaltres</td></tr>
            <tr><td><a href='javascript:GetDetails("crono_details", "IDdetail", "name",true,"table","","","Ninguno",1)'>Detalle</a></td><td><span style='color:darkgray;'>Ninguno</span></td></tr>
            <tr><td><a href='javascript:GetDetails("crono_types", "IDtype", "name",true,"table","","","Ninguno",1)'>Tipo de foto</a></td><td><span style='color:darkgray;'>Ninguno</span></td></tr>
*/              
            value += "</table>";
            break;
    }

    const cocktailDiv = document.getElementById(destDiv);
    cocktailDiv.innerHTML = value;
}

// ----------------------------
// Get table name from ID
// ----------------------------
function SetTableID(tableName) {

    var tableID = 0;
    switch (tableName)
    {
        case "crono_subjects":
            tableID = SUBJECT;
            break;
        case "countries":
            tableID = COUNTRY;
            break;
        case "crono_cities":
            tableID = CITY;
            break;
        case "crono_events":
            tableID = EVENT;
            break;
        case "crono_persons":
            tableID = PERSON;
            break;
        case "crono_groups":
            tableID = GROUP;
            break;
        case "crono_details":
            tableID = DETAIL;
            break;
        case "crono_types":
            tableID = TYPE;
            break;
    }
    return tableID;
}

// ----------------------------
// For page calls with loader
// ----------------------------
function CallPage(element, url) {

    console.log("element: " + element);
    const abody = document.getElementById(element);
    abody.style.display = "flex";
    location.href = url;
};

// ----------------------------
// Popup for selector
// ----------------------------
function TogglePopup(photo, text) {

    var content = document.getElementById("selectorContent");
    text += "<br>Modificar<br>" +
            "<a href='add.php?photo=" + photo + "'>Actualitzar la foto</a><br>" +
            "<br>Nueva dimensión<br>" +
            "<a href='javascript:CallPage(\"$element\",\"navigation.php?dimension=time\")'>Temporal</a><br>" + 
            "<a href='javascript:CallPage(\"$element\",\"navigation.php?dimension=place\")'>Ubicacions</a><br>" + 
            "<a href='javascript:CallPage(\"$element\",\"navigation.php?dimension=people\")'>Persones</a><br>" + 
            "<br>"
            "Veure la dimensió sencera o filtrar-la per la info de creuament" +
            "</td><tr></table>";
            
    content.innerHTML = text;
    document.getElementById("popup-1").classList.toggle("active");
}

function SelectorPopup(text) {

    if (text == null || text == "") return;

    var content = document.getElementById("selectorContent");
    content.innerHTML = text;
    document.getElementById("popup-1").classList.toggle("active");
}
