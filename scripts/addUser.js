let typeInput = document.getElementById('type');
let customerTypeInput = document.getElementById('customer_type');
let dobInput = document.getElementById('dob');
let nicInput = document.getElementById('nic');
let guardianNicInput = document.getElementById('guardian_nic');
let ownerNicInput = document.getElementById('owner_nic');
let nameInput = document.getElementById('name');
let unameInput = document.getElementById('username');
let passwdInput = document.getElementById('password');
let cnfpasswdInput = document.getElementById('cnfpassword');
let submitBtn = document.getElementById('submitBtn');

function keyPressFn(e, nxt) {
    if (e.keyCode === 13) {
        e.preventDefault();
        if (nxt == '') {
            document.getElementById("submitBtn").click();
        } else {
            let nextElem = document.getElementById(nxt);
            if (nextElem) {
                nextElem.focus();
            }
        }
    }
}

function getDateStr() {
    let d = new Date();
    d.setDate(d.getDate());
    let month = "" + (d.getMonth() + 1),
        day = "" + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = "0" + month;
    if (day.length < 2) day = "0" + day;

    return [year, month, day].join("-");
}

function getDayOfYear(date) {
    var start = new Date(date.getFullYear(), 0, 0);
    var diff = (date - start) + ((start.getTimezoneOffset() - date.getTimezoneOffset()) * 60000);
    var oneDay = 60000 * 60 * 24;
    return Math.floor(diff / oneDay);
}

function getDOB() {
    if (!dobInput) return null;
    try {
        let dobstr = dobInput.value;
        let dob = new Date(dobstr);
        dob.setTime(dob.getTime() + dob.getTimezoneOffset() * 60000)
        return dob;
    } catch (e) {
        return null;
    }
}

function getAge(dob) {
    let today = new Date();
    let age = today.getFullYear() - dob.getFullYear()
    if (dob.getMonth() > today.getMonth() || (dob.getMonth() == today.getMonth() && dob.getDate() > today.getDate())) {
        age--;
    }
    return age;
}

if (customerTypeInput) {
    customerTypeInput.onchange = () => {
        let cusType = customerTypeInput.value;
        if (cusType === 'individual') {
            document.getElementById('individual_div').hidden = false;
            document.getElementById('organization_div').hidden = true;
        } else {
            document.getElementById('individual_div').hidden = true;
            document.getElementById('organization_div').hidden = false;
        }
    };
}

if (dobInput) {
    dobInput.setAttribute("max", getDateStr());
    dobInput.onchange = () => {
        let dob = getDOB();
        let age = getAge(dob);
        if (age >= 18) {
            document.getElementById('over_18_div').hidden = false;
            document.getElementById('under_18_div').hidden = true;
        } else {
            document.getElementById('over_18_div').hidden = true;
            document.getElementById('under_18_div').hidden = false;
        }
    }
}

function showMessage(msg) {
    alert(msg); // TODO: modify this to show in a better way
}

function clear() {
    nameInput.value = '';
    unameInput.value = '';
    passwdInput.value = '';
    cnfpasswdInput.value = '';
    if (dobInput) dobInput.value = '';
    if (nicInput) nicInput.value = '';
    if (guardianNicInput) guardianNicInput.value = '';
    if (ownerNicInput) ownerNicInput.value = '';
}

submitBtn.onclick = e => {
    e.preventDefault();
    let name = nameInput.value;
    let username = unameInput.value;
    let password = passwdInput.value;
    let cnfpassword = cnfpasswdInput.value;
    let customer_type = null;
    let dob = null;
    let nic = null;
    let guardian_nic = null;
    let owner_nic = null;
    if (!/^[a-zA-Z.\s]{5,100}$/.test(name)) {
        showMessage("Invalid name");
        return;
    }
    if (!/^[a-zA-Z0-9._]{5,12}$/.test(username)) {
        showMessage("Invalid username");
        return;
    }
    if (!/^[\x21-\x7E]{8,15}$/.test(password)) {
        showMessage("Invalid password");
        return;
    }
    if (password !== cnfpassword) {
        showMessage("Passwords does not match");
        return;
    }
    if (customerTypeInput) {
        customer_type = customerTypeInput.value;
        dob = getDOB();
        nic = nicInput.value;
        guardian_nic = guardianNicInput.value;
        owner_nic = ownerNicInput.value;
        if (customer_type === 'individual') {
            if (!dob || !(dob instanceof Date)) {
                showMessage("Invalid Date of Birth");
                return;
            }
            if (getAge(dob) >= 18) {
                if (!nic || !/^[A-Z0-9]{10,14}$/.test(nic)) {
                    showMessage("Invalid NIC");
                    return;
                }
            } else {
                if (!guardian_nic || !/^[A-Z0-9]{10,14}$/.test(guardian_nic)) {
                    showMessage("Invalid Guardian NIC");
                    return;
                }
            }
        } else {
            if (!owner_nic || !/^[A-Z0-9]{10,14}$/.test(owner_nic)) {
                showMessage("Invalid Owner NIC");
                return;
            }
        }
    }
    let xhrSender = new XHRSender(document.URL, resp => {
        try {
            let data = JSON.parse(resp);
            if (data.hasOwnProperty('success') && data['success'] === true) {
                clear();
                showMessage('User added successfully');
                //setTimeout(e => { window.location = 'index.php'; }, 1000);
                return;
            }
            if (data.hasOwnProperty('reason') && data['reason'] instanceof String) {
                showMessage(data['reason']);
            } else {
                showMessage('Sorry try again');
            }
        } catch (e) {
            showMessage('Error occured');
        }
    });
    xhrSender.addField('name', name);
    xhrSender.addField('username', username);
    xhrSender.addField('password', password);
    xhrSender.addField('type', typeInput.value);
    if (customer_type) xhrSender.addField('customer_type', customer_type);
    if (dob) xhrSender.addField('DoB', dob.toString().split(' (')[0]);
    if (nic) xhrSender.addField('NIC', nic);
    else if (guardian_nic) xhrSender.addField('guardianNIC', guardian_nic);
    if (owner_nic) xhrSender.addField('ownerNIC', owner_nic);
    xhrSender.send();
};