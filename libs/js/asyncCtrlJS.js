//LOGIN FORM
const unosForm = document.getElementById('unosForm');

const loginForm = document.getElementById('loginForm');

if (loginForm) {
    loginForm.addEventListener('submit', async (event) => {
        event.preventDefault(); // Prevent form submission and page reload

        const form = event.target;
        const url = '/controllers/loginController/LoginCtrl.php';

        try {
            const formData = new FormData(form);

            const requestBody = {
                action: 'login',
                sendData: {
                    username: formData.get('username'),
                    password: formData.get('password')
                }
            };

            const response = await fetch(url, {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(requestBody)
            });

            const fetchData = await response.json();

            if (fetchData.status === "success") {
                window.location.href = "views/form.php";
            } else {
                document.getElementById("message").innerHTML = fetchData.message;
            }

        } catch (error) {
            // Handle any errors that occurred during the fetch operation
            console.error('Error:', error);
        }
    });

}

//FORMA ZA UNOS GLASAČA
const proveriEmail = async () => {
    try {

        const url = '/controllers/formController/FormCtrl.php';
        const email = document.getElementById('email').value;

        const requestBody = {
            action: 'checkEmail',
            sendData: {
                email: email
            }
        };
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestBody)
        });

        const fetchData = await response.json();

        const msg = document.getElementById("messageEmail");
        const msgInsertEmail = document.getElementById("messageInsertEmail");

        switch (fetchData.status) {
            case "free":
            case "existing":
                msg.innerHTML = fetchData.message;
                break;
            default:
                msg.innerHTML = fetchData.message;
                break;
        }

    } catch (error) {
        // Handle any errors that occurred during the fetch operation
        console.error('Error:', error);
    }
};

const proveriJMBG = async () => {
    try {

        const url = '/controllers/formController/FormCtrl.php';
        const jmbg = document.getElementById('jmbg').value;

        const requestBody = {
            action: 'checkJMBG',
            sendData: {
                jmbg: jmbg
            }
        };
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestBody)
        });
        const fetchData = await response.json();
        console.log(fetchData);

        const msg = document.getElementById("messageJMBG");

        if (fetchData.status === "bussyJMBG") {
            msg.innerHTML = fetchData.message;
        } else {
            msg.innerHTML = fetchData.message;
        }

    } catch (error) {
        // Handle any errors that occurred during the fetch operation
        console.error('Error:', error);
    }
};

const proveriTel = async () => {
    try {

        const url = '/controllers/formController/FormCtrl.php';
        const tel = document.getElementById('telefon').value;

        const requestBody = {
            action: 'checkTel',
            sendData: {
                telefon: tel
            }
        };
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestBody)
        });
        const fetchData = await response.json();
        console.log(fetchData);


        const msg = document.getElementById("messageTel");

        if (fetchData.status === "bussyTel") {
            msg.innerHTML = fetchData.message;
        } else {
            msg.innerHTML = fetchData.message;
        }

    } catch (error) {
        // Handle any errors that occurred during the fetch operation
        console.error('Error:', error);
    }
};


const ucitajOpstine = async () => {
    try {
        const url = '/controllers/formController/FormCtrl.php';
        const requestBody = {
            action: 'ucitajOpstine'
        };
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestBody)
        });

        const fetchData = await response.json();
        const opstine = document.getElementById('mesto');
        //opstine.innerHTML = '';
        let rb = 1;
        fetchData.forEach(element => {
            const option = document.createElement('option');
            option.value = rb++;
            option.textContent = element;
            opstine.appendChild(option);
        });


    } catch (error) {
        // Handle any errors that occurred during the fetch operation
        console.error('Error:', error);
    }
};

const proveriNosioce = async () => {

    try {
        const url = '/controllers/formController/FormCtrl.php';
        const izabranaOpstina = document.getElementById('mesto');
        const selectedOption = izabranaOpstina.options[izabranaOpstina.selectedIndex];
        const opstina = selectedOption.text;

        const requestBody = {
            action: 'checkNosioce',
            sendData: {
                opstina: opstina
            }
        };
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestBody)
        });
        const fetchData = await response.json(); // response podaci dobijeni fetch() funkcijom
        const dataNiz = fetchData.niz; //izdvajanje niza iz json odgovora
        // definisanje field elemenata 
        const nosilacGlasova = document.getElementById('nosilac_glasova');
        const imeNosiocaGlasova = document.getElementById('ime_nosioca_glasova');
        const option = document.createElement('option');
        const optionValueToAdd = '0';
        const existingOption = nosilacGlasova.querySelector('option[value="' + optionValueToAdd + '"]');

        if (fetchData.status === "Ima nosioca") {
            nosilacGlasova.disabled = false;

            // provera ako već postoji opcija sa Да, имам носиоца тј. value="0"           

            if (!existingOption) {
                const newOption = document.createElement('option');
                newOption.value = optionValueToAdd;
                newOption.textContent = 'Да, имам носиоца';
                nosilacGlasova.appendChild(newOption);
            }

            //Pražnjenje select boksa ImeNosiocaGlasova za nove podatke
            while (imeNosiocaGlasova.options.length > 1) {
                imeNosiocaGlasova.remove(1);
            }
            //Učitavanje nosilaca u polje Ime nosioca glasova
            let rb = 1;
            dataNiz.forEach(element => {
                const option = document.createElement('option');
                option.value = rb++;
                option.textContent = element;
                imeNosiocaGlasova.appendChild(option);
            });
        } else {
            //Uklanjanje opcije Да, имам носиоца
            if (existingOption) {
                nosilacGlasova.removeChild(existingOption);
            }

            nosilacGlasova.disabled = false;
            imeNosiocaGlasova.disabled = true;

        }
    } catch (error) {
        // Handle any errors that occurred during the fetch operation
        console.error('Error:', error);
    }
};

