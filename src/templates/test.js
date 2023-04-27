const button = document.getElementById('preview-button');
const dropdown = document.getElementById('file-dropdown');
const previewHTML = document.getElementById('preview-html');
const previewPress = document.getElementById('preview-press');
const fileinputbox = document.getElementById('file-input-box');
const options = document.getElementById('options');
const loading = document.getElementById('loading');
const error = document.getElementById('error');
const commtable = document.getElementById('comm-table');

const datatype = document.getElementById('data-type');
const comm = ["State","City","First Name","Last Name", "Diploma Name", "Country","Division","Degree","Major 1","Major 2"];
const honorroll = ["State","City","First Name","Last Name"];
let json = {};
let sortedjson = {};


let statevalue = "";
let cityvalue = "";
let firstnamevalue = "";
let lastnamevalue = "";
let diplomanamevalue = ""
let countryvalue = "";
let divisionvalue = "";
let degreevalue = "";
let majoronevalue = "";
let majortwovalue = "";


let output = "";

let displayCode = true;

function checkDropdown() {

    if (dropdown.value == '') {
        return;
    }

    if (dropdown.value == 'new') {
        fileinputbox.style.display = "block";
    } else {
        fileinputbox.style.display = "none";
        preview(dropdown.value);
    }
}

button.addEventListener('click', function on(e) {
    let selectedFile = document.getElementById('file-input').files[0];
    //console.log(selectedFile);
    const params = new FormData();

    params.append('userId', button.dataset.userId);
    params.append(button.dataset.csrfname, button.dataset.csrfdata);
    params.append('folderId',1);
    params.append('fieldId',1);
    params.append('assets-upload',selectedFile);
    
    if (dropdown.value == 'new') {
        fetch('/actions/assets/upload/', {
            method: 'POST',
                headers: {
                'Accept': 'application/json',
            },
            body: params,
            }).then((response) => response.json())
            .then((data) => preview(data.filename));
    } else {
        preview(dropdown.value);
    }
    
});

function showTables() {
}

function copyToClipboard(value) {
    navigator.clipboard.writeText(value);
}

function preview(filename) {
    //console.log(filename);
    if (dropdown.value == '') {
        return;
    }
    loading.style.display = "block";
    options.style.display = "none";
    const params = new FormData();
    params.append('filepath', filename);
    try {
        fetch('/actions/registrar-importer-craft/default/convert?' + new URLSearchParams({
            path: '/var/www/web/assets/' + filename,
        }), {
        method: 'GET',
        }).then((response) => response.json())
            .then((data) => {
            //let dropdowns = [statedropdown,citydropdown,firstdropdown,lastdropdown,countrydropdown,degreedropdown,divisiondropdown,majoronedropdown,majortwodropdown];
            json = data;
            initMapping();
        });
    } catch (error) {
        error.style.display = "block";
        options.style.display = "none";
        loading.style.display = "none";
    }

}



function generateOutput () {
    console.log("OUTPUTTING " + datatype.value)
    //mapValues(json)
    let title = "";
    let mode = datatype.value;
    console.log("MODE:" + mode)

    if (datatype.value == "honor-roll") {
        title = "Dean's Honor Roll"
    } else {
        title = "Provost's Honor Roll"
    }
    const params = new FormData();
    params.append('userId', button.dataset.userId);
    params.append(button.dataset.csrfname, button.dataset.csrfdata);
    params.append('data', JSON.stringify(sortedjson));
    params.append('title',title);
    params.append('datamode',mode)
    
    console.log("DATATYPE: " + datatype.value);
    try {
        fetch('/actions/registrar-importer-craft/default/sort', {
        method: 'POST',
        body: params,
        }).then((response) => response.json())
            .then((data) => {
                console.log(data);
                output = data;
                previewHTML.innerHTML = data[1];
                previewPress.innerHTML = data[2];

        }); 
    } catch (error) {
        error.style.display = "block";
        options.style.display = "none";
        loading.style.display = "none"; 
    }
}

function switchTabs(activeTab) {
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    let tab = document.getElementById(activeTab);
    tab.style.display = "block";
}

function changeOutput(text) {
    if (displayCode) {
        previewHTML.innerHTML = output[0];
        displayCode = false;
        text.innerHTML = "View Text"
    } else {
        previewHTML.innerHTML = output[1];
        displayCode = true;
        text.innerHTML = "View Code"
    }
}

