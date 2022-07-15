let typeInput = document.getElementById('type');
let customerTypeInput = document.getElementById('customer_type');
let dobInput = document.getElementById('dob');
let nicInput = document.getElementById('nic');
let guardianNicInput = document.getElementById('guardian_nic');
let ownerNicInput = document.getElementById('owner_nic');
let empBranchInput = document.getElementById('emp_branch');
let nameInput = document.getElementById('name');
let unameInput = document.getElementById('username');
let passwdInput = document.getElementById('password');
let cnfpasswdInput = document.getElementById('cnfpassword');
let submitBtn = document.getElementById('submitBtn');

const nic_pattern = /^[A-Z0-9]{10,14}$/;

function keyPressFn(e, pattern, nxt) {
    if (e.keyCode === 13) {
        e.preventDefault();
        let value = e.target.value.trim();
        if (!pattern.test(value)) {
            return;
        }
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
            document.getElementById('organization_div').hidden = true;
            document.getElementById('individual_div').hidden = false;
        } else {
            document.getElementById('individual_div').hidden = true;
            document.getElementById('organization_div').hidden = false;
            ownerNicInput.focus();
        }
    };
}

if (dobInput) {
    dobInput.setAttribute("max", getDateStr());
    dobInput.onchange = () => {
        let dob = getDOB();
        let age = getAge(dob);
        if (age >= 18) {
            document.getElementById('under_18_div').hidden = true;
            document.getElementById('over_18_div').hidden = false;
        } else {
            document.getElementById('over_18_div').hidden = true;
            document.getElementById('under_18_div').hidden = false;
        }
    }
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
    if (empBranchInput) empBranchInput.value = '';
}

if (nicInput) nicInput.onkeydown = event => { keyPressFn(event, nic_pattern, 'name'); };
if (guardianNicInput) guardianNicInput.onkeydown = event => { keyPressFn(event, nic_pattern, 'name'); };
if (ownerNicInput) ownerNicInput.onkeydown = event => { keyPressFn(event, nic_pattern, 'name'); };
if (empBranchInput) empBranchInput.onkeydown = event => { keyPressFn(event, branch_id_pattern, 'name'); };
nameInput.onkeydown = event => { keyPressFn(event, /^[a-zA-Z.\s]{5,100}$/, 'username'); };
unameInput.onkeydown = event => { keyPressFn(event, username_pattern, 'password'); };
passwdInput.onkeydown = event => { keyPressFn(event, password_pattern, 'cnfpassword'); };
cnfpasswdInput.onkeydown = event => { keyPressFn(event, password_pattern, ''); };

submitBtn.onclick = e => {
    e.preventDefault();
    let name = nameInput.value.trim();
    let username = unameInput.value.trim();
    let password = passwdInput.value.trim();
    let cnfpassword = cnfpasswdInput.value.trim();
    let customer_type = null;
    let dob = null;
    let nic = null;
    let guardian_nic = null;
    let owner_nic = null;
    let emp_branch = null;
    if (!/^[a-zA-Z.\s]{5,100}$/.test(name)) {
        setModal(false, "Invalid name");
        return;
    }
    if (!username_pattern.test(username)) {
        setModal(false, "Invalid username");
        return;
    }
    if (!password_pattern.test(password)) {
        setModal(false, "Invalid password");
        return;
    }
    if (password !== cnfpassword) {
        setModal(false, "Passwords does not match");
        return;
    }
    if (customerTypeInput) {
        customer_type = customerTypeInput.value;
        dob = getDOB();
        nic = nicInput.value.trim();
        guardian_nic = guardianNicInput.value.trim();
        owner_nic = ownerNicInput.value.trim();
        if (customer_type === 'individual') {
            if (!dob || !(dob instanceof Date)) {
                setModal(false, "Invalid Date of Birth");
                return;
            }
            if (getAge(dob) >= 18) {
                if (!nic || !nic_pattern.test(nic)) {
                    setModal(false, "Invalid NIC");
                    return;
                }
            } else {
                if (!guardian_nic || !nic_pattern.test(guardian_nic)) {
                    setModal(false, "Invalid Guardian NIC");
                    return;
                }
            }
        } else {
            if (!owner_nic || !nic_pattern.test(owner_nic)) {
                setModal(false, "Invalid Owner NIC");
                return;
            }
        }
    } else if (empBranchInput) {
        emp_branch = empBranchInput.value;
        if (!emp_branch || !branch_id_pattern.test(emp_branch)) {
            setModal(false, "Invalid Branch ID");
            return;
        }
    }
    let xhrSender = new XHRSender(document.URL, resp => {
        try {
            let data = JSON.parse(resp);
            if (data.hasOwnProperty('success') && data['success'] === true) {
                clear();
                setModal(true, 'User added successfully');
                //setTimeout(e => { window.location = 'index.php'; }, 1000);
                return;
            }
            if (data.hasOwnProperty('reason') && data['reason'] instanceof String) {
                setModal(false, data['reason']);
            } else {
                setModal(false, 'Sorry try again');
            }
        } catch (e) {
            setModal(false, 'Error occured');
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
    if (emp_branch) xhrSender.addField('branch', emp_branch);
    xhrSender.send();
};