const imeNosiocaGlasova = async() => {

    try {
        const url = '/controllers/formController/FormCtrl.php';
        const izabranaOpstina = document.getElementById('mesto');
        const selectedOption = izabranaOpstina.options[izabranaOpstina.selectedIndex];
        const opstina = selectedOption.text;

        const requestBody = {
            action: 'checkNosioce',
            sendData: {
                opstina: opstina
            }
        }
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestBody)
        });
        const fetchData = await response.json(); // response podaci dobijeni fetch() funkcijom
        const dataNiz = fetchData.niz; //izdvajanje niza iz json odgovora
        // definisanje field elemenata 
        const nosilacGlasova = document.getElementById('nosilac_glasova');
        const imeNosiocaGlasova = document.getElementById('ime_nosioca_glasova');
        const option = document.createElement('option');
        const optionValueToAdd = '0';
        const existingOption = nosilacGlasova.querySelector('option[value="' + optionValueToAdd + '"]');

        if (fetchData.status === "Ima nosioca") {
            nosilacGlasova.disabled = false;

            // provera ako već postoji opcija sa Да, имам носиоца тј. value="0"           

            if (!existingOption) {
                const newOption = document.createElement('option');
                newOption.value = optionValueToAdd;
                newOption.textContent = 'Да, имам носиоца';
                nosilacGlasova.appendChild(newOption);
            }

            //Pražnjenje select boksa ImeNosiocaGlasova za nove podatke
            while (imeNosiocaGlasova.options.length > 1) {
                imeNosiocaGlasova.remove(1);
            }
            //Učitavanje nosilaca u polje Ime nosioca glasova
            let rb = 1;
            imeNosiocaGlasova.disabled = false;
            dataNiz.forEach(element => {
                const option = document.createElement('option');
                option.value = rb++;
                option.textContent = element;
                imeNosiocaGlasova.appendChild(option);
            });

            if (nosilacGlasova.value === '0') {
                imeNosiocaGlasova.disabled = false;
            } else {
                imeNosiocaGlasova.disabled = true;
                while (imeNosiocaGlasova.options.length > 1) {
                    imeNosiocaGlasova.remove(1);
                }
            }
        } else {
            //Uklanjanje opcije Да, имам носиоца
            if (existingOption) {
                nosilacGlasova.removeChild(existingOption);
            }

            nosilacGlasova.disabled = false;
            imeNosiocaGlasova.disabled = true;

        }
    } catch (error) {
        // Handle any errors that occurred during the fetch operation
        console.error('Error:', error);
    }
};

if (unosForm) {
    unosForm.addEventListener('submit', async event => {
        try {
            event.preventDefault();

            const mesto = document.getElementById('mesto');
            const ime_nosioca_glasova = document.getElementById('ime_nosioca_glasova');

            let canSubmit = true; // Flag variable

            // Proverava da li je vrednost option polja "0"
            if (mesto.value === "0") {
                showAlert("Izaberite validnu opciju za Opštinu!");
                canSubmit = false;
            }

            if (ime_nosioca_glasova.value === "0" && ime_nosioca_glasova.disabled === false) {
                showAlert("Izaberite validnu opciju za Ime nosioca glasova!");
                canSubmit = false;
            }

            if (!canSubmit) {
                event.preventDefault(); // Prevent form submission
                return;
            }

            const messageEmail = document.getElementById('messageEmail');
            const messageJMBG = document.getElementById('messageJMBG');
            const messageTel = document.getElementById('messageTel');

            if (messageEmail.textContent === "Унели сте невалидан мејл!" || messageEmail.textContent === "Унели сте постојећи мејл!"
                    || messageJMBG.textContent === "Изабрали сте постојећи JMBG!" || messageJMBG.textContent === "ЈМБГ мора имати 13 цифри!"
                    || messageTel.textContent === "Унели сте постојећи телефон!") {

                event.preventDefault(); // Prevent form submission
                return;
            }

            const form = event.target;
            const url = '/controllers/formController/FormCtrl.php';
            const formData = new FormData(form);

            const opstinaSelect = form.elements.opstina;
            const imeNosiocaGlasovaSelect = form.elements.ime_nosioca_glasova;
            const opstinaText = opstinaSelect.options[opstinaSelect.selectedIndex].text;
            let imeNosiocaGlasovaText = imeNosiocaGlasovaSelect.options[imeNosiocaGlasovaSelect.selectedIndex].text;

            if (imeNosiocaGlasovaText === "Изаберите носиоца...") {
                imeNosiocaGlasovaText = "";
            }

            const requestBody = {
                action: 'insertGlasac',
                sendData: {
                    ime: formData.get('ime'),
                    prezime: formData.get('prezime'),
                    jmbg: formData.get('jmbg'),
                    adresa: formData.get('adresa'),
                    telefon: formData.get('telefon'),
                    biraliste: formData.get('biraliste'),
                    email: formData.get('email'),
                    datum_rodj: formData.get('datum_rodj'),
                    nosilac_glasova: formData.get('nosilac_glasova'),
                    ime_nosioca_glasova: imeNosiocaGlasovaText,
                    opstina: opstinaText
                }
            };
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(requestBody)
            });

            const fetchData = await response.json();
            console.log(fetchData);
            const messageUnos = document.getElementById('messageUnos');
            if (fetchData.status === "uspesno") {
                messageUnos.innerHTML = fetchData.message;
                messageUnos.style.color = "green";
            } else if (fetchData.status === "neuspesno") {
                messageUnos.innerHTML = fetchData.message;
                messageUnos.style.color = "red";
            } else {
                messageUnos.innerHTML = "Error: Invalid response from the server";
                messageUnos.style.color = "red";
            }

// Hide the message after 5 seconds
            setTimeout(() => {
                messageUnos.innerHTML = "";
            }, 5000);

            form.reset();

        } catch (error) {
            console.log('Error:', error);
        }
    });
}

const showAlert = (message) => {
    const alertMessage = document.getElementById('alertMessage');
    alertMessage.textContent = message;

    $('#customAlert').modal('show');

    const closeAlertButton = document.getElementById('closeAlert');
    closeAlertButton.addEventListener('click', function () {
        $('#customAlert').modal('hide');
    });

};

//STRANICA TABELE GLASACA
const deleteRow = async (button) => {
    const row = button.closest('tr');
    const firstCell = row.querySelector('td:first-child');
    const id = firstCell.textContent;
    console.log(id);
    const confirmed = window.confirm('Да ли желите да избришете ове податке?');

    if (confirmed) {
        // Proceed with the deletion if the user confirms
        await deleteGlasac(id);
        row.remove(); // Remove the row from the table after successful deletion
        ucitajTabelu();
    }


};