function initMapping() {
    let entries = Object.entries(json[0]);
    if (statevalue == "" && cityvalue == "" && firstnamevalue == "" && lastnamevalue == "") {
        if (entries.some(entry => entry.includes('State'))) {
            statevalue = 'State';
        } else {
            statevalue = entries[0];
        }
        
        if (entries.some(entry => entry.includes('City'))) {
            cityvalue = 'City';
        } else {
            cityvalue = entries[0];
        }

        if (entries.some(entry => entry.includes('First Name'))) {
            firstnamevalue = 'First Name';
        } else {
            firstnamevalue = entries[0];
        }

        if (entries.some(entry => entry.includes('Last Name'))) {
            lastnamevalue = 'Last Name';
        } else {
            lastnamevalue = entries[0];
        }

        if (entries.some(entry => entry.includes('Diploma Name'))) {
            diplomanamevalue = 'Diploma Name';
        } else {
            diplomanamevalue = entries[0];
        }

        if (entries.some(entry => entry.includes('Country'))) {
            countryvalue = 'Country';
        } else {
            countryvalue = entries[0];
        }

        if (entries.some(entry => entry.includes('Division'))) {
            divisionvalue = 'Division';
        } else {
            divisionvalue = entries[0];
        }

        if (entries.some(entry => entry.includes('Degree'))) {
            degreevalue = 'Degree';
        } else {
            degreevalue = entries[0];
        }

        if (entries.some(entry => entry.includes('Major 1'))) {
            majoronevalue = 'Major 1';
        } else {
            majoronevalue = entries[0];
        }
        
        if (entries.some(entry => entry.includes('Major 2'))) {
            majortwovalue = 'Major 2';
        } else {
            majortwovalue = entries[0];
        }

    }
    mapValues(json,null)
    switchTabs("tab-spreadsheet")
    loading.style.display = "none";
    options.style.display = "block";

}

  
function mapValues (data,dropdown) {
    //console.log(data);
    //console.log(dropdown);
    if (dropdown != null) {
        const selectedOption = dropdown.options[dropdown.selectedIndex];
        const select = JSON.parse(selectedOption.value);
        switch(select[0]) {
            case "State":
                statevalue = select[1];
                break;
            case "City":
                cityvalue = select[1];
                break;
            case "First Name":
                firstnamevalue = select[1];
                break;
            case "Last Name":
                lastnamevalue = select[1];
                break;
            case "Diploma Name":
                diplomanamevalue = select[1];
                break;
            case "Country":
                countryvalue = select[1];
                break;
            case "Division":
                divisionvalue = select[1];
                break;
            case "Degree":
                degreevalue = select[1];
                break;
            case "Major 1":
                majoronevalue = select[1];
                break;
            case "Major 2":
                majortwovalue = select[1];
                break;
            default:
                break;
        }
    }

    let mappeddata = [];
    data.forEach(element =>  { 
        if (datatype.value == "honor-roll") {
            let tempobj = {
                "State" : element[statevalue],
                "City": element[cityvalue],
                "First Name": element[firstnamevalue],
                "Last Name": element[lastnamevalue]
            }
            mappeddata.push(tempobj);
        } else {
            let tempobj = {
                "State" : element[statevalue],
                "City": element[cityvalue],
                "First Name": element[firstnamevalue],
                "Last Name": element[lastnamevalue],
                "Diploma Name": element[diplomanamevalue],
                "Country" : element[countryvalue],
                "Division" : element[divisionvalue],
                "Degree" : element[degreevalue],
                "Major 1" : element[majoronevalue],
                "Major 2" : element[majortwovalue]
            }
            mappeddata.push(tempobj);
        }
    });
    //console.log(mappeddata);
    sortedjson = mappeddata;
    generateOutput();
    generateTable();
}

function generateTable() {
    
    let body = document.getElementById("tab-spreadsheet");
    body.innerHTML = "";
    let tbl = document.createElement('table');
    const tr = tbl.insertRow();
    const selecttr = tbl.insertRow();

    let tbldata = [];

    if (datatype.value == "honor-roll") {
        tbldata = honorroll;
    } else {
        tbldata = comm;
    }

    tbldata.forEach(element => {
         const td = tr.insertCell();
         td.appendChild(document.createTextNode(element));
         
         let selecttd = selecttr.insertCell();
         let dropdown = document.createElement("select");
         
        
        for (const [key, value] of Object.entries(json[0])) {
            let option = document.createElement("option");
            option.text = key;
            option.value = JSON.stringify([element, key]);
            //console.log(option.value);
            dropdown.appendChild(option);

            switch(element) {
                case "State":
                    if (key == statevalue) {
                        option.selected = "selected";
                    }
                    break;
                case "City":
                    if (key == cityvalue) {
                        option.selected = "selected";
                    }
                    break;
                case "First Name":
                    if (key == firstnamevalue) {
                        option.selected = "selected";
                    }
                    break;
                case "Last Name":
                    if (key == lastnamevalue) {
                        option.selected = "selected";
                    }
                    break;
                case "Diploma Name":
                    if (key == diplomanamevalue) {
                        option.selected = "selected";
                    }
                    break;
                case "Country":
                    if (key == countryvalue) {
                        option.selected = "selected";
                    }
                    break;
                case "Division":
                    if (key == divisionvalue) {
                        option.selected = "selected";
                    }
                    break;
                case "Degree":
                    if (key == degreevalue) {
                        option.selected = "selected";
                    }
                    break;
                case "Major 1":
                    if (key == majoronevalue) {
                        option.selected = "selected";
                    }
                    break;
                case "Major 2":
                    if (key == majortwovalue) {
                        option.selected = "selected";
                    }
                    break;
                default:
                    break;
            }
        }


        dropdown.onchange = () => mapValues(json,dropdown);
        selecttd.appendChild(dropdown);
    });

    for (i = 0; i < 15; i++) {
        const tr = tbl.insertRow();
        for (const [key, value] of Object.entries(sortedjson[i])) {
            const td = tr.insertCell();
            td.style.opacity = 1 - i/15;
            if (`${value}`.trim() != "") {
                td.appendChild(document.createTextNode(`${value}`));
            } else {
                td.appendChild(document.createTextNode("--"));
            }
        }
    }
    body.appendChild(tbl);
}