const deleteGlasac = async (id) => {
    try {
        const url = '/controllers/tableController/TableCtrl.php';
        const requestBody = {
            action: 'deleteGlasac',
            sendData: {
                id: id
            }
        };

        const response = await fetch(url, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestBody)
        });

        const result = await response.json();
        console.log(result); // Handle the response accordingly, if needed
    } catch (error) {
        console.error('Error:', error);
    }
};

//EDITOVANJE TABELE GLASACA
const editRow = async (button) => {
    //traženje indeksa reda i id glasaca tabele koji editujemo
    const row = button.closest('tr');
    const cells = row.querySelectorAll('td');

    const firstCell = row.querySelector('td:first-child');
    const id = firstCell.textContent;

    //slanje id i dobijanje podataka iz baze
    const requestBody = {
        action: 'getGlasacById',
        sendData: {
            id: id
        }
    };
    const url = "/controllers/tableController/TableCtrl.php";
    const response = await fetch(url, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(requestBody)
    });
    const fetchData = await response.json();

    const fetchNiz = fetchData.niz;
    const editId = document.getElementById('idModal');
    editId.value = id;
    // Popunjavanje polja u modalu sa podacima 
    const editIme = document.getElementById('imeModal');
    editIme.value = fetchNiz.ime;
    const editPrezime = document.getElementById('prezimeModal');
    editPrezime.value = fetchNiz.prezime;
    const editEmail = document.getElementById('emailModal');
    editEmail.value = fetchNiz.email;
    const editJMBG = document.getElementById('jmbgModal');
    editJMBG.value = fetchNiz.jmbg;
    const editDatum_rodj = document.getElementById('datum_rodjModal');
    editDatum_rodj.value = fetchNiz.datum_rodj;
    const editTelefon = document.getElementById('telefonModal');
    editTelefon.value = fetchNiz.telefon;
    const editAdresa = document.getElementById('adresaModal');
    editAdresa.value = fetchNiz.adresa;
    const editBiraliste = document.getElementById('biralisteModal');
    editBiraliste.value = fetchNiz.biraliste;



    //Poverenistva
    const poverenistva = document.getElementById('mestoModal');
    poverenistva.innerHTML = '';
    const optionPov = document.createElement('option');
    optionPov.textContent = fetchNiz.naziv_opstine;
    poverenistva.appendChild(optionPov);
    //Nosilac glasova
    const nosilacGlasova = document.getElementById('nosilac_glasovaModal');
    nosilacGlasova.innerHTML = "";
    const optionNG = document.createElement('option');
    optionNG.textContent = fetchNiz.nosilac_glasova_tip;
    nosilacGlasova.appendChild(optionNG);
    const options = [
        {label: 'Ја сам носилац'},
        {label: 'Да, имам носиоца'},
        {label: 'Не, немам носиоца'}
    ];

    // Dodavanje dodatnih opcija u dropdown Nosilac glasova
    options.forEach((option) => {
        const optionElement = document.createElement('option');
        //optionElement.value = option.value;
        optionElement.textContent = option.label;
        nosilacGlasova.appendChild(optionElement);
    });

    //Ime nosioca glasova
    const imeNosiocaGlasova = document.getElementById('ime_nosioca_glasovaModal');
    imeNosiocaGlasova.innerHTML = '';
    const optionING = document.createElement('option');
    optionING.textContent = fetchNiz.nosilac_glasova_ime;
    imeNosiocaGlasova.appendChild(optionING);

};

//Učitavanje tabele
const ucitajTabelu = async () => {
    try {
        const url = '/controllers/tableController/TableCtrl.php';
        const requestBody = {
            action: 'loadTable'
        };
        const response = await fetch(url, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestBody)
        });


        const fetchData = await response.json();
        console.log(fetchData);
        const opstine_tab = document.getElementById('opstine_tab');
        const tabela_glasaca = document.getElementById('tabela_glasaca');
        const tabela_glasaca_body = document.getElementById('tabela_glasaca_body');
        const datepicker = document.getElementById('datepicker');

        const createRow = (data, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
        <td>${data.id}</td>    
        <td>${index}</td>
        <td>${data.ime}</td>
        <td>${data.prezime}</td>
        <td>${data.email}</td>
        <td>${data.jmbg}</td>
        <td>${data.datum_rodj}</td>
        <td>${data.adresa}</td>
        <td>${data.naziv_opstine}</td>
        <td>${data.biraliste}</td>
        <td>${data.naziv_regiona}</td>
        <td>${data.telefon}</td>
        <td>${data.datum_unosa}</td>
        <td>${data.nosilac_glasova_tip}</td>
        <td>${data.nosilac_glasova_ime}</td>
        <td>
        <button class="btn btn-primary btn-sm edit-btn" onclick="editRow(this)" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
        <button class="btn btn-danger btn-sm" onclick="deleteRow(this)" id="deleteGlasac">Delete</button>
      </td>`;

            return row;
        };

        const appendRowsToTable = (rows) => {
            tabela_glasaca_body.innerHTML = '';
            rows.forEach(row => {
                tabela_glasaca_body.appendChild(row);
            });
        };

        const populateOpstineSelect = (opstine) => {
            opstine_tab.innerHTML = '';
            let rb = 1;
            opstine.forEach(opstina => {
                const option = document.createElement('option');
                option.value = rb++;
                option.textContent = opstina;
                opstine_tab.appendChild(option);
            });
        };

        const fetchAndPopulateTable = () => {
            tabela_glasaca_body.innerHTML = '';
            fetchData.forEach((element, index) => {
                const row = createRow(element, index + 1);
                tabela_glasaca_body.appendChild(row);
            });
        };

        const fetchAndFilterByOpstina = (selectedText) => {
            const filteredData = selectedText === "Сва повереништва"
                    ? fetchData
                    : fetchData.filter(element => selectedText === element.naziv_opstine);

            const filteredRows = filteredData.map((element, index) => createRow(element, index + 1));
            appendRowsToTable(filteredRows);
            updateRowIndices(); // Update row indices after filtering
        };

        const fetchAndFilterByDate = (datepickerValue) => {
            const filteredData = fetchData.filter(element => {
                const shortDate = element.datum_unosa.substring(0, 10);
                return datepickerValue === shortDate;
            });

            const filteredRows = filteredData.map((element, index) => createRow(element, index + 1));
            appendRowsToTable(filteredRows);
            updateRowIndices(); // Update row indices after filtering
        };

        const updateRowIndices = () => {
            const rows = tabela_glasaca_body.querySelectorAll("tr");

            rows.forEach((row, index) => {
                const indexCell = row.querySelector("td:nth-child(2)");
                indexCell.textContent = index + 1;
            });
        };

        const fetchOpstineAndPopulateSelect = () => {
            const nizOpstina = [...new Set(fetchData.map(element => element.naziv_opstine))];
            populateOpstineSelect(nizOpstina);

            // Check if "Све општине" option exists
            const sveOption = opstine_tab.querySelector('option[value="0"]');
            if (!sveOption) {
                const option = document.createElement('option');
                option.value = "0";
                option.textContent = "Сва повереништва";
                opstine_tab.insertBefore(option, opstine_tab.firstChild);
                opstine_tab.value = "0"; // Set "Све општине" as the selected option
            } else {
                // Move "Све општине" option to the first position
                opstine_tab.insertBefore(sveOption, opstine_tab.firstChild);
                opstine_tab.value = sveOption.value; // Set "Све општине" as the selected option
            }
        };

        // Fetch data and populate the table initially
        fetchAndPopulateTable();
        fetchOpstineAndPopulateSelect();

        // Event listener for opstine_tab select
        opstine_tab.addEventListener('change', async event => {
            const selectedOption = opstine_tab.selectedOptions[0];
            const selectedText = selectedOption.textContent;
            fetchAndFilterByOpstina(selectedText);
        });

        // Event listener for datepicker
        if (datepicker) {
            datepicker.addEventListener('change', async event => {
                const datepickerValue = datepicker.value;
                fetchAndFilterByDate(datepickerValue);
            });
        }
    } catch (error) {
        console.error('Error:', error);
    }
};

// Date picker
let selectedDate = null;
flatpickr("#datepicker", {
    dateFormat: "d/m/Y",
    onClose: function (selectedDates, dateStr, instance) {

    }
});

//Export to Excell
const exportToExcell = () => {
    const table = document.getElementById('tabela_glasaca');

    // Create a new workbook
    const wb = XLSX.utils.book_new();

    // Convert the table to a worksheet
    const ws = XLSX.utils.table_to_sheet(table);

    // Convert the date values to Excel serial numbers
    Object.keys(ws).forEach((cellRef) => {
        if (cellRef.match(/[A-Z]+1/)) {
            const cell = ws[cellRef];
            if (cell.t === 'd') {
                const dateParts = cell.v.split('/');
                const excelDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
                cell.t = 'n';
                cell.v = XLSX.datenum(excelDate);
            }
        }
    });

    // Set the column width for the specific column   
    ws['!cols'] = [{width: 8}, {width: 20}, {width: 20}, {width: 20}, {width: 20},
        {width: 20}, {width: 20}, {width: 20}, {width: 20},
        {width: 20}, {width: 20}, {width: 20}, {width: 20}, {width: 40}];


    // Add the worksheet to the workbook
    XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');

    // Generate the Excel file
    const wbout = XLSX.write(wb, {bookType: 'xlsx', type: 'array'});

    // Save the file
    saveAs(new Blob([wbout], {type: 'application/octet-stream'}), 'glasaci.xlsx');
};



const ucitajOpstineEdit = async () => {
    try {

        const url = '/controllers/tableController/TableCtrl.php';
        const requestBody = {
            action: 'ucitajOpstineEdit'
        };

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestBody)
        });
        const fetchData = await response.json();
        const opstine = document.getElementById('mestoModal');
        const selectedValue = opstine.value; // Store the currently selected value
        opstine.innerHTML = '';
        let rb = 1;
        fetchData.forEach(element => {
            const option = document.createElement('option');
            option.value = rb++;
            option.textContent = element;
            opstine.appendChild(option);
        });
        opstine.value = selectedValue;

        const nosilacGlasova = document.getElementById("nosilac_glasovaModal");

        const imeNosiocaGlasova = document.getElementById("ime_nosioca_glasovaModal");
        imeNosiocaGlasova.innerHTML = null; // Clear the options

    } catch (error) {
        // Handle any errors that occurred during the fetch operation
        console.error('Error:', error);
    }
};

const removeDuplicateOptions = (selectField) => {
    const uniqueOptions = [];
    const optionsToRemove = [];

    // Iterate over the options and check for duplicates
    Array.from(selectField.options).forEach((option) => {
        if (!uniqueOptions.includes(option.textContent)) {
            uniqueOptions.push(option.textContent);
        } else {
            optionsToRemove.push(option);
        }
    });

    // Remove the duplicate options from the select field
    optionsToRemove.forEach((option) => {
        option.remove();
    });
};

const nosilacGlasova = document.getElementById('nosilac_glasovaModal');
if (nosilacGlasova) {
    nosilacGlasova.addEventListener("change", async () => {
        try {
            const url = '/controllers/tableController/TableCtrl.php';
            // izabrano poverenistvo
            const izabranaOpstina = document.getElementById('mestoModal');
            const selectedOption = izabranaOpstina.options[izabranaOpstina.selectedIndex];
            const opstina = selectedOption.text;
            // izabran tip nosioca
            const selectedTipNosioca = nosilacGlasova.options[nosilacGlasova.selectedIndex];
            const content = selectedTipNosioca.text;

            const imeNosiocaGlasova = document.getElementById("ime_nosioca_glasovaModal");
            const poruka = document.getElementById("messageING");

            imeNosiocaGlasova.innerHTML = null; // Clear the options

            const requestBody = {
                action: 'getImeNosioca',
                sendData: {
                    poverenistvo: opstina
                }
            };
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(requestBody)
            });
            const fetchData = await response.json();
            const dataNiz = fetchData.niz;

            const jmbg = document.getElementById('jmbgModal').value;

            //punjenje dropdown-a za imeNosiocaGlasova 
            console.log(fetchData);
            if (content === "Да, имам носиоца") {
                if (fetchData.status !== "fail") {

                    //let rb = 1;
                    const filteredArr = dataNiz.filter(item => {
                        // Exclude strings that contain the specified substring
                        //brisanje nosioca iz niza ukoliko se menja status iz "Ја сам носилац" u "Да, имам носиоца"
                        if (typeof item === "string" && item.includes(jmbg)) {
                            return false;
                        }
                        return true;
                    });
                    filteredArr.forEach((item) => {
                        const option = document.createElement('option');
                        //option.value = rb++;
                        option.textContent = item;
                        imeNosiocaGlasova.appendChild(option);
                    });
                } else {
                    poruka.innerHTML = fetchData.message;
                    setTimeout(() => {
                        poruka.innerHTML = '';
                    }, 4000);
                }

            } else {
                imeNosiocaGlasova.innerHTML = null; // Clear the options
            }
        } catch (error) {
            // Handle any errors that occurred during the fetch operation
            console.error('Error:', error);
        }
    });
}


const poverenistvo = document.getElementById("mestoModal");
if (poverenistvo) {
    poverenistvo.addEventListener("change", () => {
        const nosilacGlasova = document.getElementById("nosilac_glasovaModal");

        // Find the option with text content "Ја сам носилац"
        const optionJaSamNosilac = [...nosilacGlasova.options].find((option) => option.textContent === "Ја сам носилац");

        // Move the option to the first position
        if (optionJaSamNosilac) {
            nosilacGlasova.insertBefore(optionJaSamNosilac, nosilacGlasova.firstChild);
        }

        // Select the first option
        nosilacGlasova.selectedIndex = 0;
    });
}

const form = document.getElementById('editModalForm');
if (form) {
    form.addEventListener('submit', async(event) => {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const opstinaSelect = document.getElementById("mestoModal"); 
        let selectedOption = opstinaSelect.selectedOptions[0]; // Selected option
        let opstinaText = selectedOption.textContent;
        const requestBody = {
            action: 'updateGlasac',
            sendData: {
                id: parseInt(formData.get("idModal")),
                ime: formData.get("imeModal"),
                prezime: formData.get("prezimeModal"),
                email: formData.get("emailModal"),
                jmbg: formData.get("jmbgModal"),
                datum_rodj: formData.get("datum_rodjModal"),
                telefon: formData.get("telefonModal"),
                adresa: formData.get("adresaModal"),
                biraliste: formData.get("biralisteModal"),
                opstina: opstinaText,
                nosilac_glasova: formData.get("nosilac_glasovaModal"),
                ime_nosioca_glasova: formData.get("ime_nosioca_glasovaModal")
            }
        };
        console.log(requestBody);
        // Display a confirmation dialog
        const confirmed = window.confirm('Да ли сте сигурни да желите да измените ове податке?');
        if (confirmed) {
            try {
                const url = '/controllers/tableController/TableCtrl.php';
                const response = await fetch(url, {
                    method: 'POST',
                    body: JSON.stringify(requestBody),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                if (data.status === "success") {
                    // Manually remove the backdrop element
                    const modalBackdropElement = document.querySelector('.modal-backdrop');
                    modalBackdropElement.parentNode.removeChild(modalBackdropElement);

                    // Manually hide the modal
                    const modalElement = document.getElementById('editModal');
                    modalElement.style.display = 'none';
                    modalElement.classList.remove('show');

                    // Show the modal again using Bootstrap's Modal API
                    const editModal = new bootstrap.Modal(modalElement);

                }
                ucitajTabelu();
                // Handle the response data here (if needed)
                console.log(data);
            } catch (error) {
                // Handle any errors during the fetch() request
                console.error('Error:', error);
            }
        }
    });
}

/***Reset Password***/

//Provera da li postoji username. Ako postoji pojavljuje se modal za proveravu da li ima email za dato korisničko ime. 

const verifyUsernameBtn = document.getElementById('verifyUsernameBtn');
const usernameInput = document.getElementById('usernameInput');

if (verifyUsernameBtn) {
    verifyUsernameBtn.addEventListener('click', async () => {
        const username = usernameInput.value;
        const url = '/controllers/resetPassCtrl/ResetPassCtrl.php';
        try {
            const requestBody = {
                action: 'checkUsername',
                sendData: {
                    username: username
                }
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(requestBody),
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const fetchData = await response.json();
            if (fetchData.status === "success") {
                // Email exists in the database, prikazati prvi modal za Email
                $('#usernameVerificationModal').modal('hide');
                $('#forgotPasswordModal').modal('show');
            } else {
                const checkUsernameMessage = document.getElementById("checkUsernameMessage");
                checkUsernameMessage.innerHTML = fetchData.message;
                setTimeout(() => {
                    checkUsernameMessage.innerHTML = '';
                }, 3000);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
}


const sendVerificationBtn = document.getElementById('sendVerificationBtn');
if (sendVerificationBtn) {
    sendVerificationBtn.addEventListener('click', async () => {
        const email = document.getElementById('emailInput').value;
        const url = '/controllers/resetPassCtrl/ResetPassCtrl.php';
        try {
            const requestBody = {
                action: 'submitEmail',
                sendData: {
                    email: email
                }
            };
            const response = await fetch(url, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(requestBody)
            });

            const fetchData = await response.json();
            console.log(fetchData);
            const emailMsgErr = document.getElementById('emailMessageError');
            const emailMsgSucc = document.getElementById('emailMessageSuccess');
            if (fetchData.status === "non exist") {
                emailMsgErr.innerHTML = fetchData.message;
                setTimeout(() => {
                    emailMsgErr.innerHTML = '';
                }, 2000);
            } else {
                emailMsgSucc.innerHTML = fetchData.message;
                setTimeout(() => {
                    $('#forgotPasswordModal').modal('hide');
                    emailMsgSucc.innerHTML = '';
                }, 1500);

            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
}

const resetPassBtn = document.getElementById('resetPassBtn');
if (resetPassBtn) {
    resetPassBtn.addEventListener('click', async (event) => {
        const url = '/controllers/resetPassCtrl/ResetPassCtrl.php';
        const newPassword = document.getElementById('newPassword').value;
        const token = document.getElementById('token').value;
        console.log(newPassword);
        console.log(token);
        event.preventDefault();
        try {
            const requestBody = {
                action: 'newPassword',
                sendData: {
                    newPassword: newPassword,
                    token: token
                }
            };
            console.log(requestBody);
            const response = await fetch(url, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(requestBody)
            });


            const fetchData = await response.json();
            console.log(fetchData);
            const errorMsg = document.getElementById('tokenErrMsg');
            const successMsg = document.getElementById('tokenSuccMsg');

// Reset the class name first before adding the new class
            errorMsg.className = 'message';
            successMsg.className = 'message';

            if (fetchData.status === "success") {
                setTimeout(() => {
                    successMsg.innerHTML = '';
                    window.location.href = "../../index.php";
                }, 2000);
                successMsg.className += ' success';
                successMsg.innerHTML = fetchData.message;
            } else {
                setTimeout(() => {
                    errorMsg.innerHTML = '';
                }, 2000);
                errorMsg.className += ' error';
                errorMsg.innerHTML = fetchData.message;
            }

        } catch (error) {
            console.error('Error:', error);
        }
    });
}

/*ADMINISTRACIJA POVERENIKA*/
const ucitajOkruge = async() => {
    try {
        const okrug = document.getElementById("okrug");
        const okrugDelete = document.getElementById('okrugDelete');


        const url = '/controllers/adminController/AdminCtrl.php';
        const requestBody = {
            action: 'ucitajOkruge'
        };

        const response = await fetch(url, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestBody)
        });

        const fetchData = await response.json();
        const nizData = fetchData.niz;


        nizData.forEach(element => {
            const option = document.createElement('option');
            option.value = element.id;
            option.textContent = element.naziv_regiona;
            if (okrug) {
                okrug.appendChild(option);
            } else if (okrugDelete) {
                okrugDelete.appendChild(option);
            }

        });

    } catch (error) {
        console.error('Error:', error);
    }
};

const okrug = document.getElementById('okrug');

if (okrug) {
    okrug.addEventListener('change', async() => {
        const url = '/controllers/adminController/AdminCtrl.php';
        const selectedOption = okrug.options[okrug.selectedIndex];
        const okrugText = selectedOption.textContent;

        const poverenistvo = document.getElementById('poverenistvo');
        const selectedOptionPov = poverenistvo.options[poverenistvo.selectedIndex];

        const requestBody = {
            action: 'poverenistvaPoOkrugu',
            sendData: {
                okrug: okrugText
            }
        };
        const response = await fetch(url, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestBody)
        });

        const fetchData = await response.json();
        const nizData = fetchData.niz;


        const optionsToRemove = Array.from(poverenistvo.options).slice(1); // Exclude the first (initial) option
        optionsToRemove.forEach(option => poverenistvo.removeChild(option));


        let rb = 1;
        nizData.forEach(element => {
            const option = document.createElement('option');
            option.value = rb++;
            option.textContent = element;
            poverenistvo.appendChild(option);
        });

    });
}

const submitPoverenik = document.getElementById('unosPoverenikForm');
if (submitPoverenik) {
    submitPoverenik.addEventListener('submit', async event => {
        try {
            event.preventDefault();
            const button = event.target;
            const form = button.closest('form'); // Get the parent form element
            const url = '/controllers/adminController/AdminCtrl.php';
            const formData = new FormData(form);

            const okrugSelect = form.elements.okrug;
            const okrugText = okrugSelect.options[okrugSelect.selectedIndex].text;
            const okrugValue = +okrugSelect.options[okrugSelect.selectedIndex].value;

            const poverenistvoSelect = form.elements.poverenistvo;
            const poverenistvoText = poverenistvoSelect.options[poverenistvoSelect.selectedIndex].text;
            const poverenistvoValue = +poverenistvoSelect.options[poverenistvoSelect.selectedIndex].value;

            const poverenikNivoSelect = form.elements.poverenik_nivo;
            const poverenikNivoValue = +poverenikNivoSelect.options[poverenikNivoSelect.selectedIndex].value;

            if (okrugValue === 0 || poverenistvoValue === 0) {
                alert("Изаберите валидну опцију за округ и повереништво!");
                return;
            }

            const requestBody = {
                action: 'kreirajPoverenika',
                sendData: {
                    ime: formData.get('ime'),
                    prezime: formData.get('prezime'),
                    jmbg: formData.get('jmbg'),
                    adresa: formData.get('adresa'),
                    telefon: formData.get('telefon'),
                    biraliste: formData.get('biraliste'),
                    email: formData.get('email'),
                    datum_rodj: formData.get('datum_rodj'),
                    okrug: okrugText,
                    poverenistvo: poverenistvoText,
                    poverenik_nivo: poverenikNivoValue
                }
            };

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(requestBody)
            });

            const fetchData = await response.json();
            console.log(fetchData);
            const messageUnos = document.getElementById('messagePoverenikUnos');
            messageUnos.innerText = fetchData.message;
            if (fetchData.status === "uspesno") {
                messageUnos.style.color = "green";
            } else {
                messageUnos.style.color = "red";
            }
            form.reset();
            const optionsToRemove = Array.from(poverenistvoSelect.options).slice(1); // Exclude the first (initial) option
            optionsToRemove.forEach(option => poverenistvoSelect.removeChild(option));
            setTimeout(() => {
                messageUnos.innerText = "";
            }, 5000);


        } catch (error) {
            console.log(error);
        }

    });
}

const ucitajTabeluPov = async () => {
    try {
        const url = '/controllers/adminController/AdminCtrl.php';
        const requestBody = {
            action: 'loadTablePov'
        };
        const response = await fetch(url, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestBody)
        });


        const fetchData = await response.json();

        const opstine_tab = document.getElementById('opstine_tab');
        const tabela_poverenika = document.getElementById('tabela_poverenika');
        const tabela_pov_body = document.getElementById('tabela_pov_body');
        const datepicker = document.getElementById('datepicker');

        const createRow = (data, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
        <td>${data.id}</td>    
        <td>${index}</td>
        <td>${data.ime}</td>
        <td>${data.prezime}</td>
        <td>${data.email}</td>
        <td>${data.jmbg}</td>
        <td>${data.datum_rodj}</td>
        <td>${data.adresa}</td>
        <td>${data.naziv_opstine}</td>
        <td>${data.biraliste}</td>
        <td>${data.naziv_regiona}</td>
        <td>${data.telefon}</td>
        <td>${data.admin_nivo}</td>        
        <td>
        <button class="btn btn-primary btn-sm edit-btn" onclick="editRowPov(this)" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
        <button class="btn btn-danger btn-sm" onclick="deleteRowPov(this)" id="deletePoverenik">Delete</button>
      </td>`;

            return row;
        };

        const appendRowsToTable = (rows) => {
            tabela_pov_body.innerHTML = '';
            rows.forEach(row => {
                tabela_pov_body.appendChild(row);
            });
        };

        const populateOpstineSelect = (opstine) => {
            opstine_tab.innerHTML = '';
            let rb = 1;
            opstine.forEach(opstina => {
                const option = document.createElement('option');
                option.value = rb++;
                option.textContent = opstina;
                opstine_tab.appendChild(option);
            });
        };

        const fetchAndPopulateTable = () => {
            tabela_pov_body.innerHTML = '';
            fetchData.forEach((element, index) => {
                const row = createRow(element, index + 1);
                tabela_pov_body.appendChild(row);
            });
        };

        const fetchAndFilterByOpstina = (selectedText) => {
            const filteredData = selectedText === "Сви повереници"
                    ? fetchData
                    : fetchData.filter(element => selectedText === element.naziv_opstine);

            const filteredRows = filteredData.map((element, index) => createRow(element, index + 1));
            appendRowsToTable(filteredRows);
            updateRowIndices(); // Update row indices after filtering
        };

        const fetchAndFilterByDate = (datepickerValue) => {
            const filteredData = fetchData.filter(element => {
                const shortDate = element.datum_unosa.substring(0, 10);
                return datepickerValue === shortDate;
            });

            const filteredRows = filteredData.map((element, index) => createRow(element, index + 1));
            appendRowsToTable(filteredRows);
            updateRowIndices(); // Update row indices after filtering
        };

        const updateRowIndices = () => {
            const rows = tabela_pov_body.querySelectorAll("tr");

            rows.forEach((row, index) => {
                const indexCell = row.querySelector("td:nth-child(2)");
                indexCell.textContent = index + 1;
            });
        };

        const fetchOpstineAndPopulateSelect = () => {
            const nizOpstina = [...new Set(fetchData.map(element => element.naziv_opstine))];
            populateOpstineSelect(nizOpstina);

            // Check if "Све општине" option exists
            const sveOption = opstine_tab.querySelector('option[value="0"]');
            if (!sveOption) {
                const option = document.createElement('option');
                option.value = "0";
                option.textContent = "Сви повереници";
                opstine_tab.insertBefore(option, opstine_tab.firstChild);
                opstine_tab.value = "0"; // Set "Све општине" as the selected option
            } else {
                // Move "Све општине" option to the first position
                opstine_tab.insertBefore(sveOption, opstine_tab.firstChild);
                opstine_tab.value = sveOption.value; // Set "Све општине" as the selected option
            }
        };

        // Fetch data and populate the table initially
        fetchAndPopulateTable();
        fetchOpstineAndPopulateSelect();

        // Event listener for opstine_tab select
        opstine_tab.addEventListener('change', async event => {
            const selectedOption = opstine_tab.selectedOptions[0];
            const selectedText = selectedOption.textContent;
            fetchAndFilterByOpstina(selectedText);
        });

        // Event listener for datepicker
        if (datepicker) {
            datepicker.addEventListener('change', async event => {
                const datepickerValue = datepicker.value;
                fetchAndFilterByDate(datepickerValue);
            });
        }
    } catch (error) {
        console.error('Error:', error);
    }
};

const editRowPov = async (button) => {
    //traženje indeksa reda i id glasaca tabele koji editujemo
    const row = button.closest('tr');
    const cells = row.querySelectorAll('td');

    const firstCell = row.querySelector('td:first-child');
    const id = firstCell.textContent;

    //slanje id i dobijanje podataka iz baze
    const requestBody = {
        action: 'getPoverenikById',
        sendData: {
            id: id
        }
    };
    const url = "/controllers/adminController/AdminCtrl.php";
    const response = await fetch(url, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(requestBody)
    });
    const fetchData = await response.json();
    console.log(fetchData);
    const fetchNiz = fetchData.niz;
    console.log(fetchNiz);
    //Učitavanje inicijalnih podataka u modalau 
    const editId = document.getElementById('idModal');
    editId.value = id;

    const editIme = document.getElementById('imeModal');
    editIme.value = fetchNiz.ime;
    const editPrezime = document.getElementById('prezimeModal');
    editPrezime.value = fetchNiz.prezime;
    const editEmail = document.getElementById('emailModal');
    editEmail.value = fetchNiz.email;
    const editJMBG = document.getElementById('jmbgModal');
    editJMBG.value = fetchNiz.jmbg;
    const editDatum_rodj = document.getElementById('datum_rodjModal');
    editDatum_rodj.value = fetchNiz.datum_rodj;
    const editTelefon = document.getElementById('telefonModal');
    editTelefon.value = fetchNiz.telefon;
    const editAdresa = document.getElementById('adresaModal');
    editAdresa.value = fetchNiz.adresa;
    const editBiraliste = document.getElementById('biralisteModal');
    editBiraliste.value = fetchNiz.biraliste;
    const editOkrug = document.getElementById('okrugModal');
    editOkrug.value = fetchNiz.naziv_regiona;
    const editPoverenistvo = document.getElementById('poverenistvoModal');
    editPoverenistvo.value = fetchNiz.naziv_opstine;
    const editNivoPov = document.getElementById('nivoPoverenikaModal');
    editNivoPov.value = fetchNiz.admin_nivo;

};


const editPovForm = document.getElementById("editModalFormPoverenik");
if (editPovForm) {
    editPovForm.addEventListener("submit", async(event) => {

        try {
            event.preventDefault();
            const form = event.target;
            const formDataPov = new FormData(form);

            const url = '/controllers/adminController/AdminCtrl.php';

            const requestBody = {
                action: 'saveEditPoverenik',
                sendData: {
                    id: parseInt(formDataPov.get('idModal'), 10),
                    ime: formDataPov.get('imeModal'),
                    prezime: formDataPov.get('prezimeModal'),
                    email: formDataPov.get('emailModal'),
                    jmbg: formDataPov.get('jmbgModal'),
                    datum_rodj: formDataPov.get('datum_rodjModal'),
                    telefon: formDataPov.get('telefonModal'),
                    adresa: formDataPov.get('adresaModal'),
                    biraliste: formDataPov.get('biralisteModal')
                }
            };
            const response = await fetch(url, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(requestBody)
            });
            
            const fetchData = await response.json();
            console.log(fetchData);
            if (fetchData.status === 'success') {
                ucitajTabeluPov();
                $('#editModal').modal('hide');
            }
            console.log(fetchData);

        } catch (error) {
            console.log("Greška:" + error);
        }
    });
}
//STRANICA TABELE POVERENIKA
const deleteRowPov = async (button) => {
    const row = button.closest('tr');
    const firstCell = row.querySelector('td:first-child');
    const id = firstCell.textContent;

    if (id !== '1' && id !== '2') {
        if (window.confirm('Да ли желите да избришете ове податке?')) {
            await deletePoverenik(id);
            row.remove();
            ucitajTabeluPov();
        }
    } else {
        alert('Не можете брисати налог нивоа Superadmin или Admin');
    }
};


const deletePoverenik = async (id) => {
    try {
        const url = '/controllers/adminController/AdminCtrl.php';
        const requestBody = {
            action: 'deletePoverenik',
            sendData: {
                id: id
            }
        };

        const response = await fetch(url, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestBody)
        });

        const result = await response.json();
        console.log(result); // Handle the response accordingly, if needed
    } catch (error) {
        console.error('Error:', error);
    }
};

const ucitajSlobodneOpstineAdminOkrug = async () => {
    try {
        const url = '/controllers/adminController/AdminCtrl.php';
        const requestBody = {
            action: 'slobodneOpstine'
        };
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestBody)
        });

        const fetchData = await response.json();
        const fetchNiz = fetchData.niz;

        const opstine = document.getElementById('poverenistvoAdminOkrug');
        //opstine.innerHTML = '';

        fetchNiz.forEach(element => {
            const option = document.createElement('option');
            option.value = element.id;
            option.textContent = element.naziv_opstine;
            opstine.appendChild(option);
        });

    } catch (error) {
        console.error('Error:', error);
    }
};

// Inicijalizacija niza za izabrane 
const selectedOptions = [];

// Get the select element
const selectElement = document.getElementById("poverenistvoAdminOkrug");

// Get the output div
const outputDiv = document.getElementById("output");

// Add change event listener to the select element
if (selectElement) {
    selectElement.addEventListener("change", () => {
        // Get the selected option
        const selectedOption = selectElement.options[selectElement.selectedIndex];

        // Check if the option is not already in the array
        if (!selectedOptions.find(option => option.value === selectedOption.value)) {
            // Add the option to the array
            selectedOptions.push({value: selectedOption.value, text: selectedOption.textContent});

            // Update the output
            updateOutput();
        }
    });

// Ažuriranje output-a, izabranih opština i dugmića za brisanje pored njih
    const updateOutput = () => {
        outputDiv.innerHTML = "";
        console.log(selectedOptions);
        // Prolazak kroz selectedOptions niz
        selectedOptions.forEach(option => {
            // Kreiranje span elementa za svaku izabranu opciju
            const optionSpan = document.createElement("span");

            // Dodavanje teksta opcije span elementu
            optionSpan.textContent = option.text;

            // Kreiranje dugmeta za uklanjanje opcije
            const removeButton = document.createElement("button");
            removeButton.textContent = "X";
            removeButton.classList.add("btn", "btn-danger", "mx-2", "me-3");

            // Postavljanje click event listener-a na dugmad
            removeButton.addEventListener("click", () => {
                // Uklanjanje opcije iz niza
                const index = selectedOptions.findIndex(opt => opt.value === option.value);
                if (index !== -1) {
                    selectedOptions.splice(index, 1);
                }

                // Ažuriranje output
                updateOutput();

            });

            // Append the remove button to the option span
            optionSpan.appendChild(removeButton);

            // Append the option span to the output span
            outputDiv.appendChild(optionSpan);
        });
    };

// Get the form element
    const formElement = document.getElementById("unosOkrugForm");
    //Poruka o čuvanju okruga i poverenistva
    const messageElement = document.getElementById("messagePoverenistvoUnos");
// Dodavanje submit event listener-a formi
    formElement.addEventListener("submit", function (event) {
        // Prevent the default form submission
        event.preventDefault();

        // Provera da li je niz prazan
        if (selectedOptions.length > 0) {
            // Display the confirmation modal
            $('#confirmationModalPoverenistvo').modal('show');
        } else {
            showMessage("Молимо изаберите бар једно повереништво.", "red");

            setTimeout(() => {
                showMessage("");
            }, 3000);
        }
    });

    const confirmButtonPov = document.getElementById("confirmButtonPov");
    confirmButtonPov.addEventListener("click", async (e) => {
        try {
            e.preventDefault();
            // Your asynchronous code (fetch) here
            const url = '/controllers/adminController/AdminCtrl.php';
            const nazivOkruga = document.getElementById("naziv_okruga");
            const requestBody = {
                action: 'sacuvajOkrug',
                sendData: {
                    okrug: nazivOkruga.value,
                    niz: selectedOptions
                }
            };

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(requestBody)
            });

            const fetchData = await response.json();
            if (fetchData.status === 'success') {
                showMessage(fetchData.message, 'green');

                setTimeout(() => {
                    formElement.submit();
                }, 1000);

            } else {
                showMessage(fetchData.message, 'red');
            }

        } catch (error) {
            console.log(error);
        }
    });

// Poruka ukoliko se ne izabere predstavnistvo
    function showMessage(message, color) {
        messageElement.innerHTML = `<p style="color: ${color};">${message}</p>`;
    }
}


const deleteOkrug = async (id) => {
    try {
        const url = '/controllers/adminController/AdminCtrl.php';
        const okrugID = document.getElementById('okrugDelete').value;

        const requestBody = {
            action: 'deleteOkrug',
            sendData: {
                id: okrugID
            }
        };

        const response = await fetch(url, {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestBody)
        });

        const result = await response.json();
        console.log(result); // Handle the response accordingly, if needed
    } catch (error) {
        console.error('Error:', error);
    }
};

const dugmeDeleteOkrug = document.getElementById('deleteOkrugBtn');
if (dugmeDeleteOkrug) {
    dugmeDeleteOkrug.addEventListener('click', () => {
        const confirmed = window.confirm('Ako izbrišete okrug biće izbrisani svi poverenici i glasači koje je on uneo?');
        if (confirmed) {
            deleteOkrug(); // Call the deleteOkrug function if the user confirms
        }
    });
}






